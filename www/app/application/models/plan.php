<?php
class Plan extends MY_Model
{
    public static $fields = array(
        'name' => 'string',
        'price' => 'money',
        'discount_price' => 'money',
        'use_discount_price' => 'boolean',
        'coupon_code' => 'string'
    );

    function get_scope()
    {
        return "plan";
    }

    function load_by_plan_id($stripe_plan_id = '')
    {
        $this->db->where("stripe_plan_id", $stripe_plan_id);
        $query = $this->db->get($this->get_scope());
        return $this->after_load($query->row());
    }

    function column_map($col)
    {
        $column_map = array('name', 'price');
        return $column_map[intval($col)];
    }
}

?>