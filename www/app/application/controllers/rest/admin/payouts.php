<?
class Payouts extends REST_Controller {

    function __construct() {
        parent::__construct();
        $this->validate_admin();
        $this->load->model('Payout');
        $this->load->helper('json');
    }

    public function index_post() {
        $this->load->helper('json');
        $count = $this->Payout->get_count($this->get_grid_filter());
        $objects = $this->Payout->get_list($this->get_grid_limit(), $this->get_grid_offset(), $this->get_grid_ordering(), $this->get_grid_filter());
        $objects = $this->decorate_objects($objects);
        $result = grid_result($count, $objects);
        echo $result;
    }

    public function payout_get($uuid='') {

        $payout = $this->Payout->load_by_uuid($uuid);
        /* Unset things in the json that aren't necessary */
        $user = $this->decorate_object($payout);
        $this->response($payout);
    }

    public function payable_items_post($uuid='') {
        if($uuid) {
            $this->load->model(array('Diary', 'Event'));
            $payout = $this->Payout->load_by_uuid($uuid);

            $count = $this->Payout->get_payout_transaction_counselor_count($this->get_grid_filter(), $payout->id);
            $objects = $this->Payout->get_payout_transaction_counselor_list($this->get_grid_limit(), $this->get_grid_offset(), $this->get_grid_ordering(), $this->get_grid_filter(), $payout->id);

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
                    $object->customer = '';
                    if($event) {
                        $customer = $this->User->load($event->customer_id);
                        if($customer) {
                            $object->customer = $customer->firstname." ".$customer->lastname;
                        }
                    }
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
            unset($object->stripe_data);
            $updated_objects[] = $object;
        }
        return $updated_objects;
    }

    public function decorate_object($object) {
        if($object) {
            $counselor = $this->User->load($object->counselor_id);
            $object->firstname = $counselor->firstname;
            $object->lastname = $counselor->lastname;
        }

        $object->cnt = $this->Payout->get_payout_transaction_counselor_count('', $object->id);

        $object->created = pretty_date_time($object->created);
        $object->amount = dollarfy($object->amount);
        unset($object->stripe_data);

        return $object;
    }
}
?>