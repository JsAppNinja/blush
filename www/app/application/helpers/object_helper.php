<?

function config_val($key = '')
{
    if ($key) {
        $CI =& get_instance();
        $CI->load->model('Configuration');
        $result = $CI->Configuration->get($key);
    }
    return $result->value;
}

function loggly($data = '')
{

    if (is_array($data) || is_object($data)) {
        $data = json_encode($data);
    }

    $CI =& get_instance();
    $url = sprintf("http://logs-01.loggly.com/inputs/%s/tag/%s/", $CI->config->item('loggly_token'), $CI->config->item('loggly_tag'));
    $curl = curl_init();
    curl_setopt($curl, CURLOPT_URL, $url);
    curl_setopt($curl, CURLOPT_POST, 1);
    curl_setopt($curl, CURLOPT_RETURNTRANSFER, true);
    curl_setopt($curl, CURLOPT_FOLLOWLOCATION, true);
    curl_setopt($curl, CURLOPT_POSTFIELDS, $data);
    $result = curl_exec($curl);
}

function dologin($username = '', $password = '')
{
    $CI =& get_instance();

    /** Do Login **/
    if (!$username) {
        $username = $CI->input->post('username', TRUE);
    }

    if (!$password) {
        $password = $CI->input->post('password', TRUE);
    }

    $user = $CI->User->login($username, $password);

    return $user;
}

function validate_permission($permission_name)
{
    $permissions = array(
        'USER_LOGIN_AS' => 2,
        'USER_SET_COMPANY' => 2,
        'COMPANY_SWITCH' => 2
    );

    $permission_type_id = $permissions[$permission_name];
    return get_user_type_id() >= $permission_type_id;
    return TRUE;
}

function get_user_type_id()
{
    $user_type_id = 0;

    $CI =& get_instance();
    $CI->load->database();
    if ($CI->session->userdata('user_type_id')) {
        return $CI->session->userdata('user_type_id');
    }

    if ($CI->session->userdata('user_id')) {
        $CI->load->model('User');
        $user_id = $CI->session->userdata('user_id');
        $user = $CI->User->load($user_id);
        if ($user) {
            $user_type_id = $user->user_type_id;
            $CI->session->set_userdata('user_type_id', $user_type_id);
        }
    }

    return $user_type_id;
}

function get_user($user_id = 0)
{
    $CI =& get_instance();
    $CI->load->database();

    if ($user_id === 0) {
        $user_id = $CI->session->userdata('user_id');
        if (!$user_id) {
            $user_id = $CI->session->userdata('admin_user_id');
        }
    }

    if ($user_id) {
        $CI->load->model('User');
        $user = $CI->User->load($user_id);
        return $user;
    }
}

function get_stripe_customer($user_id = 0)
{
    $CI =& get_instance();

    \Stripe\Stripe::setApiKey($CI->config->item('stripe_private_key'));

    $user = get_user($user_id);
    if ($user && $user->stripe_customer_id) {
        $customer = unserialize($CI->session->userdata('stripe_customer'));
        if (!$customer || $customer->id !== $user->stripe_customer_id) {
            try {
                $customer = \Stripe\Customer::retrieve($user->stripe_customer_id);
                $CI->session->set_userdata('stripe_customer', serialize($customer));
            } catch (Exception $e) {
                log_message('info', sprintf('[get_stripe_customer] Stripe_Customer::retrieve User UUID: [%d] Exception: [%s]',$user_id, $e->getMessage()));
            }
        }
        //array_print($customer);
        return $customer;
    }
}

function get_stripe_recipient($user_id = 0)
{
    $CI =& get_instance();

    // \Stripe\Stripe::setApiKey($CI->config->item('stripe_private_key'));
    \Stripe\Stripe::setApiKey('sk_live_geM6GN7Fvuy2mMNGXIbl8yQP');

    $user = get_user($user_id);
    if ($user && $user->stripe_customer_id) {
        $error = 'error';
        try {
            $recipient = \Stripe\Account::retrieve($user->stripe_customer_id);
            $CI->session->set_userdata('stripe_recipient', serialize($recipient));
        } catch (Exception $e) {
            $error = $e;
            log_message('info', '[get_stripe_recipient] Stripe_Customer::retrieve Exception: '.$e->getMessage());
            $recipient = 'nothing';
        }
        return array($recipient,$error,$user->stripe_customer_id);
    }
    return "";
}

function get_blush_data()
{
    $data = array();
    $data['credits_counseling'] = CREDITS_COUNSELING;
    $data['credits_diary'] = CREDITS_DIARY;

    $data['price_counseling'] = get_price_video();
    $data['price_diary'] = get_price_journal();
    $data['prevent_schedule_24hour'] = intval(config_val('prevent_schedule_24hour'));

    return json_encode($data);
}

