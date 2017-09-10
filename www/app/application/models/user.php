<?php
class User extends MY_Model
{

    protected static $fields = array(
        'firstname' => 'string',
        'lastname' => 'string',
        'username' => 'username',
        'password' => 'password',
        'user_type_id' => 'int',
        'email' => 'email',
        'phone' => 'phone',
        'mobile_phone' => 'phone',
        'gender' => 'string',
        'birthday' => 'date',
        'about' => 'string',
        'address' => 'string',
        'country' => 'string',
        'state' => 'string',
        'city' => 'string',
        'zipcode' => 'string',
        'timezone' => 'string',
        'email_message' => 'int',
        'email_diary' => 'int',
        'email_reminder' => 'int',
        'email_purchase' => 'int',
        'email_general' => 'int'
    );

    function get_scope()
    {
        return "user";
    }

    function column_map($col)
    {
        $column_map = array('lastname', 'firstname', 'username', 'created', 'last_login', 'plan_id', 'trial_end');
        return $column_map[intval($col)];
    }

    function load_by_username($username = '', $include_deleted = FALSE)
    {
        $this->db->where("username", $username);
        if (!$include_deleted) {
            $this->db->where('deleted', 0);
        }
        $query = $this->db->get($this->get_scope());
        return $this->after_load($query->row());
    }

    function load_by_customer_id($customer_id = '')
    {
        $this->db->where("stripe_customer_id", $customer_id);
        $query = $this->db->get($this->get_scope());
        return $this->after_load($query->row());
    }

    function load_by_confirmation_key($confirmation_key = '')
    {
        $this->db->where("confirmation_key", $confirmation_key);
        $query = $this->db->get($this->get_scope());
        return $this->after_load($query->row());
    }


    function load_user_counselor($user_id, $counselor_id)
    {
        $this->db->where(array('user_id' => $user_id, 'counselor_id' => $counselor_id));
        $query = $this->db->get('user_counselor');
        return $query->row();
    }

    function get_all_customers()
    {
        $sql = "SELECT u.* from " . $this->get_scope() . " u where u.deleted = 0 and u.user_type_id = ? ORDER BY id ASC";
        $query = $this->db->query($sql, USER_TYPE_CUSTOMER);
        return $query->result();
    }

    function get_customers($counselor_id)
    {
        $sql = "SELECT u.* from " . $this->get_scope() . " u, user_counselor uc where u.deleted = 0 and uc.user_id = u.id and uc.counselor_id = ?";
        $query = $this->db->query($sql, $counselor_id);
        return $query->result();
    }

    function get_counselors()
    {
        $this->db->where(array('deleted' => 0, 'user_type_id' => USER_TYPE_COUNSELOR));
        $this->db->order_by('lastname', 'ASC');
        $query = $this->db->get($this->get_scope());
        return $query->result();
    }

    function assign_counselor($user_uuid, $counselor_id)
    {
        $user_id = $this->get_id($user_uuid);
        if ($user_id && $counselor_id) {
            $this->db->delete('user_counselor', array('user_id' => $user_id));
            $this->db->insert('user_counselor', array(
                'user_id' => $user_id,
                'counselor_id' => $counselor_id
            ));
        }
    }

    function load_counselor($user_id)
    {
        $sql = "select u.* from " . $this->get_scope() . " u, user_counselor uc where u.deleted = 0" .
            " and u.id = uc.counselor_id and uc.user_id = ?";
        $query = $this->db->query($sql, $user_id);
        return $query->row();

    }

    function delete_permanently($user_id = 0)
    {
        $this->load->model(array('Message', 'Diary', 'Conversation', 'Event', 'Registration', 'Transaction'));

        $messages = $this->Message->get_by_user($user_id);
        foreach ($messages as $message) {
            $this->Message->delete_permanently($message->id);
        }

        $diaries = $this->Diary->get_list(999, 0, '', '', $user_id);
        foreach ($diaries as $diary) {
            $this->Diary->delete_permanently($diary->id);
        }

        $conversations = $this->Conversation->get_by_user($user_id);
        foreach ($conversations as $conversation) {
            $this->Conversation->delete_permanently($conversation->id);
        }

        $events = $this->Event->get_by_user($user_id);
        foreach ($events as $event) {
            $this->Event->delete_permanently($event->id);
        }

        $transactions = $this->Transaction->get_by_user($user_id);
        foreach ($transactions as $transaction) {
            $this->Transaction->delete_permanently($transaction->id);
        }

        $registration = $this->Registration->load_by_user_id($user_id);
        if ($registration && $registration->id) {
            $this->Registration->delete_permanently($registration->id);
        }
        $this->db->delete('user_counselor', array("user_id" => $user_id));
        $this->db->delete($this->get_scope(), array("id" => $user_id));
    }

