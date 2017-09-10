<?php
class Transaction_Counselor extends MY_Model
{

    function get_scope()
    {
        return "transaction_counselor";
    }

    function get_unpaid_by_counselor($counselor_id) {
        $this->db->where(array('deleted'=>0, 'paid'=>0, 'counselor_id' => $counselor_id));
        $query = $this->db->get($this->get_scope());
        return $query->result();
    }

    function get_count($filter = '', $counselor_id = 0)
    {
        $sql = 'select count(tc.id) as cnt ';

        $query_params = array();
        $where = 'WHERE tc.deleted = 0 ';
        $from = ' from ' . $this->get_scope() . ' tc';

        if ($counselor_id > 0) {
            $where .= " and tc.counselor_id = ? ";
            $query_params[] = $counselor_id;
        }

        $sql .= ' ' . $from . ' ' . $where;

        $query = $this->db->query($sql, $query_params);
        $row = $query->row();
        return $row->cnt;
    }

    function get_list($limit = 999, $offset = 0, $ordering = '', $filter = '', $counselor_id = 0)
    {
        if (!$ordering) {
            $ordering = array('sort'=> 'created', 'dir'=> 'ASC');
        } else {
            $ordering['sort'] = $this->column_map($ordering['sort']);
        }

        $query_params = array();

        $sql = "SELECT tc.* ";

        $where = ' WHERE tc.deleted = 0 ';
        $from = ' from ' . $this->get_scope() . ' tc';

        if ($counselor_id > 0) {
            $where .= " and tc.counselor_id = ? ";
            $query_params[] = $counselor_id;
        }

        $query_params[] = $offset;
        $query_params[] = $limit;

        $sql .= ' ' . $from . ' ' . $where . " ORDER BY " . $this->get_ordering($ordering) . " LIMIT ?, ? ";

        $query = $this->db->query($sql, $query_params);
        //echo $this->db->last_query();
        return $query->result();
    }

    function add_data() {
        $this->load->library('uuid');
        $data = array(
            'uuid' => $this->uuid->v4(),
            'created' => timestamp_to_mysqldatetime(now()),
            'deleted' => 0,
            'paid' => 0
        );
        return $data;
    }

}
?>