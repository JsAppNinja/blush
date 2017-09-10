<?php
class Availability extends MY_Model
{
    public static $fields = array(
        'user_id' => 'int',
        'day' => 'int',
        'start_time' => 'string',
        'end_time' => 'string'
    );

    function get_scope()
    {
        return "availability";
    }

    function column_map($col)
    {
        $column_map = array('name', 'price');
        return $column_map[intval($col)];
    }

    function get_by_user($user_id = 0) {
        $this->db->where('user_id',$user_id);
        $this->db->order_by('day ASC, start_time ASC');
        $query = $this->db->get($this->get_scope());
        return $query->result();
    }

    function validate($user_id, $datetime) {
        $availability = $this->get_by_user($user_id);

        if(!$availability) {
            return true;
        }

        // Add thirty minutes
        $test = clone $datetime;
        //$test->add(new DateInterval('PT30M'));

        $day = $test->format('N')+1;
        /* Sunday is translated to 7 */
        if($day==8) {
            $day = 1;
        }
        $time = $test->format('H:i:s');
        $this->db->where(array(
            'user_id' => $user_id,
            'day' => $day
        ));
        $query = $this->db->get($this->get_scope());
        $results = $query->result();
        foreach($results as $row) {
            if($row->start_time <= $time) {

                // If the end time is less than the start time, we have crossed over into a new day, so
                // it's a valid time
                if($row->end_time < $row->start_time) {
                    return $row;
                }
                // Otherwise, it's at least thirty minutes after the time, so we're good.
                else if($row->end_time > $time) {
                    return $row;
                }
            }
        }
    }

    function delete($id, $user_id) {
        $this->db->delete($this->get_scope(), array("id" => $id, 'user_id' => $user_id));
    }
}

?>