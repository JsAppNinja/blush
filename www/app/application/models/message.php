<?php
class Message extends MY_Model
{

    protected static $fields = array(
        'title' => 'string',
        'text' => 'string'
    );

    function get_scope()
    {
        return "message";
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
        $this->db->where('sender_id',$user_id);
        $this->db->or_where('recipient_id',$user_id);
        $query = $this->db->get($this->get_scope());
        return $this->after_load_all($query->result());
    }

    function get_count_new($user_id, $conversation_id = 0) {
        $this->db->where(array('recipient_id' => $user_id, 'viewed'=>0));
        if($conversation_id>0) {
            $this->db->where('conversation_id', $conversation_id);
        }
        $this->db->from($this->get_scope());
        return $this->db->count_all_results();
    }

    function get_list($limit = 999, $offset = 0, $ordering = '', $filter = '', $conversation_id = 0)
    {
        if (!$ordering) {
            $ordering = array('sort' => 'created', 'dir' => 'ASC');
        } else if(!is_array($ordering)){
            $ordering['sort'] = $this->column_map($ordering['sort']);
        }

        $query_params = array();

        $sql = "SELECT m.* ";

        $where = ' WHERE m.deleted = 0';
        $from = ' from ' . $this->get_scope() . ' m';
        if ($filter) {
            $where .= ' AND m.title like ?';
            array_unshift($query_params, $filter . '%');
        }

        if ($conversation_id > 0) {
            $where .= " and m.conversation_id = ? ";
            $query_params[] = $conversation_id;
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
        if(isset($data['title']) && trim($data['title'])) {
            $data['title'] = $this->encrypt->encode($data['title']);
        }
        if(isset($data['text']) && trim($data['text'])) {
            $data['text'] = $this->encrypt->encode($data['text']);
        }
        return $data;
    }


    function update_update_data($data)
    {
        $this->load->library('encrypt');
        if(isset($data['title']) && trim($data['title'])) {
            $data['title'] = $this->encrypt->encode($data['title']);
        }
        if(isset($data['text']) && trim($data['text'])) {
            $data['text'] = $this->encrypt->encode($data['text']);
        }
        return $data;
    }

    function after_load($object)
    {
        $this->load->library('encrypt');
        if(isset($object->title)) {
            $title = $this->encrypt->decode($object->title);
            if($title) {
                $object->title = $title;
            }
        }
        if(isset($object->text)) {
            $text = $this->encrypt->decode($object->text);
            if($text) {
                $object->text = $text;
            }
        }
        return $object;
    }
}