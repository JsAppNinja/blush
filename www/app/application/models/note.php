<?php
class Note extends MY_Model
{
    protected static $fields = array(
        'text' => 'string'
    );

    function get_scope()
    {
        return "note";
    }

    function add_data() {
        $this->load->library('uuid');
        $data = array(
            'uuid' => $this->uuid->v4(),
            'created' => timestamp_to_mysqldatetime(now()),
            'deleted' => 0
        );
        return $data;
    }

    function load_by_customer_counselor($customer_id=0, $counselor_id=0)
    {
        $query = $this->db->get_where($this->get_scope(), array("customer_id" => $customer_id, "counselor_id" => $counselor_id));
        return $this->after_load_all($query->result());
    }

    function get_first($user_id=0) {
        if($user_id) {
            $conversations = $this->get_list(1,0, '', '', $user_id);
            return $conversations[0];
        }
    }

    function get_list($limit = 999, $offset = 0, $ordering = '', $filter = '', $counselor_id = 0)
    {
        if (!$ordering) {
            $ordering = array('sort' => 'created', 'dir' => 'DESC');
        } else {
            $ordering['sort'] = $this->column_map($ordering['sort']);
        }

        $query_params = array();

        $sql = "SELECT n.* ";

        $where = ' WHERE n.deleted = 0';
        $from = ' from ' . $this->get_scope() . ' n';
        if ($filter) {
            $where .= ' AND n.title like ?';
            array_unshift($query_params, $filter . '%');
        }

        if ($counselor_id > 0) {
            $where .= " and n.counselor_id = ? ";
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
        if(isset($data['text']) && trim($data['text'])) {
            $data['text'] = $this->encrypt->encode($data['text']);
        }
        return $data;
    }


    function update_update_data($data)
    {
        $this->load->library('encrypt');
        if(isset($data['text']) && trim($data['text'])) {
            $data['text'] = $this->encrypt->encode($data['text']);
        }
        return $data;
    }

    function after_load($object)
    {
        $this->load->library('encrypt');
        if(isset($object->text)) {
            $text = $this->encrypt->decode($object->text);
            if($text) {
                $object->text = $text;
            }
        }
        return $object;
    }
}