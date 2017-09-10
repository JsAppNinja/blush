<?php
class Conversation extends MY_Model
{

    protected static $fields = array();

    function get_scope()
    {
        return "conversation";
    }

    function add_data() {
        $this->load->library('uuid');
        $data = array(
            'uuid' => $this->uuid->v4(),
            'read' => 0,
            'created' => timestamp_to_mysqldatetime(now()),
            'deleted' => 0
        );
        return $data;
    }

    function get_by_user($user_id) {
        $this->db->where('customer_id',$user_id);
        $this->db->or_where('counselor_id',$user_id);
        $query = $this->db->get($this->get_scope());
        return $this->after_load_all($query->result());
    }

    function load_by_customer_counselor($customer_id=0, $counselor_id=0)
    {
        $query = $this->db->get_where($this->get_scope(), array("customer_id" => $customer_id, "counselor_id" => $counselor_id));
        return $this->after_load($query->row());
    }

    function get_first($user_id=0) {
        if($user_id) {
            $conversations = $this->get_list(1,0, '', '', $user_id);
            if(sizeof($conversations)>0)
                return $conversations[0];
        }
    }

    function get_list($limit = 999, $offset = 0, $ordering = '', $filter = '', $counselor_id = 0)
    {
        if (!$ordering) {
            $ordering = array('sort' => 'modified', 'dir' => 'DESC');
        } else {
            $ordering['sort'] = $this->column_map($ordering['sort']);
        }

        $query_params = array();

        $sql = "SELECT c.* ";

        $where = ' WHERE c.deleted = 0';
        $from = ' from ' . $this->get_scope() . ' c';
        if ($filter) {
            $where .= ' AND c.title like ?';
            array_unshift($query_params, $filter . '%');
        }

        if ($counselor_id > 0) {
            $where .= " and c.counselor_id = ? ";
            $query_params[] = $counselor_id;
        }

        $query_params[] = $offset;
        $query_params[] = $limit;

        $sql .= ' ' . $from . ' ' . $where . " ORDER BY " . $this->get_ordering($ordering) . " LIMIT ?, ? ";

        $query = $this->db->query($sql, $query_params);
        //echo $this->db->last_query();
        return $this->after_load_all($query->result());
    }


    function update_add_data($data)
    {
        $this->load->library('encrypt');
        if(isset($data['excerpt']) && trim($data['excerpt'])) {
            $data['excerpt'] = $this->encrypt->encode($data['excerpt']);
        }
        return $data;
    }


    function update_update_data($data)
    {
        $this->load->library('encrypt');
        if(isset($data['excerpt']) && trim($data['excerpt'])) {
            $data['excerpt'] = $this->encrypt->encode($data['excerpt']);
        }
        return $data;
    }

    function after_load($object)
    {
        $this->load->library('encrypt');
        if(isset($object->excerpt)) {
            $excerpt = $this->encrypt->decode($object->excerpt);
            if($excerpt) {
                $object->excerpt = $excerpt;
            }
        }
        return $object;
    }
}