<?php

class Vendor_model extends CI_Model
{

    public function __construct()
    {
        parent::__construct();
    }
	
	public function getOneVendor($vendor_id)
    {
        $this->db->where('id', $vendor_id);
        $this->db->where('shop_id', SHOP_ID);
        $query = $this->db->get('vendors');
        if ($query->num_rows() > 0) {
            return $query->row_array();
        } else {
            return false;
        }
	}
	
	public function getVendor()
	{
		$this->db->order_by('vendors.name', 'asc');
        $this->db->where("is_delete<2");
        $this->db->where('shop_id', SHOP_ID);
		$result = $this->db->get('vendors');
		return $result->result_array();
	}
	
    public function deleteVendor($id, $type)
    {
        $this->db->trans_begin();
        $this->db->where('vendor_id', $id);
        if($type = 2){
            $this->db->where('shop_id', SHOP_ID);
            if (!$this->db->update('products', array('visibility'=>0))) {
                log_message('error', print_r($this->db->error(), true));
            }
    
            $this->db->where('id', $id);
            $this->db->where('shop_id', SHOP_ID);
            if (!$this->db->update('vendors', array('is_delete'=>$type))) {
                log_message('error', print_r($this->db->error(), true));
            }    
        }else{
            $visi = $type == 1 ? 0 : 1;
            $this->db->where('shop_id', SHOP_ID);
            if (!$this->db->update('products', array('visibility'=>$visi))) {
                log_message('error', print_r($this->db->error(), true));
            }
    
            $this->db->where('id', $id);
            $this->db->where('shop_id', SHOP_ID);
            if (!$this->db->update('vendors', array('is_delete'=>$type))) {
                log_message('error', print_r($this->db->error(), true));
            }
        }
        
        if ($this->db->trans_status() === FALSE) {
            $this->db->trans_rollback();
            show_error(lang('database_error'));
        } else {
            $this->db->trans_commit();
        }
    }

    public function vendorsCount($search_title = null)
    {
        if ($search_title != null) {
            $search_title = trim($this->db->escape_like_str($search_title));
            $this->db->where("is_delete=0");
            $this->db->where("(vendors.name LIKE '%$search_title%')");
        }
        $this->db->where('shop_id', SHOP_ID);
        return $this->db->count_all_results('vendors');
    }

    public function getvendors($limit, $page, $search_title = null, $orderby = null)
    {
        if ($search_title != null) {
            $search_title = trim($this->db->escape_like_str($search_title));
            $this->db->where("(vendors.name LIKE '%$search_title%')");
            // $this->db->where("(vendors.url LIKE '%$search_title%')");
        }
        if ($orderby !== null) {
            $ord = explode('=', $orderby);
            if (isset($ord[0]) && isset($ord[1])) {
                $this->db->order_by('vendors.' . $ord[0], $ord[1]);
            }
        } else {
            $this->db->order_by('vendors.name', 'asc');
        }
        
        $this->db->where("is_delete<2");
        $this->db->where('shop_id', SHOP_ID);
        $query = $this->db->select('id as vendor_id, name as vendor_name, url as vendor_address, mobile as vendor_mobile, email as vendor_email, balance, is_delete as active')->get('vendors', $limit, $page);
        return $query->result();
    }

    public function setVendor($post, $id = 0)
    {
        $this->db->trans_begin();
        $is_update = false;
        if ($id > 0) {
            $is_update = true;
            if (!$this->db->where('id', $id)->update('vendors', array(
                        'name' => $post['vendor_name'],
                        'url' => $post['vendor_url'],
						'email' => trim($post['vendor_email']),
						'password' => ($post['vendor_pass'] != '' && $post['vendor_conpass'] != '' && $post['vendor_pass'] == $post['vendor_conpass']) ? password_hash($post['vendor_pass'], PASSWORD_DEFAULT) : $post['oldpass'],
						'mobile' => $post['vendor_mobile']
                    ))) {
                log_message('error', print_r($this->db->error(), true));
            }
        } else {
			if($this->checkVendorExsists($post)){
				if (!$this->db->insert('vendors', array(
                            'shop_id' => SHOP_ID,
							'name' => $post['vendor_name'],
							'url' => $post['vendor_url'],
							'email' => trim($post['vendor_email']),
							'password' => password_hash($post['vendor_pass'], PASSWORD_DEFAULT),
							'mobile' => $post['vendor_mobile']
						))) {
					log_message('error', print_r($this->db->error(), true));
				}
				$id = $this->db->insert_id();
			}
			else
				log_message('error', print_r("Vendor duplication found.", true));
        }
        
		if ($this->db->trans_status() === FALSE) {
            $this->db->trans_rollback();
            show_error(lang('database_error'));
        } else {
            $this->db->trans_commit();
			return $id;
        }
    }

	public function checkVendorExsists($post)
    {
        $this->db->where('email', $post['vendor_email']);
        $this->db->or_where('mobile', $post['vendor_mobile']);
        $this->db->where('shop_id', SHOP_ID);
        $query = $this->db->get('vendors');
        $row = $query->row_array();
        if (empty($row)) return true;
        return false;
    }
}
