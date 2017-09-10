<?php

class User_Controller extends MY_Controller
{

    var $data;
    var $user_id;
    var $user;

    public function validate_user($redirect = TRUE)
    {

        if (!intval($this->get_user_id())) {
            $this->session->set_flashdata('error', 'You must be logged in to view this page.  Please log in using the form below.');

            if ($redirect) {
                /** Cache the page they were trying to go to **/
                $this->session->set_userdata('pre_login_target', $this->uri->uri_string());
                redirect($this->config->item('signin_url'));
            }
            return FALSE;
        }
        return TRUE;
    }

    public function get_user_id()
    {
        if (!$this->user_id) {
            $this->user_id = $this->session->userdata('user_id');
        }
        return $this->user_id;
    }

    public function init_session($user)
    {
        $this->session->set_userdata('user_id', $user->id);
    }


}

?>