    function delete($user_id = 0)
    {
        parent::delete($user_id);
        //$this->mark_deleted('request', array("user_id" => $user_id));
    }

    function delete_bad_registration($user_id)
    {
        $this->db->delete($this->get_scope(), array("id" => $user_id));
    }

    function load_by_email($email = '', $include_deleted = FALSE)
    {
        $this->db->where("email", $email);
        if (!$include_deleted) {
            $this->db->where('deleted', 0);
        }
        $query = $this->db->get($this->get_scope());
        return $this->after_load($query->row());
    }

    function load_by_email_unwelcomed($email = '')
    {
        $this->db->where("email", $email);
        $this->db->where("welcomed", 0);
        $query = $this->db->get($this->get_scope());
        return $this->after_load($query->row());
    }

    function load($id = 0)
    {
        if (intval($id)) {
            $query = $this->db->get_where($this->get_scope(), array("id" => $id, 'deleted' => 0));
            return $this->after_load($query->row());
        }
    }

    function get_name($user_id)
    {
        $this->db->where(array("id" => $user_id));
        $this->db->select('firstname, lastname');
        $query = $this->db->get($this->get_scope());
        return $query->row();
    }

    function get_customers_by_counselor($counselor_id)
    {
        $query = "select u.* from " . $this->get_scope() . " u, user_counselor uc where uc.user_id = u.id and "
            . " uc.counselor_id = ? and u.deleted = 0 and u.user_type_id = ?";
        $query = $this->db->query($query, array($counselor_id, USER_TYPE_CUSTOMER));
        return $query->result();
    }

    function login($username, $password)
    {
        $query = $this->db->get_where($this->get_scope(), array("username" => $username, 'deleted' => 0));
        $user = $query->row();
        if ($user) {

            $password = sha1($password . $user->salt);

            if ($user->password != $password) {
                unset($user);
            }

            if (isset($user) && $user) {
                return $this->after_load($user);
            }
        }
    }

    function login_admin($username, $password)
    {
        $query = $this->db->get_where($this->get_scope(), array("username" => $username, "password" => $password, 'user_type_id' => 99, 'deleted' => 0));
        return $query->row();
    }

    function from_post()
    {

        $data = array('firstname' => trim($this->input->post('firstname', TRUE)),
            'lastname' => trim($this->input->post('lastname', TRUE)),
            'email' => trim($this->input->post('email', TRUE)),
            'phone' => phone_format(trim($this->input->post('phone', TRUE))),
            //'inactive'  => intval($this->input->post('inactive', TRUE))
            'inactive' => 0
        );
        return $data;
    }

    function create_from_registration($registration)
    {
        $this->load->library('uuid');

        $reg_data = json_decode($registration->data);

        $data = array(
            'uuid' => $this->uuid->v4(),
            'user_type_id' => USER_TYPE_CUSTOMER,
            'firstname' => $reg_data->firstname,
            'lastname' => $reg_data->lastname,
            'email' => $reg_data->email,
            'gender' => $reg_data->gender,
            'birthday' => $reg_data->birthday,
            'city' => $reg_data->city,
            'state' => $reg_data->state,
            'zipcode' => $reg_data->zipcode,
            'username' => $registration->username,
            'phone' => phone_format(trim($reg_data->mobile_phone)),
            'mobile_phone' => phone_format(trim($reg_data->mobile_phone)),
            'password' => $registration->password,
            'salt' => $registration->salt,
            'parent_email' => $reg_data->parent_email,
            'country' => $reg_data->state ? 'US' : '',
            'created' => timestamp_to_mysqldatetime(now()),
            'credits' => 0, /* They will get their credits when their cards are processed */
            'timezone' => $reg_data->timezone,
            'deleted' => 0,
            //'inactive' => ($reg_data->parent_email) ? 1 : 0,
            'inactive' => 0,
            'email_message' => 1,
            'email_diary' => 1,
            'email_reminder' => 1,
            'email_purchase' => 1,
            'email_general' => 1,
            'sms_message' => 1,
            'sms_diary' => 1,
            'sms_reminder' => 1,
            'sms_purchase' => 1,
            'sms_general' => 1
        );

        return $this->add($data);
    }

    function change_password($id = 0, $password)
    {
        if (intval($id)) {

            $salt = $this->create_salt();
            $password = sha1($password . $salt);

            /** Update Community **/
            $data = array('password' => $password, 'salt' => $salt);
            $this->db->where('id', $id);
            $this->db->update($this->get_scope(), $data);
        }
    }

