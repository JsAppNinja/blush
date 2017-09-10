<?php
class Availability_Calendar extends MY_Model
{

    protected static $fields = array(
        'user_id' => 'int',
        'is_available' => 'int',
        'is_all_day' => 'int',
        'date' => 'date',
        'start_time' => 'string',
        'end_time' => 'string'
    );

    function get_scope()
    {
        return "availability_calendar";
    }

    function get_by_user($user_id = 0, $date = '') {
        $this->db->where(array('user_id' => $user_id, 'date' => $date));
        $this->db->order_by('date ASC, start_time ASC');
        $query = $this->db->get($this->get_scope());
        return $query->result();
    }

    function validate($user_id, $datetime) {
        $test = clone $datetime;
        $test->add(new DateInterval('PT30M'));
        $availabilities = $this->get_by_user($user_id, $test->format('Y-m-d'));
        if(!$availabilities) {
            return 0;
        } else {
            foreach($availabilities as $availability) {
                //array_print($availabilities);
                // Check to see if they are unavailable all day
                if($availability->is_all_day) {
                    if($availability->is_available<0) {
                        return -1;
                    } else {
                        // They are available all day
                        return 1;
                    }
                } else {
                    date_default_timezone_set(getenv('TZ'));
                    $start_time = strtotime($availability->date." ".$availability->start_time);
                    $end_time = strtotime($availability->date." ".$availability->end_time);
                    $timestamp = $test->getTimestamp();
                    //array_print($availability);
                    //echo "\n".$test->format('Y-m-d H:i')."\n";
                    //echo "DATETIME: ".$timestamp."\n";
                    //echo "START: ".$start_time."\n";
                    //echo "END: ".$end_time."\n";
                    //echo "TIMESTAMP: ".$timestamp."\n";

                    // If the availability IS AVAILABLE -
                    //- Return 1 if between start/end,
                    //- Return -1 otherwise;
                    if(intval($availability->is_available)>0) {
                        if($timestamp > $start_time && $timestamp <= $end_time) {
                            return 1;
                        }
                    } else {

                        //If the availability IS UNAVAILABLE
                        // - Return 0 if not between start/end
                        // - Return -1 if otherwise
                        if($timestamp >= $start_time && $timestamp <= $end_time) {
                            return -1;
                        }
                    }
                }
            }

            return 0;
        }
    }

    function get_list($limit = 999, $offset = 0, $ordering = '', $filter = '', $user_id = 0, $start_timestamp = '', $end_timestamp = '')
    {
        if (!$ordering) {
            $ordering = array('sort' => 'date', 'dir' => 'ASC');
        } else {
            $ordering['sort'] = $this->column_map($ordering['sort']);
        }

        $query_params = array();

        $sql = "SELECT ac.* ";

        $where = ' WHERE 1=1';
        $from = ' from ' . $this->get_scope() . ' ac';

        if ($user_id > 0) {
            $where .= " and ac.user_id = ? ";
            $query_params[] = $user_id;
        }

        if($start_timestamp) {
            $start_date = timestamp_to_mysqldate($start_timestamp);
            $start_time = timestamp_to_mysqltime($start_timestamp);
            $where.=" and (ac.date > ? || (ac.date = ? && ac.start_time >= ?))";
            $query_params[] = $start_date;
            $query_params[] = $start_date;
            $query_params[] = $start_time;
        }

        if($end_timestamp) {
            $end_date = timestamp_to_mysqldate($end_timestamp);
            $where.=" and ac.date <= ?";
            $query_params[] = $end_date;
        }

        $query_params[] = $offset;
        $query_params[] = $limit;

        $sql .= ' ' . $from . ' ' . $where . " ORDER BY " . $this->get_ordering($ordering) . " LIMIT ?, ? ";

        $query = $this->db->query($sql, $query_params);
        return $query->result();
    }

    function column_map($col)
    {
        $column_map = array('id');
        return $column_map[intval($col)];
    }
}

?>