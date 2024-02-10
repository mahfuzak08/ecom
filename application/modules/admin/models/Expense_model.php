<?php

class Expense_model extends CI_Model
{

    public function __construct()
    {
        parent::__construct();
    }
	
	public function getExpenses($id, $orderby = 'name',  $type = 'asc')
    {
        $this->db->select('expenses.*, users.username');
        $this->db->join('users', 'expenses.created_by = users.id');
        if($id>0){
            $this->db->where("expenses.id", $id);
        }
        $this->db->where('expenses.shop_id', SHOP_ID);
        return $this->db->get("expenses")->result();
    }

    public function getExpenseTrans($id, $orderby='id', $type='asc')
    {
        $this->db->select('expense_trans.*, accounts.name');
        $this->db->join('accounts', 'expense_trans.account_id =  accounts.id', 'left');
        $this->db->where("expense_id", $id);
        $this->db->where('expense_trans.shop_id', SHOP_ID);
        $this->db->order_by($orderby, $type);
        return $this->db->get("expense_trans")->result_array();
    }
    
    public function getExpenseTransDetails($id)
    {
        $this->db->select('expense_trans.*, expenses.title as group, accounts.name');
        $this->db->join('expenses', 'expense_trans.expense_id =  expenses.id', 'left');
        $this->db->join('accounts', 'expense_trans.account_id =  accounts.id', 'left');
        $this->db->where("expense_trans.id", $id);
        $this->db->where('expense_trans.shop_id', SHOP_ID);
        return $this->db->get("expense_trans")->result_array();
    }

    public function setExpense($post)
    {
        $data = array(
            'shop_id' => SHOP_ID,
            'title'      =>$post['name'],
            'created_by'=>$_SESSION['logged_user_id']
        );
        
        if(! $this->db->insert('expenses', $data)){
            log_message('error', print_r($this->db->error(), true));
            show_error(lang('database_error'));
            return 'db error.';
        }
        return $this->db->insert_id();
    }

    public function delete_tran($id)
    {
        $this->db->trans_begin();
        
        if (!$this->db->where(array('type'=>'Expense', 'trans_no'=>$id, 'shop_id'=> SHOP_ID))->delete('account_trans')) {
            log_message('error', print_r($this->db->error(), true));
            show_error(lang('database_error'));
        }
        if (!$this->db->where(array('id'=>$id, 'shop_id'=> SHOP_ID))->delete('expense_trans')) {
            log_message('error', print_r($this->db->error(), true));
            show_error(lang('database_error'));
        }
        // if (!$this->db->where(array('id'=>$id, 'shop_id'=> SHOP_ID))->delete('expenses')) {
        //     log_message('error', print_r($this->db->error(), true));
        //     show_error(lang('database_error'));
        // }
        if ($this->db->trans_status() === FALSE) {
            $this->db->trans_rollback();
            return 'Error while deleting. Please try again after sometime';
        } else {
            $this->db->trans_commit();
            return 'successfull.';
        }
    }
    
    public function delete_group($id)
    {
        $rows = $this->db->where(array('expense_id'=>$id, 'shop_id'=> SHOP_ID))->get('expense_trans')->result_array();
        if(count($rows) == 0){
            $this->db->trans_begin();
            
            if (!$this->db->where(array('id'=>$id, 'shop_id'=> SHOP_ID))->delete('expenses')) {
                log_message('error', print_r($this->db->error(), true));
                show_error(lang('database_error'));
            }
            if ($this->db->trans_status() === FALSE) {
                $this->db->trans_rollback();
                return 'Error while deleting. Please try again after sometime';
            } else {
                $this->db->trans_commit();
                return 'successfull.';
            }
        }else{
            return 'Please delete expenses transection, before delete the group.';
        }
    }

    public function setTrans($post)
    {
        if(! $this->db->insert('expense_trans', array(
            'shop_id' => SHOP_ID,
            'expense_id'=>$post['eid'],
            'date'=>$post['date'],
            'title'=>$post['title'],
            'details'=>$post['details'],
            'account_id'=>$post['accno'],
            'amount'=>$post['amount'],
            'user_id'=>$_SESSION['logged_user_id']
            ))){
            log_message('error', print_r($this->db->error(), true));
            show_error(lang('database_error'));
            return 'db error.';
        }

        $etid = $this->db->insert_id();
        
        if(! $this->db->insert('account_trans', array(
            'shop_id' => SHOP_ID,
            'type'=>'Expense',
            'trans_no'=>$etid,
            'bank_act'=>$post['accno'],
            'details'=>$post['title'],
            'trans_date'=>$post['date'],
            'amount'=>($post['amount']*-1),
            'person_id'=>$_SESSION['logged_user_id']
            ))){
            log_message('error', print_r($this->db->error(), true));
            show_error(lang('database_error'));
            return 'db error.';
        }
        return $etid;
    }
}
