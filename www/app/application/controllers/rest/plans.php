<?
class Plans extends REST_Controller {

    protected static $fields = array(
        'blush_journal' => 'money',
        'blush_video' => 'money',
    );

    function __construct() {
        parent::__construct();
        $this->load->helper('json');
        $this->load->model('Plan');
    }

    public function index_get() {
        $plans = $this->Plan->get();
        $this->response($plans);
    }


    /**
     * Returns the pricing model from a request to GET - /app/rest/plans/pricing
     */
    public function pricing_get() {
        $pricing = new stdClass;

        /* Load the non-member pricing */
        $pricing = array();
        foreach(Plans::$fields as $field => $datatype) {
            $pricing[$field] = convert_field(get_option($field), $datatype);
        }
        $pricing['plans'] = $this->Plan->get();

        $this->response($pricing);
    }

    /**
     * Validates a coupon code for a plan
     */
    public function coupon_code_get() {
        $code = $this->get('code');
        $plan_id = intval($this->get('plan_id'));

        if($plan_id) {
            $plan = $this->Plan->load($plan_id);
            if($plan && $plan->use_discount_price && $plan->coupon_code==$code) {
                $price = $plan->discount_price;
                json_success("The coupon you have entered is correct", array('price' => $price));
                return;
            } else {

                \Stripe\Stripe::setApiKey($this->config->item('stripe_private_key'));

                /* Look up the coupon and try to figure out what the price is */
                try {
                    $coupon = \Stripe\Coupon::retrieve($code);
                    if($coupon) {

                        if(!$coupon->valid) {
                            log_message('error', sprintf('[Coupon Code] Coupon Invalid [%s] ', print_r($coupon, TRUE)));
                            json_error('The coupon you have entered is no longer valid.');
                            return false;
                        }

                        $price = $plan->price;
                        if($coupon->amount_off>0) {
                            $price = $price - $coupon->amount_off;
                        } else if($coupon->percent_off) {
                            $price = $price - ($price * ($coupon->percent_off*.01));
                        }
                        log_message('info', sprintf('[Coupon Code] Coupon Applied [%s] ', print_r($coupon, TRUE)));

                        json_success("The coupon you have entered is correct", array('price' => max(0, $price), 'coupon' => (array)$coupon));
                        return;
                    }

                } catch(Exception $e) {
                    loggly(array(
                        'text' => $e->getMessage(),
                        'method' => 'rest.plans.coupon_code_get',
                        'exception' => $e,
                        'code' => $code
                    ));

                }
            }
        }

        json_error('The coupon code you have entered is invalid');
    }
}
?>