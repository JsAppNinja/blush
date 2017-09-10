<?
class Notifications extends REST_Controller {

    function __construct() {
        parent::__construct();
        $this->validate_admin();
        $this->load->model('Notification');
        $this->load->helper('json');
    }

    public function index_post() {
        $this->load->helper('json');
        $count = $this->Notification->get_count($this->get_grid_filter());
        $objects = $this->Notification->get_list($this->get_grid_limit(), $this->get_grid_offset(), $this->get_grid_ordering(), $this->get_grid_filter());
        $objects = $this->decorate_objects($objects);
        $result = grid_result($count, $objects);
        echo $result;
    }

    public function notification_put($uuid = 0) {
        $this->Notification->update_by_uuid($uuid, $this->get_put_fields($this->Notification->get_fields()));
        json_success('The Notification has been updated successfully');
    }

    public function notification_get($uuid='') {

        $notification = $this->Notification->load_by_uuid($uuid);
        /* Unset things in the json that aren't necessary */
        $user = $this->decorate_object($notification);
        $this->response($notification);
    }

    public function test_post() {
        $emails = explode(',', $this->post('emails'));
        $uuid = $this->post('uuid');
        $notification_id = $this->Notification->get_id($uuid);
        $this->load->helper('notification');
        notification_test($notification_id, $emails);
        json_success("Test Sent Successfully to: ".implode(", ", $emails));
    }

    public function decorate_objects($objects)
    {
        $updated_objects = array();
        foreach ($objects as $object) {
            $object->DT_RowId = $object->uuid;

            $updated_objects[] = $object;
        }
        return $updated_objects;
    }

    public function decorate_object($object) {
        return $object;
    }
}
?>