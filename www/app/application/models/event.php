<?php
class Event extends MY_Model
{

    protected static $fields = array(
        'title' => 'string',
        'text' => 'string',
        'date' => 'date',
        'start_time' => 'time',
        'start_ampm' => 'string',
        'end_time' => 'time',
        'end_ampm' => 'string',
        'counselor_id' => 'int'
    );

    function get_scope()
    {
        return "event";
    }

    function add_data()
    {
        $this->load->library('uuid');
        $data = array(
            'uuid' => $this->uuid->v4(),
            'created' => timestamp_to_mysqldatetime(now()),
            'deleted' => 0,
            'closed_out' => 0,
        );
        return $data;
    }

    function get_by_user($user_id) {
        $this->db->where('customer_id',$user_id);
        $this->db->where('deleted',0);
        $this->db->or_where('counselor_id',$user_id);
        $query = $this->db->get($this->get_scope());
        return $query->result();
    }

    function find_existing($array) {
        $this->db->where('deleted',0);
        $this->db->where($array);
        $result = $this->db->get($this->get_scope());
        return $result->row();
    }

    function find_future() {
        $this->db->where(array('deleted'=>0, 'closed_out' => date("Y-m-d")));
        $query = $this->db->get($this->get_scope());
        return $query->result();
    }

    function find_future_customer($customer_id = 0) {
        $this->db->where(array('deleted'=>0, 'date >=' => date("Y-m-d"), 'customer_id' => $customer_id, 'closed_out' => 0));
        $query = $this->db->get($this->get_scope());
        return $query->result();
    }

    function find_past_open() {
        $this->db->where(array('deleted'=>0, 'closed_out'=>0, 'date <' => date("Y-m-d")));
        $query = $this->db->get($this->get_scope());
        return $query->result();
    }

    function get_upcoming($customer_id = 0, $counselor_id = 0) {
        $date = now()+(60*UPCOMING_EVENT_DELAY);
        //echo date("Y-m-d H:i:s", $date)."\n";

        $this->db->where(array(
            'date' => date("Y-m-d", now()),
            'start_time <= ' => date("H:i", $date),
            'end_time > ' => date("H:i", now()),
            'deleted' => 0
        ));
        if($customer_id) {
            $this->db->where('customer_id',$customer_id);
        }
        if($counselor_id) {
            $this->db->where('counselor_id',$counselor_id);
        }
        $this->db->order_by('start_time', 'asc');

        $result = $this->db->get($this->get_scope());
        //echo $this->db->last_query()."\n";
        return $result->row();
    }

    function get_upcoming_26hours($customer_id = 0, $counselor_id = 0) {
        $start = now()+(60*60*27);
        $end = now()+(60*60*28);
        echo date("Y-m-d H:i:s", $start)."<br/>";

        $this->db->where('date', date("Y-m-d", $start));
        $this->db->where('start_time >=', date("H:00", $start));
        $this->db->where('start_time <', date("H:00", $end));
        $this->db->where('deleted', 0);
        if($customer_id) {
            $this->db->where('customer_id',$customer_id);
        }
        if($counselor_id) {
            $this->db->where('counselor_id',$counselor_id);
        }

        $result = $this->db->get($this->get_scope());
        echo $this->db->last_query()."<br/>";
        return $result->result();
    }

    function get_list($limit = 999, $offset = 0, $ordering = '', $filter = '', $customer_id = 0, $counselor_id = 0, $start_timestamp = '', $end_timestamp = '')
    {
        if (!$ordering) {
            $ordering = array('sort' => 'created', 'dir' => 'ASC');
        } else {
            $ordering['sort'] = $this->column_map($ordering['sort']);
        }

        $query_params = array();

        $sql = "SELECT e.* ";

        $where = ' WHERE e.deleted = 0';
        $from = ' from ' . $this->get_scope() . ' e';
        if ($filter) {
            $where .= ' AND e.title like ?';
            array_unshift($query_params, $filter . '%');
        }

        if ($customer_id > 0) {
            $where .= " and e.customer_id = ? ";
            $query_params[] = $customer_id;
        }

        if ($counselor_id > 0) {
            $where .= " and e.counselor_id = ? ";
            $query_params[] = $counselor_id;
        }

        if($start_timestamp) {
            $start_date = timestamp_to_mysqldate($start_timestamp);
            $start_time = timestamp_to_mysqltime($start_timestamp);
            $where.=" and (e.date > ? || (e.date = ? && e.start_time >= ?))";
            $query_params[] = $start_date;
            $query_params[] = $start_date;
            $query_params[] = $start_time;
        }

        if($end_timestamp) {
            $end_date = timestamp_to_mysqldate($end_timestamp);
            $where.=" and e.date <= ?";
            $query_params[] = $end_date;
        }

        $query_params[] = $offset;
        $query_params[] = $limit;

        $sql .= ' ' . $from . ' ' . $where . " ORDER BY " . $this->get_ordering($ordering) . " LIMIT ?, ? ";

        $query = $this->db->query($sql, $query_params);
        //echo $this->db->last_query();
        return $query->result();
    }

    function get_count_last_30() {
        $sql = 'select count(e.id) as cnt ';

        $where = 'WHERE e.deleted = 0 AND e.date > ? ';
        $from = ' from ' . $this->get_scope() . ' e';
        $query_params = array(timestamp_to_mysqldatetime(add_day(-31)));

        $sql .= ' ' . $from . ' ' . $where;

        $query = $this->db->query($sql, $query_params);
        //echo $this->db->last_query();
        $row = $query->row();
        return $row->cnt;
    }
}