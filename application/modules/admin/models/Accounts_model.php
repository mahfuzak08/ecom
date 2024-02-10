<?php

class Accounts_model extends CI_Model
{

    public function __construct()
    {
        parent::__construct();
    }
	
	public function getAccounts($id, $orderby = 'name',  $type = 'asc')
    {
        if($id>0){
            $this->db->where("id", $id);
        }
        $this->db->where('shop_id', SHOP_ID);
        return $this->db->get("accounts")->result();
    }

    public function getAccountTrans($id, $trnxid = 0, $orderby='trans_date', $type='asc', $sd='', $ed='', $tt = '')
    {
        if($trnxid>0){
            $this->db->where('id', $trnxid);
        }
        if($sd != '' && $ed != ''){
            $this->db->where('trans_date >= ', $sd);
            $this->db->where('trans_date <= ', $ed);
        }
        if($tt != ''){
            $this->db->like('type', $tt);
        }
        
        $this->db->where('shop_id', SHOP_ID);
        $this->db->where("bank_act", $id);
        $this->db->order_by($orderby, $type);
        return $this->db->get("account_trans")->result_array();
    }
    
    public function getAccountBal($id)
    {
        $this->db->select("SUM(amount) as balance");
        $this->db->where('shop_id', SHOP_ID);
        $this->db->where("bank_act", $id);
        $this->db->group_by("bank_act");
        return $this->db->get("account_trans")->row("balance");
    }

    public function setAccount($post)
    {
        $data = array(
            'shop_id' => SHOP_ID,
            'name'=>$post['name'],
            'type'=>$post['type'],
            'bank_name'=>$post['bank_name'] != "" ? $post['bank_name'] : NULL,
            'bank_address'=>$post['bank_address'] != "" ? $post['bank_address'] : NULL,
            'acc_no'=>$post['acc_no'] != "" ? $post['acc_no'] : NULL,
            'currency'=>$post['currency'] != "" ? $post['currency'] : 'BDT'
        );
        
        if(! $this->db->insert('accounts', $data)){
            log_message('error', print_r($this->db->error(), true));
            show_error(lang('database_error'));
            return 'db error.';
        }
        $post['id'] = $this->db->insert_id();

        if($post['opening_balance'] != 0 && ($post['type'] != 'Due' || $post['type'] != 'GiftCard')){
            if(! $this->db->insert('account_trans', array(
                'shop_id' => SHOP_ID,
                'type'          => 'Direct',
                'details'       => 'Opening Balance',
                'bank_act'      => $post['id'],
                'trans_date'    => $post['date'],
                'person_id'     => $_SESSION['logged_user_id'],
                'amount'        => $post['opening_balance']
                ))) {
                log_message('error', print_r($this->db->error(), true));
            }
        }

        return $post['id']; 
    }

    public function delete($id)
    {
        $this->db->where('shop_id', SHOP_ID);
        $this->db->where('id', $id);
        if (!$this->db->delete('accounts')) {
            log_message('error', print_r($this->db->error(), true));
            show_error(lang('database_error'));
            return 'db error.';
        }
        return 'successfull.';
    }

    public function setTrans($post)
    {
        $data = array(
            'shop_id' => SHOP_ID,
            'type'=>'Direct',
            'bank_act'=>$post['id'],
            'details'=>$post['note'],
            'trans_date'=>$post['date'],
            'amount'=>$post['type'] == "Deposit" ? $post['amount'] : ($post['amount']*-1),
            'person_id'=>$_SESSION['logged_user_id']
        );
        
        if(! empty($post['trnxid']) && $post['trnxid']>0){
            if(! $this->db->where('id', $post['trnxid'])->update('account_trans', $data)){
                log_message('error', print_r($this->db->error(), true));
                show_error(lang('database_error'));
                return 'db error.';
            }
            return $post['trnxid'];
        }
        else{
            if(! $this->db->insert('account_trans', $data)){
                log_message('error', print_r($this->db->error(), true));
                show_error(lang('database_error'));
                return 'db error.';
            }
            return $this->db->insert_id();
        }
    }
}
