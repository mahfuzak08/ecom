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
        $this->load->model(array('Accounts_model', 'Languages_model'));
    }

    public function index($id=0)
    {
        $this->login_check();
        $data = array();
        $head = array();
        $head['title'] = 'Administration - Account Lists';
        $head['description'] = 'Account Lists';
        $head['keywords'] = '';

        $data['accounts'] = $this->Accounts_model->getAccounts($id);
        $data['languages'] = $this->Languages_model->getLanguages();
        if(isset($_POST["add_account"])){
            $result = $this->Accounts_model->setAccount($_POST);
            $this->saveHistory('Add an account '. $result);
            redirect('admin/accounts');
        }
        else{
            $this->saveHistory('Go to Accounts');
            $this->load->view('_parts/header', $head);
            $this->load->view('account/lists', $data);
            $this->load->view('_parts/footer');
        }
    }
    
    public function acc_trans_details($id=0)
    {
        $this->login_check();
        $data = array();
        $head = array();
        $head['title'] = 'Administration - Account Transection Details';
        $head['description'] = 'Account Transection Details';
        $head['keywords'] = '';
        $data['nf'] = 2;
        $data['accounts'] = $this->Accounts_model->getAccounts($id);
        $data['languages'] = $this->Languages_model->getLanguages();
        $data['account_trans'] = $this->Accounts_model->getAccountTrans($id);
        if(isset($_GET['edit'])){
            $data['edit_trans'] = $this->Accounts_model->getAccountTrans($id, $_GET['edit']);
        }
        $this->saveHistory('Go to Account Transection Details');
        $this->load->view('_parts/header', $head);
        $this->load->view('account/details', $data);
        $this->load->view('_parts/footer');
    }

    public function add_trans()
    {
        if(isset($_POST['add_trans']) && $_POST['id']>0){
            $this->form_validation->set_rules('amount', 'Amount', 'trim|callback_numeric');
            if($this->form_validation->run() == FALSE)
            {
                $result = $this->Accounts_model->setTrans($_POST);
                if($_POST['trnxid'] == $result){
                    $this->saveHistory('Update an account transection '. $result);
                    $this->session->set_flashdata('success', 'Update an account transection');
                    redirect('admin/accounts/'.$_POST['id']);
                }
                elseif($result == 'db error.'){
                    $this->saveHistory('Add an account transection error');
                    $this->session->set_flashdata('warning', 'Add an account transection error');
                    redirect('admin/accounts/'.$_POST['id']);    
                }
                else{
                    $this->saveHistory('Add an account transection '. $result);
                    $this->session->set_flashdata('success', 'Add an account transection');
                    redirect('admin/accounts/'.$_POST['id']);
                }
            }
            else{
                $this->session->set_flashdata('error', 'Validation error.');
                redirect('admin/accounts');
            }    
        }
        else{
            $this->saveHistory('Add transection no id found.');
            redirect('admin/accounts');
        }
        
    }

    public function delete($id)
    {
        $this->login_check();
        $result = $this->Accounts_model->delete($id);
        $this->saveHistory('Delete an account : ' . $id . ' is '. $result);
        redirect('admin/accounts');
    }
}