    function reset_password($id = 0)
    {
        $clear_password = random_string('alnum', 8);
        $salt = $this->create_salt();
        $password = sha1($clear_password . $salt);

        /** Update Community **/
        $data = array('password' => $password, 'salt' => $salt);

        $this->db->where('id', $id);
        $this->db->update($this->get_scope(), $data);
        return $clear_password;
    }

    /**
     * Update the password and created the salt
     * @param $data - data going into the add()
     */
    public function update_add_data($data)
    {

        if (!isset($data['salt'])) {
            $salt = $this->create_salt();
            $password = sha1($data['password'] . $salt);
            $data['password'] = $password;
            $data['salt'] = $salt;
        }
        return $data;
    }

    /**
     * Update the password and created the salt
     * @param $data - data going into the add()
     */
    public function update_update_data($data)
    {
        if (isset($data['password'])) {
            $salt = $this->create_salt();
            $password = sha1($data['password'] . $salt);
            $data['password'] = $password;
            $data['salt'] = $salt;
        }
        return $data;
    }

    function add_data()
    {
        $this->load->library('uuid');

        $data = array(
            'uuid' => $this->uuid->v4(),
            'credits' => 0,
            'email_message' => 1,
            'email_diary' => 1,
            'email_reminder' => 1,
            'email_purchase' => 1,
            'email_general' => 1,
            'sms_message' => 1,
            'sms_diary' => 1,
            'sms_reminder' => 1,
            'sms_purchase' => 1,
            'sms_general' => 1,
            'created' => timestamp_to_mysqldatetime(now())
        );
        return $data;
    }

    function set_stripe_data($user_id, $customer)
    {
        $data = array('stripe_registration_data' => serialize($customer), 'stripe_customer_id' => $customer->id);
        $this->db->where('id', $user_id);
        $this->db->update($this->get_scope(), $data);
    }

    function record_login($user_id = 0)
    {
        $user = $this->User->load($user_id);
        $data = array('previous_login' => $user->last_login, 'last_login' => timestamp_to_mysqldatetime(now()));
        $this->db->where('id', $user_id);
        $this->db->update($this->get_scope(), $data);
    }

    function get_count_last_30($user_type_id = 0)
    {
        $sql = 'select count(u.id) as cnt ';

        $where = 'WHERE u.deleted = 0 AND u.created > ? ';
        $from = ' from ' . $this->get_scope() . ' u';
        $query_params = array(timestamp_to_mysqldatetime(add_day(-31)));

        if ($user_type_id > 0) {
            $where .= " AND u.user_type_id = ? ";
            $query_params[] = $user_type_id;
        }
        $sql .= ' ' . $from . ' ' . $where;

        $query = $this->db->query($sql, $query_params);
        //echo $this->db->last_query();
        $row = $query->row();
        return $row->cnt;
    }

    function get_count($filter = '', $user_type_id = 0, $counselor_id = 0)
    {
        $sql = 'select count(u.id) as cnt ';

        $where = 'WHERE u.deleted = 0';
        $from = ' from ' . $this->get_scope() . ' u';
        $query_params = array();

        if ($filter) {
            $parts = explode(' ', $filter);
            if (sizeof($parts) > 1) {
                $where .= ' AND (u.firstname like ? AND u.lastname like ?)';
                array_unshift($query_params, $parts[1] . '%');
                array_unshift($query_params, $parts[0] . '%');
            } else {
                $where .= ' AND (u.lastname like ? OR u.firstname like ?)';
                array_unshift($query_params, $filter . '%');
                array_unshift($query_params, $filter . '%');
            }
        }

        if ($user_type_id > 0) {
            $where .= " AND u.user_type_id = ? ";
            $query_params[] = $user_type_id;
        }

        if ($counselor_id > 0) {
            $from .= ", user_counselor uc ";
            $where .= " and uc.user_id = u.id and uc.counselor_id = ? ";
            $query_params[] = $counselor_id;
        } else if ($counselor_id < 0) {
            $where .= " AND u.id NOT IN (select distinct user_id from user_counselor) ";
        }

        $sql .= ' ' . $from . ' ' . $where;

        $query = $this->db->query($sql, $query_params);
        $row = $query->row();
        return $row->cnt;
    }

