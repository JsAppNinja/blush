<?php if (!defined('BASEPATH')) exit('No direct script access allowed');

class My_Account extends User_Controller
{
    public function _remap($method, $params)
    {
        $this->load_view($method, $params);
    }

    public function load_view($method, $params)
    {
        $this->validate_user();
        /* Setup the page */
        $this->data['page_title'] = 'My Account';
        $this->data['page_active_my_account'] = TRUE;
        $this->data['activeRouter'] = 'myAccount';

        $this->output->set_header("Cache-Control: private, no-store, no-cache, must-revalidate, max-age=0");
        $this->output->set_header("Pragma: no-cache");

        $this->load->view('includes/header', $this->data);
        $this->load->view('includes/page-title', $this->data);
        $this->load->view('page', $this->data);
        $this->load->view('includes/footer', $this->data);
    }
}

?>