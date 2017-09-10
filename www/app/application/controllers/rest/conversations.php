<?
class Conversations extends REST_Controller {

    /* TODO: Need to secure these calls */

    function __construct() {
        parent::__construct();
        $this->validate_user();
        $this->load->model(array('Message', 'Conversation'));
        $this->load->helper('json');
    }

    public function index_get() {
        $user = get_user();

        $conversations = $this->Conversation->get_list(999, 0, '', '', $user->id);
        $this->response($this->decorate_objects($conversations, $user));
    }

    /* if the user is a customer, it returns the conversation that they are having with their coach */
    public function conversation_get() {
        $user = get_user();
        if($user->user_type_id==USER_TYPE_CUSTOMER) {
            $counselor = get_counselor($user->id);
            $counselor_id = 0;
            if($counselor) {
                $counselor_id = $this->User->get_id($counselor->uuid);
            }
            $conversation = $this->Conversation->load_by_customer_counselor($user->id, $counselor_id);
            if($conversation) {
                $this->response($conversation);
            }
        }
    }

    public function decorate_objects($objects, $user)
    {
        $updated_objects = array();
        foreach ($objects as $object) {

            $customer = get_user($object->customer_id);
            $object->customer = $customer->firstname." ".$customer->lastname;
            $object->customer_picture = get_avatar(IMG_SIZE_SM, $customer);
            if($user) {
                $object->new_message_count = intval($this->Message->get_count_new($user->id, $object->id));
            }

            $object->counselor = get_user_name($object->counselor_id);
            $object->created = pretty_date_time_shorter($object->created);
            $object->modified = pretty_date_short($object->modified);
            $object->excerpt = word_limiter($object->excerpt, 5);
            $updated_objects[] = $object;
        }
        return $updated_objects;
    }
}
?>