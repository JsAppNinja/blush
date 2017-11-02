<?php
class Payout extends MY_Model
{

    function get_scope()
    {
        return "payout";
    }

    function column_map($col)
    {
        $column_map = array('created', 'u.lastname', 'u.firstname', 'stripe_transfer_id', 'created');
        return $column_map[intval($col)];
    }

    function add($transfer, $counselor_id = 0)
    {
        $this->load->library('uuid');

        $amount = $transfer['amount'];

        $data = array(
            'creator_id' => 42,//intval(get_user_id()),
            'counselor_id' => $counselor_id,
            'amount' => number_format($amount / 100, 2),
            'uuid' => $this->uuid->v4(),
            'created' => timestamp_to_mysqldatetime(now()),
            'stripe_transfer_id' => $transfer['id'],
            'stripe_data' => serialize($transfer)
        );

        $query = $this->db->query($this->db->insert_string($this->get_scope(), $data));
        $id = $this->db->insert_id();
        $this->after_add($id, $counselor_id);
        return $id;
    }

    function after_add($id, $counselor_id)
    {
        /* Insert transaction_payout rows */
        $sql = "select tc.id from transaction_counselor tc where tc.counselor_id = ? and tc.paid = 0";
        $query = $this->db->query($sql, $counselor_id);
        foreach ($query->result() as $row) {
            $this->db->query($this->db->insert_string('payout_transaction_counselor', array('payout_id' => $id, 'transaction_counselor_id' => $row->id)));

            $this->db->where('id', $row->id);
            $this->db->update('transaction_counselor', array('paid' => 1, 'date_paid' => timestamp_to_mysqldatetime(now())));
        }
    }

    function get_count($filter = '', $user_id = 0, $counselor_id = 0)
    {
        $sql = 'select count(p.id) as cnt ';

        $start_date = $this->input->post('start_date', TRUE);
        $end_date = $this->input->post('end_date', TRUE);

        $query_params = array();
        $where = 'WHERE deleted = 0 ';
        $from = ' from ' . $this->get_scope() . ' p';
        if ($filter) {
            $where .= ' AND p.stripe_transfer_id LIKE ?';
            array_unshift($query_params, $filter . '%');
        }

        if ($user_id > 0) {
            $where .= " and p.creator_id = ? ";
            $query_params[] = $user_id;
        }

        if ($counselor_id > 0) {
            $where .= " and p.counselor_id = ? ";
            $query_params[] = $counselor_id;
        }

        if ($start_date) {
            $where .= " and p.created >= ? ";
            $query_params[] = timestamp_to_mysqldate(strtotime($start_date));
        }
        if ($end_date) {
            $where .= " and p.created < ? ";
            $query_params[] = timestamp_to_mysqldate(strtotime($end_date));
        }

        $sql .= ' ' . $from . ' ' . $where;

        $query = $this->db->query($sql, $query_params);
        $row = $query->row();
        return $row->cnt;
    }

    function get_list($limit = 999, $offset = 0, $ordering = '', $filter = '', $user_id = 0, $counselor_id = 0)
    {
        if (!$ordering) {
            $ordering = array('sort' => 'created', 'dir' => 'ASC');
        } else {
            $ordering['sort'] = $this->column_map($ordering['sort']);
        }

        $start_date = $this->input->post('start_date', TRUE);
        $end_date = $this->input->post('end_date', TRUE);

        $query_params = array();

        $sql = "SELECT p.*, u.firstname, u.lastname ";

        $where = ' WHERE p.deleted = 0 and u.id = p.counselor_id ';
        $from = ' from ' . $this->get_scope() . ' p, user u';
        if ($filter) {
            $where .= ' AND p.stripe_transfer_id LIKE ?';
            array_unshift($query_params, $filter . '%');
        }

        if ($user_id > 0) {
            $where .= " and p.user_id = ? ";
            $query_params[] = $user_id;
        }

        if ($counselor_id > 0) {
            $where .= " and p.counselor_id = ? ";
            $query_params[] = $counselor_id;
        }

        if ($start_date) {
            $where .= " and p.created >= ? ";
            $query_params[] = timestamp_to_mysqldate(strtotime($start_date));
        }
        if ($end_date) {
            $where .= " and p.created < ? ";
            $query_params[] = timestamp_to_mysqldate(strtotime($end_date));
        }

        $query_params[] = $offset;
        $query_params[] = $limit;

        $sql .= ' ' . $from . ' ' . $where . " ORDER BY " . $this->get_ordering($ordering) . " LIMIT ?, ? ";

        $query = $this->db->query($sql, $query_params);
        //echo $this->db->last_query();
        return $query->result();
    }

