<?
class Plans extends REST_Controller {

    protected static $fields = array(
        'blush_journal' => 'money',
        'blush_journal_discount' => 'money',
        'blush_journal_discount_use'  => 'boolean',
        'blush_video' => 'money',
        'blush_video_discount' => 'money',
        'blush_video_discount_use'  => 'boolean',
    );

    function __construct() {
        parent::__construct();
        $this->validate_admin();
        $this->load->model('Plan');
        $this->load->helper('json');
    }

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
     * Called from app/admin/pricing when the user clicks the save button.  We are limited on what we can save here
     * basically can update the discount price, coupon code, and credits.  Can't change the price because stripe
     * doesn't allow updating the price of plans after they are created.
     */
    public function pricing_post() {
        \Stripe\Stripe::setApiKey($this->config->item('stripe_private_key'));

        foreach(Plans::$fields as $field => $datatype) {
           set_option($field, convert_field($this->post($field), $datatype));
        }

        $plans = $this->Plan->get();
        $index = 0;
        foreach($plans as $plan) {
            $data = array();
            foreach(Plan::$fields as $field => $datatype) {
                $data[$field] = convert_field($this->post($field."_".$index), $datatype);
            }

            /* Don't update price -- its field is disabled, so it will set it to zero */
            unset($data['price']);

            $plan = $this->Plan->load($this->post('id_'.$index));
            $this->Plan->update($plan->id, $data);

            try {
                $stripe_plan = \Stripe\Plan::retrieve($plan->stripe_plan_id);
            } catch (Exception $e) {
                /** If there is an exception, we need to create the plan */
                $response = \Stripe\Plan::create(array(
                        "amount" => ($plan->price*100),
                        "interval" => "month",
                        "name" => $plan->name,
                        "currency" => "usd",
                        "id" => $plan->stripe_plan_id)
                );
                log_message('info', '[Admin - Plans] Stripe_Plan::create response: ' . print_r($response,true));
                log_message('error', '[Admin - Plans] Stripe_Plan::create exception: '.$e->getMessage());
            }


            $stripe_coupon_id = $plan->stripe_plan_id."-".$data['coupon_code'];
            $discount = (($plan->price - $data['discount_price'])*100);
            $coupon_data = array(
                "amount_off" => $discount,
                "currency" => "usd",
                "duration" => 'once',
                "id" => $stripe_coupon_id
            );

            $stripe_coupon = NULL;
            /** Update or add the coupon on stripe */
            try {
                $stripe_coupon = \Stripe\Coupon::retrieve($plan->stripe_coupon_id);
                if($stripe_coupon) {

                    /** If the discount has changed or the id has changed, delete it and recreate it*/
                    if($stripe_coupon['amount_off']!=$discount || $stripe_coupon_id != $stripe_coupon['id']) {
                        $stripe_coupon->delete();

                        if($data['use_discount_price']) {
                            $response = \Stripe\Coupon::create($coupon_data);
                        }
                    }
                }
            } catch(Exception $e) {
                if(isset($response)) {
                    log_message('info', '[Admin - Plans] Stripe_Coupon::create response: ' . print_r($response,true));
                }
                log_message('error', '[Admin - Plans] Stripe_Coupon::create exception: '.$e->getMessage());
            }


            /** If there wasn't a coupon, go ahead and create one */
            if(!$stripe_coupon && $data['use_discount_price']) {
                try {
                    $response = \Stripe\Coupon::create($coupon_data);
                } catch(Exception $e) {
                    if($response) {
                        log_message('info', '[Admin - Plans] Stripe_Coupon::create response: ' . print_r($response,true));
                    }
                    log_message('error', '[Admin - Plans] Stripe_Coupon::create exception: '.$e->getMessage());
                }
            }

            $this->Plan->update($plan->id, array('stripe_coupon_id'=>$stripe_coupon_id));

            $index++;
        }

        json_success('The Pricing Information has been updated successfully');
    }

    public function decorate_object($object) {
        return $object;
    }
}
?>