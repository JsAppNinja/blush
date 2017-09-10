<?php if (!defined('BASEPATH')) exit('No direct script access allowed');

class Main extends User_Controller
{

    public function __construct()
    {
        parent::__construct();
    }

    public function index() {


        if ($this->get_user_id()) {
            redirect($this->config->item('site_home'));
        } else {
            redirect($this->config->item('signin_url'));
        }
    }

}