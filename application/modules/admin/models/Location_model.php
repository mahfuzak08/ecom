<?php

class Location_model extends CI_Model
{

    public function __construct()
    {
        parent::__construct();
    }

    public function getLocation()
    {
        $result = $this->db->where('shop_id', SHOP_ID)->get('location');
        return $result->result_array();
    }

    public function setLocation($id, $name, $cost)
    {
        if($id>0){
            $this->db->where('id', $id);
            $this->db->where('shop_id', SHOP_ID);
            if(!$this->db->update('location', array('name' => $name, 'cost'=>$cost))){
                log_message('error', print_r($this->db->error(), true));
                show_error(lang('database_error'));
            }
        }
        elseif (!$this->db->insert('location', array('shop_id' => SHOP_ID, 'name' => $name, 'cost'=>$cost))) {
            log_message('error', print_r($this->db->error(), true));
            show_error(lang('database_error'));
        }
    }

    public function deleteLocation($id)
    {
        $this->db->where('shop_id', SHOP_ID);
        if (!$this->db->where('id', $id)->delete('location')) {
            log_message('error', print_r($this->db->error(), true));
            show_error(lang('database_error'));
        }
    }

}
