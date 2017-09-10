<?php if (!defined('BASEPATH')) exit('No direct script access allowed');

class Main extends Admin_Controller
{

    public function __construct()
    {
        parent::__construct();
    }

    public function _remap($method, $params) {
        $this->load_view($method, $params);
    }

    public function load_view() {
        $this->validate_user();
        /* Setup the page */
        $this->data['page_title'] = 'Dashboard';
        $this->data['activeRouter'] = 'admin';

        $this->load->view('includes/admin-header', $this->data);
        $this->load->view('includes/admin-footer', $this->data);
    }

}