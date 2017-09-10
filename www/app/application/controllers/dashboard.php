<?php if (!defined('BASEPATH')) exit('No direct script access allowed');

class Dashboard extends User_Controller
{
    public function _remap($method, $params) {
        $this->load_view($method, $params);
    }

    public function load_view($method, $params)
    {
        $this->validate_user();
        /* Setup the page */
        $this->data['page_title'] = 'Dashboard';
        $this->data['page_active_dashboard'] = TRUE;

        /* Load Backbone App and Controllers -- CUSTOMER*/

        if(get_user() && get_user()->user_type_id==USER_TYPE_CUSTOMER) {
            $this->data['templates'] = array(
                'dashboard-dashboard' => APPPATH.'views/partials/dashboard/dashboard.php',
                'dashboard-messages' => APPPATH.'views/partials/dashboard/messages.php',
                'dashboard-diaries' => APPPATH.'views/partials/dashboard/diaries.php',
                'dashboard-videoadd' => APPPATH.'views/partials/dashboard/videoadd.php'
            );
            $this->data['activeRouter'] = 'dashboardCustomer';
        } else {
            $this->data['templates'] = array(
                'dashboard-dashboard' => APPPATH.'views/partials/dashboard/dashboard.php',
                'dashboard-calendar' => APPPATH.'views/partials/dashboard/calendar.php',
                'dashboard-conversations' => APPPATH.'views/partials/dashboard/conversations.php',
                'dashboard-messages' => APPPATH.'views/partials/dashboard/messages.php',
                'dashboard-customers' => APPPATH.'views/partials/dashboard/customers.php'
            );
            $this->data['activeRouter'] = 'dashboardCounselor';
        }

        $this->output->set_header("Cache-Control: private, no-store, no-cache, must-revalidate, max-age=0");
        $this->output->set_header("Pragma: no-cache");

        $this->load->view('includes/header', $this->data);
        $this->load->view('includes/page-title', $this->data);
        $this->load->view('page', $this->data);
        $this->load->view('includes/footer', $this->data);
    }
}

?>