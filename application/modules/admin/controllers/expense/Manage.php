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
        $head['title'] = 'Administration - Expense Lists';
        $head['description'] = 'Expense Lists';
        $head['keywords'] = '';

        $data['expenses'] = $this->Expense_model->getExpenses($id);
        // $data['languages'] = $this->Languages_model->getLanguages();
        if(isset($_POST["add_expense"])){
            $result = $this->Expense_model->setExpense($_POST);
            $this->saveHistory('Add an expense '. $result);
            redirect('admin/expenses');
        }
        else{
            $this->saveHistory('Go to Expense Lists');
            $this->load->view('_parts/header', $head);
            $this->load->view('expense/lists', $data);
            $this->load->view('_parts/footer');
        }
    }
    
    public function expense_trans_details($id=0)
    {
        $this->login_check();
        $data = array();
        $head = array();
        $head['title'] = 'Administration - Expense Transection Details';
        $head['description'] = 'Expense Transection Details';
        $head['keywords'] = '';
        $data['nf'] = 2;
        $data['expenses'] = $this->Expense_model->getExpenses($id);
        // $data['languages'] = $this->Languages_model->getLanguages();
        $data['expense_trans'] = $this->Expense_model->getExpenseTrans($id);
        $data['accounts'] = $this->Accounts_model->getAccounts(0);
        $this->saveHistory('Go to Expense Transection Details');
        $this->load->view('_parts/header', $head);
        $this->load->view('expense/details', $data);
        $this->load->view('_parts/footer');
    }

    public function add_trans()
    {
        $this->login_check();
        if(isset($_POST['add_trans']) && $_POST['eid']>0){
            $this->form_validation->set_rules('date', 'Date', 'callback_valid_date');
            $this->form_validation->set_rules('title', 'Expense Title', 'trim|required');
            $this->form_validation->set_rules('details', 'Expense Title', 'trim');
            $this->form_validation->set_rules('accno', 'Account', 'required|callback_numeric');
            $this->form_validation->set_rules('amount', 'Amount', 'trim|required|callback_numeric');
            if($this->form_validation->run() == FALSE)
            {
                if($_POST['amount'] > $this->Accounts_model->getAccountBal($_POST['accno'])){
                    $this->session->set_flashdata('error', 'Not enough balance in this account.');
                    redirect('admin/expenses/'.$_POST['accno']);
                }
                $result = $this->Expense_model->setTrans($_POST);
                if(is_numeric($result)){
                    $this->saveHistory('Add an expense transection '. $result);
                    $this->session->set_flashdata('success', 'Successfully transection added');
                    redirect('admin/expenses/print_bill/'.$result);
                }else{
                    $this->session->set_flashdata('error', $result);
                    redirect('admin/expenses/'.$_POST['accno']);
                }
            }
            else{
                $this->session->set_flashdata('error', 'Validation error.');
                redirect('admin/expenses/'.$_POST['accno']);
            }    
        }
        else{
            $this->saveHistory('Add transection no id found.');
            redirect('admin/expenses');
        }
        
    }

    public function valid_date($date) {
        $d = DateTime::createFromFormat('Y-m-d', $date);
        return $d && $d->format('Y-m-d') === $date;
    }

    public function delete($id)
    {
        $this->login_check();
        if($_GET['type']==='t')
            $result = $this->Expense_model->delete_tran($id);
        elseif($_GET['type']==='g')
            $result = $this->Expense_model->delete_group($id);
        if($result === 'successfull.')
            $this->session->set_flashdata('success', 'Delete successfully');
        else
            $this->session->set_flashdata('error', $result);

        $this->saveHistory('Delete an expense : ' . $id . ' is '. $result);
        redirect('admin/expenses');
    }

    public function print_bill($id)
    {
        $this->login_check();
        $data = array();
        $head = array();
        $head['title'] = 'Administration - Expense Bill';
        $head['description'] = 'Expense Bill';
        $head['keywords'] = '';
        $data['exp_trans_details'] = $this->Expense_model->getExpenseTransDetails($id);
        
        $data['inv_addLine_1'] = $this->Home_admin_model->getValueStore('inv_addLine_1');
        $data['inv_addLine_2'] = $this->Home_admin_model->getValueStore('inv_addLine_2');
        $data['inv_addLine_3'] = $this->Home_admin_model->getValueStore('inv_addLine_3');
        $data['inv_addLine_4'] = $this->Home_admin_model->getValueStore('inv_addLine_4');
        
        $this->load->library('Inword');
        $data['iw'] = $this->inword->numberToStr($data['exp_trans_details'][0]['amount']);
        
        $data['barcode'] = $this->set_barcode($id);
        $data['nf'] = 2;
        $this->saveHistory('Show Expense Bill');
        $this->load->view('_parts/header', $head);
        $this->load->view('expense/receipt', $data);
        $this->load->view('_parts/footer');
    }
}
