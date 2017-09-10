<?
class Diaries extends REST_Controller {

    /* TODO: Need to secure these calls */

    function __construct() {
        parent::__construct();
        $this->validate_user();
        $this->load->model('Diary');
        $this->load->helper('json');
    }

    public function index_get() {
        $diaries = $this->Diary->get_list(999, 0, NULL, NULL, get_user_id());
        $this->response($diaries);
    }

    /**
     * Get a list of diaries for a user as the user's counselor
     * @param $uuid
     */
    public function user_get($uuid) {
        $this->validate_customer_counselor($uuid);

        $user_id = $this->User->get_id($uuid);

        $diaries = $this->Diary->get_list(999, 0, NULL, NULL, $user_id, true);
        $this->response($this->decorate_objects($diaries));
    }

    public function diary_get($uuid = '') {
        $diary = $this->Diary->load_by_uuid($uuid);
        $user = get_user();

        /* Validate that this user owns the diary */
        $this->validate_user_owner($diary, $user->id);

        /* If this diary has comments and the user is fetching it, we should mark it as comments read */
        $this->Diary->update($diary->id, array('comments_read'=> 1));

        $this->response($this->decorate_object($diary));
    }

    /**
     * Customer is submitting a new diary to be reviewed by their coach.
     */
    public function diary_post() {
        $id = $this->Diary->add($this->get_post_fields($this->Diary->get_fields()));

        // If the journal isn't a draft, remove their credits and send it to their counselor
        if(intval($this->post('draft', TRUE))===0) {
            /* Need to remove credits from the user's account */
            $user = get_user();

            if($user->credits < CREDITS_DIARY) {
                log_message('error', sprintf('Customer %s cannot create a new journal due to lack of credits.  '
                    .' Current Credits: [%d]', $user->email, $user->credits));

                json_error('You do not have enough credits to create a new journal at this time.');
                exit;
            }

            $credits = max($user->credits - CREDITS_DIARY, 0);
            $this->User->update($user->id, array('credits'=> $credits));
            log_message('info', sprintf('Customer %s successfully created a new journal.  '
                .' Previous Credits: [%d], New Credits [%d]', $user->email, $user->credits, $credits));

            $this->load->helper('notification');
            notify_newdiary($id);

            json_success('The journal you created has been sent to your coach successfully.', array('uuid'=>$this->Diary->get_uuid($id)));
        } else {
            json_success('The journal you created has been saved as a draft successfully.  '
                .'You can edit it by picking it from your journal list through clicking on the "journals" button above.',
                array('uuid'=>$this->Diary->get_uuid($id)));
        }
    }

    public function diary_put($uuid = 0) {
        $return_data = array();
        $diary = $this->Diary->load_by_uuid($uuid);

        $user = get_user();

        $data = $this->get_put_fields($this->Diary->get_fields());
        $message = 'Diary updated successfully.';

        if($user->user_type_id==USER_TYPE_COUNSELOR) {

            /* Validate that this counselor is assigned this customer */
            $this->validate_customer_counselor($uuid, $diary->user_id);

            /* If the counselor has submitted comments, notify the user */
            if(isset($data['comments']) && $data['comments']) {
                $message = "Your comments on this diary post have been updated successfully.";
                $this->load->helper('notification');
                notify_diaryresponse($diary->id);
            }
        } else {
            /* Validate that this user owns the diary */
            $this->validate_user_owner($diary, $user->id);

            // If they are sending a diary that was a draft, send it to the coach
            if(intval($diary->draft) === 1 && $data['draft'] === 0) {
                $credits = max($user->credits - CREDITS_DIARY, 0);
                $this->User->update($user->id, array('credits'=> $credits));

                $this->load->helper('notification');
                notify_newdiary($diary->id);
                $message = 'Your diary has been saved and sent to your coach successfully.';
                $return_data['uuid'] = $diary->uuid;
            } else {
                $message = 'Your diary has been updated successfully.';
            }
        }
        if(isset($data['comments']) && $data['comments']) {
            $data['commentor_id'] = $user->id;
        }

        $this->Diary->update_by_uuid($uuid, $data);
        json_success($message, $return_data);
    }

    public function decorate_object($object) {
        if(!$object->id) {
            $object->draft = 1;
        }


        $object->read = intval($object->read);
        $object->closed_out = intval($object->closed_out);
        $object->draft = intval($object->draft);
        $object->comments = nl2br($object->comments);
        $commentor_id = $object->commentor_id;
        if($commentor_id) {
            $object->commenter_picture = get_avatar(IMG_SIZE_MD, $this->User->load($commentor_id));
        }
        return $object;
    }

    public function decorate_objects($objects)
    {
        $updated_objects = array();
        foreach ($objects as $object) {
            $updated_objects[] = $this->decorate_object($object);
        }
        return $updated_objects;
    }
}
?>