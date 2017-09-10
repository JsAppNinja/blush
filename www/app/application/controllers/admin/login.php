<?php if (!defined('BASEPATH')) exit('No direct script access allowed');

class Login extends Admin_Controller
{

    public function __construct()
    {
        parent::__construct();
    }

    public function user($uuid = '') {
        $this->validate_user();

        $user = $this->User->load_by_uuid($uuid);
        if($user) {
            $this->session->set_userdata('user_id', $user->id);
            redirect($this->config->item('site_home'));
        }
        redirect($this->config->item('admin_home'));
    }

}