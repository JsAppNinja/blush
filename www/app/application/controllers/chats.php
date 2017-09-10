<?php if (!defined('BASEPATH')) exit('No direct script access allowed');
use OpenTok\OpenTok;

class Chats extends User_Controller
{

    function __construct()
    {
        parent::__construct();
        $this->load->model('Event');
    }

    public function event($uuid)
    {
        $this->validate_user();

        $event = $this->Event->load_by_uuid($uuid);
        if ($event) {

            $session_id = '';
            $token = '';

            try {
                $opentok = new OpenTok($this->config->item('open_tok_api_key'), $this->config->item('open_tok_api_secret'));

                if (!$event->session_id) {
                    //$session = $opentok->createSession($_SERVER["REMOTE_ADDR"],
                    //    array(SessionPropertyConstants::P2P_PREFERENCE=>"enabled"));
                    $session = $opentok->createSession(array('location' => $_SERVER["REMOTE_ADDR"]));

                    if ($session) {
                        $session_id = $session->getSessionId();
                        $this->Event->update($event->id, array('session_id'=> $session_id));
                    }

                } else {
                    $session_id = $event->session_id;
                }

                /* IF this is the customer, validate their customer session token */
                if(get_user_id() == $event->customer_id) {
                    if(!$event->customer_token) {
                        $token = $opentok->generateToken($session_id);
                        $this->Event->update($event->id, array('customer_token'=> $token));
                    } else {
                        $token = $event->customer_token;
                    }
                } else if(get_user_id() == $event->counselor_id) {
                    if(!$event->counselor_token) {
                        $token = $opentok->generateToken($session_id);
                        $this->Event->update($event->id, array('counselor_token'=> $token));
                    } else {
                        $token = $event->counselor_token;
                    }
                }
            } catch (OpenTokException $e) {
                log_message('info', '[OpenTok] OpenTokSDK::create_session() Exception: ' . $e->getMessage());
            } catch (Exception $e) {
                log_message('info', '[OpenTok] OpenTokSDK::create_session() Exception: ' . $e->getMessage());
            }

            $this->data['session_id'] = $session_id;
            $this->data['token'] = $token;
            $this->load->view('opentokv2', $this->data);
        }
    }
}