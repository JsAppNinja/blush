<?
class Transactions extends REST_Controller {

    function __construct() {
        parent::__construct();
        $this->validate_admin();
        $this->load->model('Transaction');
        $this->load->helper('json');
    }

    public function index_post() {
        $this->load->helper('json');
        $count = $this->Transaction->get_count($this->get_grid_filter());
        $objects = $this->Transaction->get_list($this->get_grid_limit(), $this->get_grid_offset(), $this->get_grid_ordering(), $this->get_grid_filter());
        $objects = $this->decorate_objects($objects);
        $result = grid_result($count, $objects);
        echo $result;
    }

    public function transaction_get($uuid='') {

        $user = $this->Transaction->load_by_uuid($uuid);
        /* Unset things in the json that aren't necessary */
        $user = $this->decorate_object($user);
        $this->response($user);
    }

    public function decorate_objects($objects)
    {
        $updated_objects = array();
        foreach ($objects as $object) {
            $object->DT_RowId = $object->uuid;

            $object->customer = get_user_name($object->customer_id);
            $object->counselor = get_user_name($object->counselor_id);

            $object->amount = dollarfy($object->amount);
            $object->created = pretty_date($object->created);
            $updated_objects[] = $object;
        }
        return $updated_objects;
    }

    public function decorate_object($object) {
        $object->created = pretty_date_time($object->created);
        $object->amount = dollarfy($object->amount);

        $object->customer = get_user_name($object->customer_id);
        $object->counselor = get_user_name($object->counselor_id);
        $object->customer_uuid = $this->User->get_uuid($object->customer_id);
        $object->counselor_uuid = $this->User->get_uuid($object->counselor_id);
        return $object;
    }
}
?>