<?php
class Diary extends MY_Model
{

    protected static $fields = array(
        'title' => 'string',
        'comments' => 'string',
        'text' => 'string',
        'read' => 'int',
        'draft' => 'int',
        'commentor_id' => 'int',
        'comments_read' => 'int'
    );

    function get_scope()
    {
        return "diary";
    }

    function add_data() {
        $this->load->library('uuid');
        $data = array(
            'uuid' => $this->uuid->v4(),
            'user_id' => get_user_id(),
            'read' => 0,
            'created' => timestamp_to_mysqldatetime(now()),
            'deleted' => 0
        );
        return $data;
    }

    function count_unread($user_id, $exclude_draft = false) {
        $where = array("user_id"=>$user_id, "deleted"=>0, "read"=>0 );
        if($exclude_draft) {
            $where['draft'] = 0;
        }
        $this->db->where($where);
        $this->db->from($this->get_scope());
        return $this->db->count_all_results();
    }

    function get_count_new($user_id) {
        $sql = "select count(d.id) as cnt from ".$this->get_scope()." d, user_counselor uc where d.read = 0 and uc.user_id = d.user_id and uc.counselor_id = ?";
        $query = $this->db->query($sql, $user_id);
        $row = $query->row();
        return $row->cnt;
    }

    function get_count_new_comments($user_id) {
        $this->db->where(array('user_id' => $user_id, 'comments_read'=>0));
        $this->db->from($this->get_scope());
        return $this->db->count_all_results();
    }

    function find_responded_open() {
        $this->db->where(array('deleted'=>0, 'closed_out'=>0, 'comments IS NOT NULL' => NULL, 'comments <>' => '', 'commentor_id >' => 0));
        $query = $this->db->get($this->get_scope());
        return $this->after_load_all($query->result());
    }

    function get_list($limit = 999, $offset = 0, $ordering = '', $filter = '', $user_id = 0, $exclude_draft = false)
    {
        if (!$ordering) {
            $ordering = array('sort' => 'created', 'dir' => 'DESC');
        } else {
            $ordering['sort'] = $this->column_map($ordering['sort']);
        }

        $query_params = array();

        $sql = "SELECT d.* ";

        $where = ' WHERE d.deleted = 0';
        $from = ' from ' . $this->get_scope() . ' d';
        if ($filter) {
            $where .= ' AND d.title like ?';
            array_unshift($query_params, $filter . '%');
        }

        if ($user_id > 0) {
            $where .= " and d.user_id = ? ";
            $query_params[] = $user_id;
        }

        if ($exclude_draft > 0) {
            $where .= " and d.draft = 0 ";
        }

        $query_params[] = $offset;
        $query_params[] = $limit;

        $sql .= ' ' . $from . ' ' . $where . " ORDER BY " . $this->get_ordering($ordering) . " LIMIT ?, ? ";

        $query = $this->db->query($sql, $query_params);
        //echo $this->db->last_query();
        return $this->after_load_all($query->result());
    }

    function get_count_last_30() {
        $sql = 'select count(d.id) as cnt ';

        $where = 'WHERE d.deleted = 0 AND d.created > ? ';
        $from = ' from ' . $this->get_scope() . ' d';
        $query_params = array(timestamp_to_mysqldatetime(add_day(-31)));

        $sql .= ' ' . $from . ' ' . $where;

        $query = $this->db->query($sql, $query_params);
        //echo $this->db->last_query();
        $row = $query->row();
        return $row->cnt;
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
        if(isset($data['comments']) && trim($data['comments'])) {
            $data['comments'] = $this->encrypt->encode($data['comments']);
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
        if(isset($data['comments']) && trim($data['comments'])) {
            $data['comments'] = $this->encrypt->encode($data['comments']);
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
        if(isset($object->comments)) {
            $comments = $this->encrypt->decode($object->comments);
            if($comments) {
                $object->comments = $comments;
            }
        }
        return $object;
    }
}