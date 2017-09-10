<?php
class Notification extends MY_Model
{
    protected static $fields = array(
        'body' => 'raw',
    );

    function get_scope()
    {
        return "notification";
    }

    function column_map($col)
    {
        $column_map = array('name', 'description');
        return $column_map[intval($col)];
    }

    function add($transfer, $counselor_id=0)
    {
        $this->load->library('uuid');

        $amount = $transfer['amount'];

        $data = array(
            'uuid'            => $this->uuid->v4()
        );

        $query = $this->db->query($this->db->insert_string($this->get_scope(), $data));
        $id = $this->db->insert_id();
        $this->after_add($id, $counselor_id);
        return $id;
    }
}

?>