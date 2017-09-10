<?php if (!defined('BASEPATH')) exit('No direct script access allowed');

class Accounts extends User_Controller
{

    public function __construct()
    {
        parent::__construct();
    }

    public function registration() {
        /* Setup the page */
        $this->data['page_title'] = 'Get Started - Make Us Blush';
        $this->data['activeRouter'] = 'registration';

        $this->load->view('includes/header', $this->data);
        $this->load->view('registration', $this->data);
        $this->load->view('includes/footer', $this->data);

    }

    public function confirmation($confirmation_link) {
        $user = $this->User->load_by_confirmation_key($confirmation_link);
        if($user) {
            $this->User->update($user->id, array('inactive'=>0));
            $this->session->set_flashdata('success', 'Your child\'s account has been approved.  They can login at any time now.');
        }
        redirect($this->config->item('signin_url'));
    }

    public function resend_confirmation($uuid) {
        $user = $this->User->load_by_uuid($uuid);
        if($user) {
            $confirmation_key = random_string('unique');
            $this->User->update($user->id, array('confirmation_key'=>$confirmation_key));

            $this->load->helper('notification');
            notify_parent_approval($user->id, $confirmation_key);
            $this->session->set_flashdata('success', 'The approval email has been resent to your parent.  Once we have received approval from your parents, your account will become active.');
        }
        redirect($this->config->item('signin_url'));
    }

    public function login()
    {
        $this->load->library('form_validation');

        $this->form_validation->set_rules('username', 'Username', 'trim|required|xss_clean');
        $this->form_validation->set_rules('password', 'Password', 'trim|required|xss_clean');

        $url = $this->session->userdata('pre_login_target');
        if (!$url) {
            $url = $this->config->item('site_home');
        }

        if ($this->session->userdata('user_id')) {
            redirect($url);
        }

        if ($this->form_validation->run() != FALSE) {
            $user = dologin();

            if ($user && $user->id) {
                if ($user->inactive > 0) {
                    $this->session->set_flashdata('error', 'Your account has been suspended.  Please contact us if you have any questions regarding your account.');
                    redirect($this->config->item('signin_url'));
                }

                $this->User->record_login($user->id);
                $this->session->unset_userdata('pre_login_target');

                if($user->user_type_id == USER_TYPE_ADMIN) {
                    log_message('info', 'Admin Login - User ID: ' . $user->id . ', Username: ' . $user->username . ', Pre Login Target: ' . $url);
                    $this->session->set_userdata('admin_user_id', $user->id);
                    redirect($this->config->item('admin_home'));
                } else {
                    log_message('info', 'Login - User ID: ' . $user->id . ', Username: ' . $user->username . ', Pre Login Target: ' . $url);
                    $this->session->set_userdata('user_id', $user->id);
                    redirect($url);
                }
            } else {
                $this->session->set_flashdata('error', 'The username/password you have entered are invalid.  Please try again.');
                redirect($this->config->item('signin_url'));
            }
        }
        redirect($this->config->item('signin_url'));
    }

    public function logout(){
        $this->session->userdata = array();
        $this->session->sess_destroy();

        redirect($this->config->item('signin_url'));
    }

    public function query_username()
    {
        header("Cache-Control: no-store, no-cache, must-revalidate, max-age=0");
        header("Cache-Control: post-check=0, pre-check=0", false);
        header("Pragma: no-cache");
        $username = $this->input->get('username', TRUE);
        $user = $this->User->load_by_username($username, TRUE);

        $user_id = get_user_id();
        if($this->input->get('uuid')) {
            $tmp_user = $this->User->load_by_uuid($this->input->get('uuid'));
            $user_id = $tmp_user->id;
        }

        if ($user && ($user->id != $user_id)) {
            header('Content-Type: application/json');
            echo json_encode("The username you are attempting to use is currently in use, please choose another");
        } else {
            echo "true";
        }
    }

    public function query_email()
    {
        $email = $this->input->get('email', TRUE);
        $user = $this->User->load_by_email($email);

        $user_id = get_user_id();
        if($this->input->get('uuid')) {
            $tmp_user = $this->User->load_by_uuid($this->input->get('uuid'));
            $user_id = $tmp_user->id;
        }

        if ($user && ($user->id != $user_id)) {
            echo json_encode("The email address you are attempting to use is currently in use, please choose another");
        } else {
            echo "true";
        }
    }
}

?>