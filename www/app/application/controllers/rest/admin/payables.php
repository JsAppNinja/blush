<?
class Payables extends REST_Controller {

    function __construct() {
        parent::__construct();
        $this->validate_admin();
        $this->load->model('Transaction_Counselor');
        $this->load->helper('json');
    }

    public function index_post() {
        $this->load->helper('json');
        $count = $this->User->get_payable_count($this->get_grid_filter());
        $objects = $this->User->get_payable_list($this->get_grid_limit(), $this->get_grid_offset(), $this->get_grid_ordering(), $this->get_grid_filter());
        $objects = $this->decorate_objects($objects);
        $result = grid_result($count, $objects);
        echo $result;
    }

    public function payable_get($uuid='') {

        $user = $this->User->load_payable($uuid);
        /* Unset things in the json that aren't necessary */
        $user = $this->decorate_object($user);
        $this->response($user);
    }

    /**
     * Submits payment to the stripe api
     */
    public function pay_post($uuid='') {

        $counselor = $this->User->load_payable($uuid);
        if($counselor) {
            $this->load->model('Payout');
            $this->load->helper('notification');

            if($counselor->amount>0) {
                \Stripe\Stripe::setApiKey($this->config->item('stripe_private_key'));
                try {

                    $transfer = \Stripe\Transfer::create(
                        array( "amount" => $counselor->amount*100,
                            "currency" => "usd",
                            "recipient" => $counselor->stripe_customer_id,
                            "description" => "Transfer for ".$counselor->firstname." ".$counselor->lastname
                        )
                    );

                    $this->Payout->add($transfer, $counselor->id);
                    notify_payment($counselor->id, $transfer);

                    json_success('Your payment has been submitted successfully as <b>transaction number '.$transfer['id'].'</b>!');
                } catch (Exception $e) {
                    log_message('info', '[Transfer] Stripe_Transfer::create Exception: ' . $e->getMessage());
                    $error = sprintf('There was a problem processing the transfer. The error returned from the authorizing gateway was \'%s\'', $e->getMessage());
                    json_error($error);
                }
            }
        }
    }

    public function payable_items_post($uuid='') {
        if($uuid) {
            $this->load->model(array('Diary', 'Event'));
            $counselor = $this->User->load_by_uuid($uuid);

            $count = $this->Transaction_Counselor->get_count($this->get_grid_filter(), $counselor->id);
            $objects = $this->Transaction_Counselor->get_list($this->get_grid_limit(), $this->get_grid_offset(), $this->get_grid_ordering(), $this->get_grid_filter(), $counselor->id);

            $updated_objects = array();
            foreach($objects as $object) {

                if($object->transaction_counselor_type_id == TRANSACTION_COUNSELOR_TYPE_DIARY) {
                    $diary = $this->Diary->load($object->object_id);
                    $object->customer = '';
                    if($diary) {
                        $customer = $this->User->load($diary->user_id);
                        if($customer) {
                            $object->customer = $customer->firstname." ".$customer->lastname;
                        }
                    }
                    $object->transaction_type = "Diary";
                } else {
                    $event = $this->Event->load($object->object_id);
                    $customer = $this->User->load($event->customer_id);
                    $object->customer = $customer->firstname." ".$customer->lastname;
                    $object->transaction_type = "Video Session";
                }
                $object->amount = dollarfy($object->amount);
                $updated_objects[] = $object;
            }

            $result = grid_result($count, $updated_objects);
            echo $result;
        }
    }

    public function decorate_objects($objects)
    {
        $updated_objects = array();
        foreach ($objects as $object) {
            $object->DT_RowId = $object->uuid;

            $object->amount = dollarfy($object->amount);
            $object->created = pretty_date($object->created);
            $updated_objects[] = $object;
        }
        return $updated_objects;
    }

    public function decorate_object($object) {
        $object->created = pretty_date_time($object->created);
        $object->account = '';
        $holder = '';
        if ($object->stripe_customer_id) {
            $stripe_recipient = get_stripe_recipient($object->id);
            $object->account = json_decode($stripe_recipient['active_account']);
            $holder = $stripe_recipient;
        }
        $object->account = 'blah';
        $sid = $object->stripe_customer_id;
        $id = $object->id;
        $object->account = 'sid: ' . var_dump($sid) . ', $id: ' . var_dump($id) . ', recip: ' . var_dump($holder);
        return $object;
    }
}
?>