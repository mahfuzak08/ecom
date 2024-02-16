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

        if(isset($_POST["sendsms"])){
            $this->sms_lib->toSms($_POST['number'], $_POST['msg']);
        }

        $data['expenses'] = $this->Expense_model->getExpenses($id);
        $data['languages'] = $this->Languages_model->getLanguages();
        
        if(empty($_GET['page']) || isset($_POST["sendsms"])){
            $data['bal'] = $_SESSION['logged_user'] = $this->sms_lib->getBalance();
        }
        else{
            $data['bal'] = $_SESSION['logged_user'];
        }
        $page = isset($_GET['page']) && $_GET['page'] > 0 ? $_GET['page']-1 : 0;
        $page *= 10;
        $data['smss'] = $this->db->limit(10, $page)->order_by('id', 'desc') ->get('sms_logs')->result();
        $data['total_sms'] = $this->db->count_all_results('sms_logs', FALSE);

        
    
        $this->saveHistory('Go to SMS Lists');
        $this->load->view('_parts/header', $head);
        $this->load->view('sms/lists', $data);
        $this->load->view('_parts/footer');
    }
}
