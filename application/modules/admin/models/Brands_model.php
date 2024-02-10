<?php

class Brands_model extends CI_Model
{

    public function __construct()
    {
        parent::__construct();
    }

    public function getBrands($abbr='')
    {
        $this->db->where('shop_id', SHOP_ID);
        if($abbr != '')
            $this->db->where('abbr',$abbr);
        else
            $this->db->where('abbr', MY_LANGUAGE_ABBR);
        $result = $this->db->get('brands');
        return $result->result_array();
    }

    public function setBrand($post)
    {
        $post['brand_id'] = time();
        for($i=0; $i<count($post['translations']); $i++){
            if (!$this->db->insert('brands', array('shop_id' => SHOP_ID, 'brand_id' => $post['brand_id'], 'name' => $post['name'][$i], 'abbr' => $post['translations'][$i]))) {
                log_message('error', print_r($this->db->error(), true));
                show_error(lang('database_error'));
            }
        }
    }

    public function deleteBrand($id)
    {
        $this->db->where('shop_id', SHOP_ID);
        if (!$this->db->where('brand_id', $id)->delete('brands')) {
            log_message('error', print_r($this->db->error(), true));
            show_error(lang('database_error'));
        }
    }

}
