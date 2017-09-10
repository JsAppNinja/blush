<?
class Customers extends REST_Controller {

    /* TODO: Need to secure these calls */

    function __construct() {
        parent::__construct();
        $this->validate_user();
        $this->load->helper('json');
    }

    /**
     * Returns a list of customers for the current counselor
     */
    public function index_get() {
        $this->load->model('Diary');
        $customers = $this->User->get_customers(get_user_id());
        $this->response($this->decorate_objects($customers));
    }

    public function customer_get($uuid = '') {
        $this->validate_customer_counselor($uuid);
        $customer = $this->User->load_by_uuid($uuid);
        $this->response($this->decorate_object($customer));
    }

    public function customer_post() {
        /* TODO - Validate that this is a counselor */
        $id = $this->User->add($this->get_post_fields($this->User->get_fields()));
        json_success('The customer you created has been saved successfully.', array('uuid'=>$this->User->get_uuid($id)));
    }

    public function customer_put($uuid = 0) {
        $this->validate_customer_counselor($uuid);
        $this->User->update_by_uuid($uuid, $this->get_put_fields($this->User->get_fields()));
        json_success('Your customer has been updated successfully.');
    }

    public function decorate_objects($objects)
    {
        $updated_objects = array();
        foreach ($objects as $object) {
            $updated_objects[] = $this->decorate_object($object);
        }
        return $updated_objects;
    }

    public function decorate_object($object)
    {
        /* Unset things in the json that aren't necessary */
        unset($object->salt);
        unset($object->password);
        unset($object->deleted);
        unset($object->stripe_registration_data);

        $object->created = pretty_date($object->created);
        $object->last_login = pretty_date($object->last_login);
        $object->picture = get_avatar(IMG_SIZE_MD, $object);
        $unread_diaries = $this->Diary->count_unread($object->id, true);
        $object->has_unread = 0;
        if($unread_diaries > 0) {
            $object->has_unread = 1;
        }
        return $object;
    }
}
?>