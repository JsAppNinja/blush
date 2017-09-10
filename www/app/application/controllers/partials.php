<?php if (!defined('BASEPATH')) exit('No direct script access allowed');

class Partials extends CI_Controller
{

    private $_no_cache = array(
        'dashboard/dashboard',
        'dashboard/credits',
        'dashboard/message',
        'my_account/profile',
        'my_account/payment'
    );

    /** @var array
     * The list of views that we concatenate into our partials javascript file
     * */
    private $_views = array(
        'registration/step1',
        'registration/step2',
        'registration/step3',
        'registration/step4',
        'registration/step5',
        'registration/plan',
        'dashboard/dashboard',
        'dashboard/calendar',
        'dashboard/conversations',
        'dashboard/credits',
        'dashboard/customer',
        'dashboard/customers',
        'dashboard/diaries',
        'dashboard/diaries-counselor',
        'dashboard/diary',
        'dashboard/events',
        'dashboard/event',
        'dashboard/message',
        'dashboard/messages',
        'dashboard/notes',
        'dashboard/includes/videoadd-availability',
        'my_account/includes/availability-calendar-calendar',
        'my_account/my_account',
        'my_account/profile',
        'my_account/payment',
        'my_account/notifications',
        'my_account/password',
        'my_account/account_type',
        'my_account/availability',
        'admin/admin',
        'admin/dashboard',
        'admin/datatable',
        'admin/customer',
        'admin/counselor',
        'admin/transaction',
        'admin/datatable-customers',
        'admin/payable',
        'admin/payout',
        'admin/pricing',
        'admin/notification',
        'admin/user/counselor-customers'
    );

    public function _remap($method, $params)
    {

        if ($method == 'all') {
            return $this->load_all_script();
        } else {
            $path = $method;
            if ($params) {
                $path .= "/" . implode("/", $params);
            }
            $this->load_view($path);
        }
    }

    public function load_view($path)
    {
        //if (!in_array($path, $this->_no_cache)) {
            //$this->output->cache(1440); /* Cache for a day */
        //}
        $this->load->view("partials/" . $path);
    }

    private function load_all_script()
    {
        $this->output->set_content_type('application/javascript');
        foreach ($this->_views as $view) {
            $this->output->append_output('app.template_cache.set("' . $view . '", "');
            $data = $this->load->view('partials/' . $view, '', true);
            $data = trim(preg_replace('/\s\s+/', ' ', $data));
            $data = addslashes(preg_replace('~>\s*\n\s*<~', '><', $data));
            $this->output->append_output($data . '");');
        }
    }
}

?>