    function get_list($limit = 999, $offset = 0, $ordering = '', $filter = '', $user_type_id = 0, $counselor_id = 0)
    {
        if (!$ordering) {
            $ordering = array('sort' => 'lastname', 'dir' => 'ASC');
        } else {
            $ordering['sort'] = $this->column_map($ordering['sort']);
        }

        $query_params = array();

        $sql = "SELECT u.* ";

        $where = ' WHERE u.deleted = 0';
        $from = ' from ' . $this->get_scope() . ' u';
        if ($filter) {
            $parts = explode(' ', $filter);
            if (sizeof($parts) > 1) {
                $where .= ' AND (u.firstname like ? AND u.lastname like ?)';
                array_unshift($query_params, $parts[1] . '%');
                array_unshift($query_params, $parts[0] . '%');
            } else {
                $where .= ' AND (u.lastname like ? OR u.firstname like ?)';
                array_unshift($query_params, $filter . '%');
                array_unshift($query_params, $filter . '%');
            }
        }

        if ($user_type_id > 0) {
            $where .= " and u.user_type_id = ? ";
            $query_params[] = $user_type_id;
        }

        if ($counselor_id > 0) {
            $from .= ", user_counselor uc ";
            $where .= " and uc.user_id = u.id and uc.counselor_id = ? ";
            $query_params[] = $counselor_id;
        } else if ($counselor_id < 0) {
            $where .= " AND u.id NOT IN (select distinct user_id from user_counselor) ";
        }

        $query_params[] = $offset;
        $query_params[] = $limit;

        $sql .= ' ' . $from . ' ' . $where . " ORDER BY " . $this->get_ordering($ordering) . " LIMIT ?, ? ";

        $query = $this->db->query($sql, $query_params);
        //echo $this->db->last_query();
        return $query->result();
    }

    function get_payable_count($filter = '')
    {
        $sql = 'select count(u.id) as cnt ';

        $where = 'WHERE u.deleted = 0 and tc.counselor_id = u.id and tc.paid = 0 and tc.deleted = 0';
        $from = ' from ' . $this->get_scope() . ' u, transaction_counselor tc';
        $query_params = array();

        if ($filter) {
            $parts = explode(' ', $filter);
            if (sizeof($parts) > 1) {
                $where .= ' AND (u.firstname like ? AND u.lastname like ?)';
                array_unshift($query_params, $parts[1] . '%');
                array_unshift($query_params, $parts[0] . '%');
            } else {
                $where .= ' AND (u.lastname like ? OR u.firstname like ?)';
                array_unshift($query_params, $filter . '%');
                array_unshift($query_params, $filter . '%');
            }
        }

        $sql .= ' ' . $from . ' ' . $where;

        $query = $this->db->query($sql, $query_params);
        $row = $query->row();
        return $row->cnt;
    }

    function get_payable_list($limit = 999, $offset = 0, $ordering = '', $filter = '')
    {
        if (!$ordering) {
            $ordering = array('sort' => 'lastname', 'dir' => 'ASC');
        } else {
            $ordering['sort'] = $this->column_map($ordering['sort']);
        }

        $query_params = array();

        $sql = "SELECT u.*, sum(tc.amount) as amount ";

        $where = ' WHERE u.deleted = 0 and tc.counselor_id = u.id and tc.paid = 0 and tc.deleted = 0';
        $from = ' from ' . $this->get_scope() . ' u, transaction_counselor tc';
        if ($filter) {
            $parts = explode(' ', $filter);
            if (sizeof($parts) > 1) {
                $where .= ' AND (u.firstname like ? AND u.lastname like ?)';
                array_unshift($query_params, $parts[1] . '%');
                array_unshift($query_params, $parts[0] . '%');
            } else {
                $where .= ' AND (u.lastname like ? OR u.firstname like ?)';
                array_unshift($query_params, $filter . '%');
                array_unshift($query_params, $filter . '%');
            }
        }

        $query_params[] = $offset;
        $query_params[] = $limit;

        $sql .= ' ' . $from . ' ' . $where . " GROUP BY u.id ORDER BY " . $this->get_ordering($ordering) . " LIMIT ?, ? ";

        $query = $this->db->query($sql, $query_params);
        //echo $this->db->last_query();
        return $query->result();
    }

    function load_payable($uuid)
    {
        $sql = "select u.*, sum(tc.amount) as amount, count(tc.id) as cnt from " . $this->get_scope() . " u, transaction_counselor tc "
            . " where u.id = tc.counselor_id and tc.deleted = 0 and tc.paid = 0 and u.deleted = 0 and u.uuid = ? group by u.id";
        $query = $this->db->query($sql, $uuid);
        return $query->row();
    }

    function edit_from_get($id)
    {
        $data = array('firstname' => trim($this->input->get('firstname', TRUE)), 'lastname' => trim($this->input->get('lastname', TRUE)), 'email' => trim($this->input->get('email', TRUE)), 'phone' => trim($this->input->get('phone', TRUE)), 'notify_deal' => intval($this->input->get('notify_deal')), 'notify_task' => intval($this->input->get('notify_task')), 'notify_comment' => intval($this->input->get('notify_comment')), 'notify_file' => intval($this->input->get('notify_file')), 'notify_join' => intval($this->input->get('notify_new_user')),);
        $this->db->where(array('id' => $id));
        $this->db->update($this->get_scope(), $data);
    }

}

?>