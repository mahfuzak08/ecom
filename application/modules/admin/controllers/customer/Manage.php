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
        $this->load->model(array('Customer_model', 'Sales_model', 'Languages_model'));
    }

    public function index($id = 0)
    {
        $this->login_check();
        $data = array();
        $head = array();
        $head['title'] = 'Administration - Customer Lists';
        $head['description'] = 'Customer Lists';
        $head['keywords'] = '';

        if(isset($_POST["customer_name"]) && isset($_POST["customer_mobile"]) && isset($_POST["customer_address"])){
            $id = $this->Customer_model->add_update_customer($_POST);
            if($id > 0) redirect('admin/customer');
        }
        if(isset($_GET['trnx_delete'])){
            $r = $this->Customer_model->delete_cus_trnx($_GET['trnx_delete']);
            if(!$r){
                $this->saveHistory('Customer transection delete. Details are: '.json_encode($r));
                $this->session->set_flashdata('result_delete', 'Customer transection delete successfully.');
                redirect('admin/customer');
            }
        }
        if(isset($_GET['delete'])){
            if($this->Customer_model->delete_cus($_GET['delete'])){
                $this->saveHistory('Delete a customer.');
                $this->session->set_flashdata('result_delete', 'Customer delete successfully.');
            }
        }
        elseif(isset($_GET['edit'])){
            $data['customer'] = $this->Customer_model->getCustomers($_GET['edit']);
        }
        
        $data['customers'] = $this->Customer_model->getCustomers($id, 'noorder', 'desc');
        // $data['languages'] = $this->Languages_model->getLanguages();
        if($id>0){
            $head['description'] = 'Customer Details';
            $data['payment_type'] = $this->Sales_model->get_payment_type();
            $data['acc_type'] = $this->Sales_model->get_payment_type();
            $data['trans'] = $this->Customer_model->getCustomerTrans($id);
            $this->saveHistory('Go to Customer Details');
            $this->load->view('_parts/header', $head);
            $this->load->view('customers/details', $data);
            $this->load->view('_parts/footer');
        }
        else{
            $this->saveHistory('Go to Customers');
            $this->load->view('_parts/header', $head);
            $this->load->view('customers/lists', $data);
            $this->load->view('_parts/footer');
        }
    }

    public function add_payment()
    {
        $customer_id = $this->input->post('id');
		$this->form_validation->set_rules('details', 'Payment Details', 'trim');
		$this->form_validation->set_rules('amount_tendered', 'lang:sales_amount_tendered', 'trim|is_natural_no_zero|required');
		
		if($this->form_validation->run() !== FALSE)
		{
            $_POST['payment_date'] = $this->input->post('payment_date')." ".date("h:i:s");
            $trnsid = $this->Customer_model->add_payment($_POST);
            if($trnsid !== false){
                $this->print_receipt($trnsid);
            }
            else{
                $this->session->set_flashdata('error', 'Payment not save, please contact to administrator.');
                redirect('admin/customer/'.$customer_id);
            }
        }
        else{
            $this->session->set_flashdata('error', 'Validation error');
            redirect('admin/customer/'.$customer_id);
        }
    }

    public function print_receipt($id)
    {
        $this->login_check();
        $data = array();
        $head = array();
        $head['title'] = 'Administration - Money Receipt';
        $head['description'] = 'Money Receipt';
        $head['keywords'] = '';
        $this->load->model(array('Home_admin_model'));
        $data['inv_addLine_1'] = $this->Home_admin_model->getValueStore('inv_addLine_1');
        $data['inv_addLine_2'] = $this->Home_admin_model->getValueStore('inv_addLine_2');
        $data['inv_addLine_3'] = $this->Home_admin_model->getValueStore('inv_addLine_3');
        $data['inv_addLine_4'] = $this->Home_admin_model->getValueStore('inv_addLine_4');
        
        $data['customer_payment_data'] = $this->Customer_model->get_cus_payment($id);
        // print_r($data['customer_payment_data'][0]['trans_for']);
        $data['customer_info'] = $this->Sales_model->get_customer_info($data['customer_payment_data'][0]['trans_for']);
        $data['barcode'] = $this->set_barcode($id);
        
        $this->load->library('Inword');
        $data['iw'] = $this->inword->numberToStr($data['customer_payment_data'][0]['amt']);
        
        $data['nf'] = 2;
        $this->saveHistory('Show Money Receipt');
        $this->load->view('_parts/header', $head);
        $this->load->view('customers/receipt', $data);
        $this->load->view('_parts/footer');
    }

    public function verify()
    {
        $this->login_check();
        $result = $this->Customer_model->verify($_POST);
        $this->saveHistory('Manually Customer Verify. Phone Number: ' . $_POST['to']);
    }
}
