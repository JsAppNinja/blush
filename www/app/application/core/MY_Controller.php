<?php

class MY_Controller extends CI_Controller
{

    var $data;
    var $user_id;
    var $user;

    public function __construct()
    {
        parent::__construct();
        $this->data['flash_success'] = $this->session->flashdata('success');
        $this->data['flash_info'] = $this->session->flashdata('info');
        $this->data['flash_error'] = $this->session->flashdata('error');
    }

    /** OVERRIDE THESE **/
    protected function decorate_objects($objects)
    {
        return $objects;
    }

    protected function validate()
    {
        $this->load->library('form_validation');
    }
}

?>