<?php
class Configuration extends MY_Model
{

    protected static $fields = array(
        '' => 'string'
    );

    function get_scope()
    {
        return "configuration";
    }

    function get_all_map() {
        $rows = $this->get_all();
        $configuration = array();
        foreach($rows as $row) {
            $configuration[$row->key] = $row->value;
        }
        return $configuration;
    }

    function get() {
        // $this->db->where("key",$key);
        $query = $this->db->get($this->get_scope());
        return $query->row();
    }

    function update_all($configuration) {
        foreach($configuration as $key => $value) {
            $this->db->where('key', $key);
            $this->db->update($this->get_scope(), array('value' => $value));
        }
    }

    function add_data()
    {
        $this->load->library('uuid');

        $data = array(
            'uuid' => $this->uuid->v4(),
            'created' => timestamp_to_mysqldatetime(now())
        );
        return $data;
    }
}

?>