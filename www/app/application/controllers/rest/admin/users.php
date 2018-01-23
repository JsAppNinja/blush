<?
class Users extends REST_Controller {

    function __construct() {
        parent::__construct();
        $this->validate_admin();
        $this->load->helper('json');
        $this->load->model(array('Registration','Plan'));

    }

    /**
     * Returns a single user -- either the logged-in user if the id = 0 or the user for the id
     * @param int $id The id of the user
     */
    public function user_get($uuid='') {

        $user = $this->User->load_by_uuid($uuid);

        $registration = $this->Registration->load_by_user_id($user->id);
        if($registration) {
            $registration->data = json_decode($registration->data);
            $user->registration = $registration;

            if(!$user->state && $registration->data->state) {
                $user->state = $registration->data->state;
            }
        }

        $user->counselor = '';
        if($user->user_type_id == USER_TYPE_CUSTOMER) {
            $counselor = $this->User->load_counselor($user->id);
            if($counselor) {
                $user->counselor = $this->decorate_object($counselor);
            }
        }

        /* Unset things in the json that aren't necessary */
        $user = $this->decorate_object($user);
        $this->response($user);
    }

    public function user_put($uuid = 0) {
        $fields =  $this->get_put_fields($this->User->get_fields());
        /* Allow them to set the credits field */
        $fields['credits'] = $this->put('credits', TRUE);
        $this->User->update_by_uuid($uuid, $fields);

        json_success('Your profile has been updated successfully');
    }

    public function user_post() {
        $user = get_user();
        $id = $this->User->add($this->get_post_fields($this->User->get_fields()));
        json_success('The user you created has been saved successfully.', array('uuid'=>$this->User->get_uuid($id)));
    }

    /**
     * Assigns the customer to a counselor
     */
    public function user_counselor_post() {
        $user_uuid = $this->post('uuid');
        $counselor_id = $this->post('counselor_id');

        $this->User->assign_counselor($user_uuid, $counselor_id);

        $customer_id = $this->User->get_id($user_uuid);

        $this->load->helper('notification');
        notify_new_customer($counselor_id, $customer_id);

        $this->user_get($user_uuid);
    }

    public function counselors_post() {
        $this->load->helper('json');
        $count = $this->User->get_count($this->get_grid_filter(), USER_TYPE_COUNSELOR);
        $objects = $this->User->get_list($this->get_grid_limit(), $this->get_grid_offset(), $this->get_grid_ordering(), $this->get_grid_filter(), USER_TYPE_COUNSELOR);
        $objects = $this->decorate_objects($objects);
        $result = grid_result($count, $objects);
        echo $result;
    }

    public function customers_post($counselor_id = 0) {

        if(!$counselor_id) {
            $counselor_id = intval($this->post('counselor_id'));
        }
        $this->load->helper('json');
        $count = $this->User->get_count($this->get_grid_filter(), USER_TYPE_CUSTOMER, $counselor_id);
        $objects = $this->User->get_list($this->get_grid_limit(), $this->get_grid_offset(), $this->get_grid_ordering(), $this->get_grid_filter(), USER_TYPE_CUSTOMER, $counselor_id);
        $objects = $this->decorate_objects($objects);
        $result = grid_result($count, $objects);
        echo $result;
    }

    public function delete_post() {
        $uuid = $this->post('uuid');
        if($uuid) {
            $user = $this->User->load_by_uuid($uuid);
            if($user) {
                /** Delete their stripe account */
                if($user->stripe_customer_id) {
                    try {
                        \Stripe\Stripe::setApiKey($this->config->item('stripe_private_key'));
                        $cu = \Stripe\Customer::retrieve($user->stripe_customer_id);
                        $cu->delete();
                    } catch(Exception $e) {
                        /* Ignore it */
                    }
                }
                $this->User->delete_permanently($user->id);
                json_success('The user has been permanently deleted successfully.', array('uuid'=>$uuid));
            }
        }
    }

    public function decorate_objects($objects)
    {
        $updated_objects = array();
        foreach ($objects as $object) {
            $object->DT_RowId = $object->uuid;
            $object->created = pretty_date_short($object->created);
            $object->last_login = pretty_date_short($object->last_login);
            $updated_objects[] = $object;
        }
        return $updated_objects;
    }

    public function decorate_object($object) {
        $object->birthday = pretty_date_short($object->birthday);
        unset($object->salt);
        unset($object->password);
        unset($object->stripe_registration_data);

        $object->mobile_phone = phone_format($object->mobile_phone);
        $object->plan = new stdClass;
        if($object->plan_id) {
            $object->plan = $this->Plan->load($object->plan_id);
        }
        $object->picture = get_avatar(IMG_SIZE_LG, $object);
        return $object;
    }
}
?>