function get_price_journal()
{
    $user = get_user();
    if (isset($user) && isset($user->stripe_subscription_id)) {
        return get_option('blush_journal_discount');
    } else {
        return get_option('blush_journal');
    }
}

function get_price_video()
{
    $user = get_user();
    if (isset($user) && isset($user->stripe_subscription_id)) {
        return get_option('blush_video_discount');
    } else {
        return get_option('blush_video');
    }
}

function get_user_json($user_id = 0)
{
    $user = get_user($user_id);
    if (!$user) {
        return '{}';
    } else {
        return json_encode(decorate_user($user, true));
    }
}

function get_counselor($user_id = 0)
{
    $CI =& get_instance();
    $CI->load->database();

    if ($user_id === 0) {
        $user_id = $CI->session->userdata('user_id');
    }

    if ($user_id > 0) {
        $sql = "SELECT u.* from user u, user_counselor uc where u.id = uc.counselor_id and uc.user_id = ?";
        $query = $CI->db->query($sql, $user_id);
        $counselor = $query->row();
        if($counselor) {
            $counselor = decorate_user($counselor, true);
            unset($counselor->plan, $counselor->plan_id, $counselor->stripe_customer, $counselor->stripe_customer_id, $counselor->zipcode,
            $counselor->phone, $counselor->email, $counselor->address, $counselor->birthday, $counselor->city,
            $counselor->counselor, $counselor->credits, $counselor->mobile_phone, $counselor->parent_email,
            $counselor->email_diary, $counselor->email_general, $counselor->email_message, $counselor->email_purchase,
            $counselor->email_reminder, $counselor->previous_login, $counselor->state, $counselor->stripe_card, $counselor->username);
        }

        return $counselor;
    }
}

function get_user_name($user_id = 0)
{

    $user = get_user($user_id);
    if ($user) {
        return $user->firstname." ".$user->lastname;
    }
}

function get_user_id()
{
    $CI =& get_instance();
    $CI->load->database();

    return $CI->session->userdata('user_id');
}

function get_user_email()
{
    $CI =& get_instance();
    $CI->load->database();
    if ($CI->session->userdata('user_id')) {
        $CI->load->model('User');
        $user_id = $CI->session->userdata('user_id');
        $user = $CI->User->load($user_id);
        return $user->email;
    }
}

function object_list($table_name, $sort_col = 'id', $sort_order = 'ASC')
{
    $CI =& get_instance();
    $CI->load->database();

    $options = array();
    $CI->db->order_by($sort_col, $sort_order);
    $query = $CI->db->get($table_name);

    $results = array();
    foreach ($query->result() as $result) {
        if (isset($result->enabled) && !$result->enabled)
            continue;

        $results[] = $result;
    }

    return $results;
}

function table_lookup($table_name, $id)
{
    if (intval($id)) {
        $CI =& get_instance();
        $CI->load->database();

        $CI->db->where('id', $id);
        $query = $CI->db->get($table_name);
        $result = $query->row();
        if ($result) {

            if (isset($result->enabled) && !$result->enabled)
                return NULL;

            return $result->name;
        }
    }
}

function table_lookup_reverse($table_name, $name)
{
    if ($name) {
        $CI =& get_instance();
        $CI->load->database();

        $CI->db->where('name', $name);
        $query = $CI->db->get($table_name);
        $result = $query->row();
        if ($result) {

            if (isset($result->enabled) && !$result->enabled)
                return NULL;

            return $result->id;
        }
    }
}

function convert_field($value, $datatype = '')
{
    if ($datatype == 'phone') {
        $value = phone_format($value);
    } else if ($datatype == 'date') {
        $value = mysql_date($value);
    } else if ($datatype == 'time') {
        $value = mysql_time($value);
    } else if ($datatype == 'int') {
        $value = intval($value);
    } else if ($datatype == 'money') {
        $value = doubleval(preg_replace("/[^0-9,.]/", "", $value));
    }

    return $value;
}

function get_option($name)
{
    $CI =& get_instance();
    $CI->load->database();

    $CI->db->where('name', $name);
    $query = $CI->db->get('option');
    $result = $query->row();
    if ($result) {
        return $result->value;
    }
}

function set_option($name, $value)
{
    if ($name) {
        $CI =& get_instance();
        $CI->load->database();

        $option = get_option($name);
        if (!$option) {
            $CI->db->query($CI->db->insert_string('option', array('name' => $name, 'value' => $value)));
        } else {
            $CI->db->where('name', $name);
            $CI->db->update('option', array('value' => $value));
        }
    }
}

?>