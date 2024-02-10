<?php

class Dbwork_model extends CI_Model
{

    public function __construct()
    {
        parent::__construct();
    }

    public function delete_trans($type, $type2='')
    {
        $this->db->trans_begin();

        if($type == 'all'){
            if (!$this->db->where('shop_id', SHOP_ID)->delete('account_trans')) {
                log_message('error', print_r($this->db->error(), true));
            }
            
            if (!$this->db->where('shop_id', SHOP_ID)->delete('cus_sup_trans')) {
                log_message('error', print_r($this->db->error(), true));
            }
            
            if (!$this->db->where('shop_id', SHOP_ID)->delete('expense_trans')) {
                log_message('error', print_r($this->db->error(), true));
            }
            
            if (!$this->db->where('shop_id', SHOP_ID)->delete('history')) {
                log_message('error', print_r($this->db->error(), true));
            }
            
            if (!$this->db->where('shop_id', SHOP_ID)->delete('orders')) {
                log_message('error', print_r($this->db->error(), true));
            }
            
            if (!$this->db->where('shop_id', SHOP_ID)->delete('orders_clients')) {
                log_message('error', print_r($this->db->error(), true));
            }
            
            if (!$this->db->where('shop_id', SHOP_ID)->delete('orders_clients')) {
                log_message('error', print_r($this->db->error(), true));
            }
            
            if (!$this->db->where('shop_id', SHOP_ID)->delete('payments')) {
                log_message('error', print_r($this->db->error(), true));
            }
            
            if (!$this->db->where(array('shop_id'=> SHOP_ID, 'ref_id' > 0))->delete('product_trans')) {
                log_message('error', print_r($this->db->error(), true));
            }
            
            if (!$this->db->like('description', 'Initial Stock')->where('shop_id', SHOP_ID)->update('product_trans', array('quantity' => 0))) {
                log_message('error', print_r($this->db->error(), true));
            }
            
            if (!$this->db->where('shop_id', SHOP_ID)->delete('purchase_orders')) {
                log_message('error', print_r($this->db->error(), true));
            }
            
            if (!$this->db->where('shop_id', SHOP_ID)->delete('vendors_orders')) {
                log_message('error', print_r($this->db->error(), true));
            }
            
            if (!$this->db->where('shop_id', SHOP_ID)->delete('vendors_orders_clients')) {
                log_message('error', print_r($this->db->error(), true));
            }
            
            if (!$this->db->where('shop_id', SHOP_ID)->delete('wish_list')) {
                log_message('error', print_r($this->db->error(), true));
            }
            
            if (!$this->db->update('customer', array('balance'=>0), array('shop_id', SHOP_ID))) {
                log_message('error', print_r($this->db->error(), true));
            }
            
            // if (!$this->db->update('products', array('quantity'=>0), array('shop_id', SHOP_ID))) {
            //     log_message('error', print_r($this->db->error(), true));
            // }
            if($type2 == 'product_delete'){
                if (!$this->db->where(array('shop_id'=> SHOP_ID))->delete('products')) {
                    log_message('error', print_r($this->db->error(), true));
                }
            }
            
            if (!$this->db->update('vendors', array('balance'=>0), array('shop_id', SHOP_ID))) {
                log_message('error', print_r($this->db->error(), true));
            }
            
        }
        
        if ($this->db->trans_status() === FALSE) {
            $this->db->trans_rollback();
            return false;
        } else {
            $this->db->trans_commit();
            return true;
        }
    }

    public function getAdminUsers($user = null)
    {
        $this->db->where('shop_id', SHOP_ID);
        if ($user != null && is_numeric($user)) {
            $this->db->where('id', $user);
        } else if ($user != null && is_string($user)) {
            $this->db->where('username', $user);
        }
        $query = $this->db->get('users');
        if ($user != null) {
            return $query->row_array();
        } else {
            return $query;
        }
    }

    public function setAdminUser($post)
    {
        if ($post['edit'] > 0) {
            if (trim($post['password']) == '') {
                unset($post['password']);
            } else {
                $post['password'] = md5($post['password']);
            }
            $this->db->where('shop_id', SHOP_ID);
            $this->db->where('id', $post['edit']);
            unset($post['id'], $post['edit']);
            $post['access'] = implode(',', $post['access']);
            if (!$this->db->update('users', $post)) {
                log_message('error', print_r($this->db->error(), true));
                show_error(lang('database_error'));
            }
        } else {
            unset($post['edit']);
            $post['password'] = md5($post['password']);
            $post['access'] = implode(',', $post['access']);
            $post['shop_id'] = SHOP_ID;
            if (!$this->db->insert('users', $post)) {
                log_message('error', print_r($this->db->error(), true));
                show_error(lang('database_error'));
            }
        }
    }
	
