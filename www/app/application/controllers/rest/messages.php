<?
class Messages extends REST_Controller {

    /* TODO: Need to secure these calls */

    function __construct() {
        parent::__construct();
        $this->validate_user();
        $this->load->model(array('Message', 'Conversation'));
        $this->load->helper('json');
    }

    public function index_get() {
       $this->get_conversation_messages('');
    }

    public function conversation_get($uuid='') {
        $this->get_conversation_messages($uuid);
    }

    public function message_get($uuid = '') {
        $message = $this->Message->load_by_uuid($uuid);
        $this->response($message);
    }

    public function message_post() {
        /* Need to find the conversation for this message */
        $user = get_user();
        $customer_id = 0;
        $conversation_id = 0;
        $counselor_id = 0;
        $recipient_id = 0;

        $to_customer = false;

        /* Determine who the sender/receiver and customer/counselor are */
        if($user->user_type_id==USER_TYPE_CUSTOMER) {
            $counselor = get_counselor($user->id);
            $customer_id = $user->id;
            if($counselor) {
                $counselor_id = $this->User->get_id($counselor->uuid);
                $recipient_id = $counselor_id;
            }

        } else {
            $recipient_uuid = $this->post('recipient');

            /* If this is sent from the 'new message' panel, we have the recipient uuid, if it is sent from
            the message list, we only have the conversation uuid
            */
            if($recipient_uuid) {
                $conversation = $this->Conversation->load_by_customer_counselor($user->id, $user->id);
                $recipient = $this->User->load_by_uuid($recipient_uuid);
            }  else {
                $conversation_uuid = $this->post('conversation_uuid');
                $conversation = $this->Conversation->load_by_uuid($conversation_uuid);
                $recipient = $this->User->load($conversation->customer_id);
            }

            if($recipient) {
                $customer_id = $recipient->id;
                $recipient_id = $customer_id;
            }

            $counselor_id = $user->id;
            $to_customer = true;
        }

        /* Load or create the conversation */
        $conversation = $this->Conversation->load_by_customer_counselor($customer_id, $counselor_id);
        if(!$conversation) {
            $conversation_id = $this->Conversation->add(array(
                'customer_id' => $customer_id,
                'counselor_id' => $counselor_id
            ));
        } else {
            $conversation_id = $conversation->id;
        }

        if($conversation_id && $recipient_id) {
            $data = $this->get_post_fields($this->Message->get_fields());
            $data['sender_id'] = $user->id;
            $data['recipient_id'] = $recipient_id;
            $data['conversation_id'] = $conversation_id;

            $id = $this->Message->add($data);

            /* If this message is being sent to a counselor, notify the counselor */
            $this->load->helper('notification');
            if(!$to_customer) {
                notify_counselor_mail($counselor_id);
            } else {
                notify_customer_mail($customer_id, $counselor_id);
            }

            $this->Conversation->update($conversation_id, array(
                'excerpt' => word_limiter($data['text'], 20, ''),
                'modified' => timestamp_to_mysqldatetime(now()),
                'read' => 0
            ));

            json_success('The message you created has been saved successfully.', array('uuid'=>$this->Message->get_uuid($id)));
            return;
        }

        json_error("There was a problem sending your message");
    }

    public function message_put($uuid = 0) {
        $this->Message->update_by_uuid($uuid, $this->get_put_fields($this->Message->get_fields()));
        json_success('Your message has been updated successfully.');
    }

    public function decorate_objects($objects)
    {
        $updated_objects = array();
        foreach ($objects as $object) {

            $sender = get_user($object->sender_id);
            $object->sender = $sender->firstname." ".$sender->lastname;
            $object->sender_picture = get_avatar(IMG_SIZE_MD, $sender);
            $object->recipient = get_user_name($object->recipient_id);
            $object->created = pretty_date_time_shorter($object->created);
            $object->text = nl2br($object->text);

            if(!$object->viewed && $object->recipient_id == get_user_id()) {
                $this->Message->update($object->id, array('viewed'=>1));
            }

            $updated_objects[] = $object;
        }
        return $updated_objects;
    }

    private function get_conversation_messages($conversation_uuid='') {
        $user = get_user();

        if($user->user_type_id==USER_TYPE_CUSTOMER) {
            $counselor = get_counselor($user->id);
            $counselor_id = 0;
            if($counselor) {
                $counselor_id = $this->User->get_id($counselor->uuid);
            }
            $conversation = $this->Conversation->load_by_customer_counselor($user->id, $counselor_id);
            if($conversation) {
                $messages = $this->Message->get_list(9999, 0, array('sort' => 'created', 'dir' => 'DESC'), '', $conversation->id);
                $this->response($this->decorate_objects($messages));
                return;
            }
        } else if($user->user_type_id == USER_TYPE_COUNSELOR) {
            $conversation = NULL;
            if($conversation_uuid) {
                $conversation = $this->Conversation->load_by_uuid($conversation_uuid);
            } else {
                $conversation = $this->Conversation->get_first($user->id);
            }
            if($conversation) {
                $messages = $this->Message->get_list(9999, 0, array('sort' => 'created', 'dir' => 'DESC'), '', $conversation->id);
                $this->response($this->decorate_objects($messages));
                return;
            }
        }

        $messages = array();
        $this->response($messages);

    }
}
?>