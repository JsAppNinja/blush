<?php
class Transaction extends MY_Model
{

    function get_scope()
    {
        return "transaction";
    }

    function column_map($col)
    {
        $column_map = array('transaction_nbr', 'created', 'requestor_id', 'custodian_id', 'amount');
        return $column_map[intval($col)];
    }

    function add_data() {
        $this->load->library('uuid');
        $data = array(
            'uuid' => $this->uuid->v4(),
            'transaction_nbr' => strtoupper(random_string('alnum', 10)),
            'created' => timestamp_to_mysqldatetime(now()),
            'deleted' => 0
        );

        $counselor = get_counselor();
        if($counselor) {
            $data['counselor_id'] = $this->User->get_id($counselor->uuid);
        }
        return $data;
    }

    function get_by_user($user_id) {
        $this->db->where('customer_id',$user_id);
        $this->db->or_where('counselor_id',$user_id);
        $query = $this->db->get($this->get_scope());
        return $query->result();
    }

    function unpaid_for_custodian($custodian_id) {
        $sql = "select count(t.id) as cnt, sum(t.amount) as amt from ".$this->get_scope()
          ." t where t.custodian_id = ? and t.paid = 0";
        $query = $this->db->query($sql, $custodian_id);
        return $query->row();
    }

    function mark_paid_custodian($custodian_id) {
        $this->db->where(array('custodian_id'=>$custodian_id, 'paid'=>0));
        $this->db->update($this->get_scope(), array('paid'=> 1, 'date_paid'=>timestamp_to_mysqldatetime(now())));
    }

    function get_count_last_30() {
        $sql = 'select count(t.id) as cnt ';

        $where = 'WHERE t.deleted = 0 AND t.created > ? ';
        $from = ' from ' . $this->get_scope() . ' t';
        $query_params = array(timestamp_to_mysqldatetime(add_day(-31)));

        $sql .= ' ' . $from . ' ' . $where;

        $query = $this->db->query($sql, $query_params);
        //echo $this->db->last_query();
        $row = $query->row();
        return $row->cnt;
    }

    function get_amount_last_30() {
        $sql = 'select sum(t.amount) as amt ';

        $where = 'WHERE t.deleted = 0 AND t.created > ? ';
        $from = ' from ' . $this->get_scope() . ' t';
        $query_params = array(timestamp_to_mysqldatetime(add_day(-31)));

        $sql .= ' ' . $from . ' ' . $where;

        $query = $this->db->query($sql, $query_params);
        //echo $this->db->last_query();
        $row = $query->row();
        return $row->amt;
    }

    function get_count($filter = '', $user_id = 0, $custodian_id = 0)
    {
        $sql = 'select count(t.id) as cnt ';

        $start_date = $this->input->post('start_date', TRUE);
        $end_date = $this->input->post('end_date', TRUE);
        $unpaid = $this->input->post('unpaid', TRUE);

        $query_params = array();
        $where = 'WHERE deleted = 0 ';
        $from = ' from ' . $this->get_scope() . ' t';
        if ($filter) {
            $where .= ' AND t.transaction_nbr LIKE ?';
            array_unshift($query_params, $filter . '%');
        }

        if ($unpaid > 0) {
            $where .= " and t.paid = 0 ";
        }

        if ($user_id > 0) {
            $where .= " and t.user_id = ? ";
            $query_params[] = $user_id;
        }

        if ($custodian_id > 0) {
            $where .= " and t.custodian_id = ? ";
            $query_params[] = $custodian_id;
        }

        if ($start_date) {
            $where .= " and t.created >= ? ";
            $query_params[] = timestamp_to_mysqldate(strtotime($start_date));
        }
        if ($end_date) {
            $where .= " and t.created < ? ";
            $query_params[] = timestamp_to_mysqldate(strtotime($end_date));
        }

        $sql .= ' ' . $from . ' ' . $where;

        $query = $this->db->query($sql, $query_params);
        $row = $query->row();
        return $row->cnt;
    }

    function get_list($limit = 999, $offset = 0, $ordering = '', $filter = '', $user_id = 0, $custodian_id = 0)
    {
        if (!$ordering) {
            $ordering = array('sort'=> 'created', 'dir'=> 'ASC');
        } else {
            $ordering['sort'] = $this->column_map($ordering['sort']);
        }

        $start_date = $this->input->post('start_date', TRUE);
        $end_date = $this->input->post('end_date', TRUE);
        $unpaid = $this->input->post('unpaid', TRUE);

        $query_params = array();

        $sql = "SELECT t.* ";

        $where = ' WHERE deleted = 0 ';
        $from = ' from ' . $this->get_scope() . ' t';
        if ($filter) {
            $where .= ' AND t.transaction_nbr LIKE ?';
            array_unshift($query_params, $filter . '%');
        }

        if ($unpaid > 0) {
            $where .= " and t.paid = 0 ";
        }

        if ($user_id > 0) {
            $where .= " and t.user_id = ? ";
            $query_params[] = $user_id;
        }

        if ($custodian_id > 0) {
            $where .= " and t.custodian_id = ? ";
            $query_params[] = $custodian_id;
        }

        if ($start_date) {
            $where .= " and t.created >= ? ";
            $query_params[] = timestamp_to_mysqldate(strtotime($start_date));
        }
        if ($end_date) {
            $where .= " and t.created < ? ";
            $query_params[] = timestamp_to_mysqldate(strtotime($end_date));
        }

        $query_params[] = $offset;
        $query_params[] = $limit;

        $sql .= ' ' . $from . ' ' . $where . " ORDER BY " . $this->get_ordering($ordering) . " LIMIT ?, ? ";

        $query = $this->db->query($sql, $query_params);
        //echo $this->db->last_query();
        return $query->result();
    }
}

?>