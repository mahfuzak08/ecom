<?php

/*
 * @Author:    Md. Mahfuzur Rahman
 *  Gitgub:    https://github.com/mahfuzak08/
 */
if (!defined('BASEPATH')) {
    exit('No direct script access allowed');
}

class Manage extends ADMIN_Controller
{

    private $num_rows = 10;

    public function __construct()
    {
        parent::__construct();
        $this->load->model(array('Expense_model', 'Languages_model', 'Accounts_model'));
    }

    public function index($id=0)
    {
        $this->login_check();
        $data = array();
        $head = array();
        $head['title'] = 'Administration - SMS';
        $head['description'] = 'SMS';
        $head['keywords'] = '';

        // $data['expenses'] = $this->Expense_model->getExpenses($id);
        // $data['languages'] = $this->Languages_model->getLanguages();
        
        // if(empty($_GET['page'])){
        //     $data['bal'] = $_SESSION['logged_user'] = $this->sms_lib->getBalance();
        // }
        // else{
            $data['bal'] = $_SESSION['logged_user'];
        // }

        if(isset($_POST["add_expense"])){
            $result = $this->Expense_model->setExpense($_POST);
            $this->saveHistory('Add an expense '. $result);
            redirect('admin/expenses');
        }
        else{
            $this->saveHistory('Go to SMS Lists');
            $this->load->view('_parts/header', $head);
            $this->load->view('sms/lists', $data);
            $this->load->view('_parts/footer');
        }
    }
}