    function get_counselor_count($filter = '')
    {
        $sql = 'select count(c.id) as cnt ';

        $where = 'WHERE c.deleted = 0 and c.id in (select distinct(counselor_id) from transaction where paid = 0) ';
        $from = ' from counselor c';
        $query_params = array();

        if ($filter) {
            $where .= ' AND (c.name like ? OR c.summary like ?)';
            array_unshift($query_params, $filter . '%');
            array_unshift($query_params, $filter . '%');
        }

        $sql .= ' ' . $from . ' ' . $where;

        $query = $this->db->query($sql, $query_params);
        $row = $query->row();
        return $row->cnt;
    }

    function get_counselor_list($limit = 999, $offset = 0, $ordering = '', $filter = '')
    {
        $limit = 999;
        if (!$ordering) {
            $ordering = array('sort' => 'name', 'dir' => 'ASC');
        } else {
            $ordering['sort'] = $this->column_map($ordering['sort']);
        }

        $query_params = array();

        $sql = "SELECT c.id, c.name as counselor, sum(t.amount) as amount ";

        $from = ' from counselor c, transaction t';
        $where = ' WHERE c.deleted = 0 and c.id in (select distinct(counselor_id) from transaction where paid = 0) '
            . ' and t.counselor_id = c.id';
        if ($filter) {
            $where .= ' AND (c.name like ? OR c.summary like ?)';
            array_unshift($query_params, $filter . '%');
            array_unshift($query_params, $filter . '%');
        }

        $query_params[] = $offset;
        $query_params[] = $limit;

        $sql .= ' ' . $from . ' ' . $where . " GROUP BY c.id ORDER BY " . $this->get_ordering($ordering) . " LIMIT ?, ? ";

        $query = $this->db->query($sql, $query_params);
        //echo $this->db->last_query();
        return $query->result();
    }

    function get_payout_transaction_counselor_count($filter = '', $payout_id = 0)
    {
        $sql = 'select count(ptc.id) as cnt ';

        $where = 'WHERE tc.deleted = 0 and tc.id = ptc.transaction_counselor_id ';
        $from = ' from payout_transaction_counselor ptc, transaction_counselor tc';
        $query_params = array();

        if ($filter) {
            $where .= ' AND (tc.amount like ?)';
            array_unshift($query_params, $filter . '%');
        }
        if ($payout_id) {
            $where .= " and ptc.payout_id = ? ";
            $query_params[] = $payout_id;
        }

        $sql .= ' ' . $from . ' ' . $where;

        $query = $this->db->query($sql, $query_params);
        $row = $query->row();
        return $row->cnt;
    }

    function get_payout_transaction_counselor_list($limit = 999, $offset = 0, $ordering = '', $filter = '', $payout_id = 0)
    {
        $limit = 999;
        if (!$ordering) {
            $ordering = array('sort' => 'tc.id', 'dir' => 'ASC');
        } else {
            $ordering['sort'] = $this->column_map($ordering['sort']);
        }

        $query_params = array();

        $sql = "SELECT tc.* ";

        $from = ' from payout_transaction_counselor ptc, transaction_counselor tc';
        $where = ' WHERE tc.deleted = 0 and tc.id = ptc.transaction_counselor_id';

        if ($filter) {
            $where .= ' AND (tc.amount like ?)';
            array_unshift($query_params, $filter . '%');
        }
        if ($payout_id) {
            $where .= " and ptc.payout_id = ? ";
            $query_params[] = $payout_id;
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