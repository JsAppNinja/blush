<?
class Notes extends REST_Controller {

    /* TODO: Need to secure these calls */

    function __construct() {
        parent::__construct();
        $this->validate_user();
        $this->load->model(array('Note'));
        $this->load->helper('json');
    }

    public function user_get($uuid='') {
        $this->validate_customer_counselor($uuid);
        $notes = $this->Note->load_by_customer_counselor($this->User->get_id($uuid), get_user_id());
        $this->response($this->decorate_objects($notes));
    }

    public function note_post($uuid) {
        $this->validate_customer_counselor($uuid);
        $data = $this->get_post_fields($this->Note->get_fields());
        $data['customer_id'] = $this->User->get_id($uuid);
        $data['counselor_id'] = get_user_id();

        $id = $this->Note->add($data);
        json_success('The note you created has been saved successfully.', array('uuid'=>$this->Note->get_uuid($id)));
    }

    public function decorate_objects($objects)
    {
        $updated_objects = array();
        foreach ($objects as $object) {
            $object->created = pretty_date_time_shorter($object->created);
            $updated_objects[] = $object;
        }
        return $updated_objects;
    }
}
?>