	public function full_db_backup()
	{
		$this->load->dbutil();

		$prefs = array(
			'tables' => array(
				'accounts', 
				'account_trans', 
				'active_pages', 
				'bank_accounts', 
				'blog_posts', 
				'blog_translations', 
				'brands', 
				'confirm_links', 
				'cookie_law', 
				'cookie_law_translations', 
				'customer', 
				'cus_sup_trans', 
				'discount_codes', 
				'expenses', 
				'expense_trans', 
				'history', 
				'keys', 
				'languages', 
				'location', 
				'orders', 
				'orders_clients', 
				'payments', 
				'products', 
				'products_translations', 
				'product_trans', 
				'purchase_orders', 
				'seo_pages', 
				'seo_pages_translations', 
				'shop_categories', 
				'shop_categories_translations', 
				'shop_lists', 
				'subscribed', 
				'textual_pages_tanslations', 
				'users', 
				'value_store', 
				'vendors', 
				'vendors_orders', 
				'vendors_orders_clients', 
				'visitor_counter', 
				'wish_list'
				),
			'format' => 'zip',
			'filename' => 'ecom.sql'
		);

		$backup = $this->dbutil->backup($prefs);

		$file_name = time().'.zip';
		$save = 'db_backup/' . $file_name;
		$this->load->helper('download');
		while(ob_get_level())
		{
			ob_end_clean();
		}

		force_download($file_name, $backup);
	}

	public function todays_backup()
	{
		$this->load->dbutil();
		$tables = array(
				'accounts', 
				'account_trans', 
				'active_pages', 
				'bank_accounts', 
				'blog_posts', 
				'brands', 
				'confirm_links', 
				'cookie_law', 
				'customer', 
				'cus_sup_trans', 
				'discount_codes', 
				'expenses', 
				'expense_trans', 
				'history', 
				'keys', 
				'languages', 
				'location', 
				'orders', 
				'orders_clients', 
				'payments', 
				'products', 
				'product_trans', 
				'purchase_orders', 
				'seo_pages', 
				'shop_categories', 
				'subscribed', 
				'users', 
				'value_store', 
				'vendors', 
				'vendors_orders', 
				'vendors_orders_clients', 
				'visitor_counter', 
				'wish_list'
			);
		$tables2 = array(
				'blog_translations', 
				'cookie_law_translations', 
				'products_translations', 
				'seo_pages_translations', 
				'shop_categories_translations', 
				'shop_lists', 
				'textual_pages_tanslations'
			);

		$sqlScript = "";
		foreach ($tables as $table) {
			
			// Prepare SQLscript for creating table structure
			$result = $this->db->query("SHOW CREATE TABLE $table");
			$row = $result->row_array();
			
			$sqlScript .= "\n\n" . $row['Create Table'] . ";\n\n";
			
			// Prepare SQLscript for damping data
			$result = $this->db->query("SELECT * FROM $table WHERE shop_id = ".SHOP_ID)->result();
			foreach($result as $n => $row)
			{
				if($n%1000 == 0){
					$sqlScript .= $this->custome_insert_string($table, $row, true);
					$sqlScript .= "\n";
				}
				$sqlScript .= $this->custome_insert_string($table, $row, false);
				if(($n>0 && $n%1000 == 0) || ($n+1 == count($result)) ) 
					$sqlScript .= "; \n";
				else 
					$sqlScript .= ", \n";
			}
		}

		if(!empty($sqlScript))
		{
			// Save the SQL script to a backup file
			$backup_file_name = 'backup_' . time() . '.sql';
			$save = 'db_backup/' . $backup_file_name;
			$this->load->helper('download');
			while(ob_get_level())
			{
				ob_end_clean();
			}

			force_download($backup_file_name, $sqlScript);
		}

	}
	
	public function custome_insert_string($table, $data, $head=false)
	{
		$fields = $values = array();

		foreach ($data as $key => $val)
		{
			$fields[] = "`".mysql_real_escape_string($key)."`";
			$values[] = "'".mysql_real_escape_string($val)."'";
		}
		if($head)
			return 'INSERT INTO '.$table.' ('.implode(', ', $fields).') VALUES ';
		else
			return '('.implode(', ', $values).')';
	}
}
