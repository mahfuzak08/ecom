<?php

class Customer_model extends CI_Model
{

    public function __construct()
    {
        parent::__construct();
    }
	
	public function getCustomers($id, $orderby = 'noorder',  $type = 'desc')
    {
        // SELECT c.*, COUNT(o.customer_id) as noorder, SUM(o.total)
        // FROM `customer` as c
        // LEFT JOIN `orders` as o ON c.id = o.customer_id
        // GROUP BY o.customer_id
        $this->db->select('customer.*, COUNT(orders.customer_id) as noorder, SUM(orders.total) as toamt');
        $this->db->join('orders', 'customer.id = orders.customer_id', 'left');
        $this->db->where('customer.shop_id', SHOP_ID);
        $this->db->group_by('customer.id');
        $this->db->order_by($orderby, $type);
        if($id>0){
            $this->db->where("customer.id", $id);
        }
        return $this->db->get("customer")->result();
    }
    
    public function getCustomerTrans($id)
    {
        // SELECT cus_sup_trans.*, orders.order_id, orders.order_type, orders.payment_type, orders.total, orders.asof_date_due 
        // FROM `cus_sup_trans`
        // LEFT JOIN `orders` ON cus_sup_trans.trans_no = orders.id 
        // WHERE cus_sup_trans.`trans_for` = 2 
        // ORDER BY cus_sup_trans.`id`
        $this->db->select('cus_sup_trans.*, orders.order_id, orders.order_type, orders.payment_type, orders.total, orders.asof_date_due');
        $this->db->join('orders', 'cus_sup_trans.trans_no = orders.id', 'left');
        $this->db->where('cus_sup_trans.shop_id', SHOP_ID);
        $this->db->where("cus_sup_trans.type", 'customer');
        if($id>0){
            $this->db->where("cus_sup_trans.trans_for", $id);
        }
        $this->db->order_by('cus_sup_trans.id', 'asc');
        return $this->db->get("cus_sup_trans")->result_array();
    }
    
    public function get_cus_payment($id)
    {
        if($id>0){
            $this->db->where("id", $id);
        }
        $this->db->where('shop_id', SHOP_ID);
        return $this->db->get("cus_sup_trans")->result_array();
    }

    public function add_payment($post)
    {
        $this->db->trans_begin();
        $this->db->where('id', $post['id']);
        $this->db->where('shop_id', SHOP_ID);
        $this->db->set('balance', 'balance+'.($post['amount_tendered'] * -1), FALSE);
        if( !$this->db->update('customer') ){
            log_message('error', print_r($this->db->error(), true));
            show_error(lang('database_error'));
        }

        if (!$this->db->insert('cus_sup_trans', array(
            'shop_id'   => SHOP_ID,
            'type'      => 'customer',
            'trans_for' => $post['id'],
            'amt'       => $post['amount_tendered'],
            'note'      => $post['details'],
            'trans_date'=> $post['payment_date'],
            'user_id'   => $_SESSION['logged_user_id']
            ))) {
            log_message('error', print_r($this->db->error(), true));
        }

        $lastId = $this->db->insert_id();

        if( ! $this->db->insert("account_trans", array(
            'shop_id' => SHOP_ID,
            'type'          => 'customer_payment',
            'trans_date'    => $post['payment_date'],
            'person_id'     => $_SESSION['logged_user_id'],
            'trans_no'      => $lastId,
            'bank_act'      => $post['payment_type'],
            'amount'        => $post['amount_tendered']
            ))){
            log_message('error', print_r($this->db->error(), true));
            show_error(lang('database_error'));
        }

        if ($this->db->trans_status() === FALSE) {
            $this->db->trans_rollback();
            return false;
        } else {
            $this->db->trans_commit();
            return $lastId;
        }
    }

    public function verify($post)
    {
        $this->db->where('shop_id', SHOP_ID);
        $this->db->where('phone', $post['to']);
        if (!$this->db->update('customer', array('phone_verify' => 'Yes'))) {
            log_message('error', print_r($this->db->error(), true));
            show_error(lang('database_error'));
        }
    }
    
    public function add_update_customer($post)
    {
        $check_mob = $this->countCustomerByPhone($post['customer_mobile']);
        $old_new = $post['old_customer_mobile'] == $post['customer_mobile'];
        $data = array(
            'shop_id'       => SHOP_ID,
            'name'          => $post['customer_name'],
            'phone'         => $post['customer_mobile'],
            'address'       => $post['customer_address'],
            'phone_verify'  => 'Yes'
        );
        if($post['customer_balance'] != 0){
            $data['balance'] = $post['customer_balance'];
        }
        if($post['id'] == 0 && $check_mob == 0){
            if(! $this->db->insert('customer', $data)){
                log_message('error', print_r($this->db->error(), true));
                return false;
            }
            return $this->db->insert_id();
        }
        else{
            if($old_new == false && $check_mob > 0) return false;
            if(! $this->db->where('id', $post['id'])->update('customer', $data)){
                log_message('error', print_r($this->db->error(), true));
                return false;
            }
            return $post['id']; 
        }
    }

    public function delete_cus($id){
        $this->db->where('shop_id', SHOP_ID);
        $this->db->where('id', $id);
        if (!$this->db->delete('customer')) {
            log_message('error', print_r($this->db->error(), true));
            return false;
        }
        return true;
    }

    private function countCustomerByPhone($phone)
    {
        $this->db->where('phone', $phone);
        $this->db->where('shop_id', SHOP_ID);
        return $this->db->count_all_results('customer');
    }
}
