<?php

/*
 * @Author:    Md. Mahfuzur Rahman
 *  Gitgub:    https://github.com/mahfuzak08/
 */
if (!defined('BASEPATH')) {
    exit('No direct script access allowed');
}

class Salemanage extends ADMIN_Controller
{

    private $num_rows = 10;

    public function __construct()
    {
        parent::__construct();
        $this->load->model(array('Home_admin_model', 'Sales_model', 'Customer_model', 'Languages_model', 'Categories_model'));
        $this->load->library('pos_ses_lib');
    }

    public function index()
    {
        $this->login_check();
        $data = array();
        $head = array();
        $head['title'] = 'Administration - Sale';
        $head['description'] = 'Sales Register';
        $head['keywords'] = '';

        $data['products_lang'] = $products_lang = $this->session->userdata('admin_lang_products');
        
        if(isset($_GET['settings'])){
            $data['page_width'] = $this->Home_admin_model->getValueStore('page_width');
            $data['printer_type'] = $this->Home_admin_model->getValueStore('printer_type');
            $data['details_img'] = $this->Home_admin_model->getValueStore('details_img');
            $data['logo_in'] = $this->Home_admin_model->getValueStore('logo_in');
            
            $data['inv_addLine_1'] = $this->Home_admin_model->getValueStore('inv_addLine_1');
            $data['inv_addLine_2'] = $this->Home_admin_model->getValueStore('inv_addLine_2');
            $data['inv_addLine_3'] = $this->Home_admin_model->getValueStore('inv_addLine_3');
            $data['inv_addLine_4'] = $this->Home_admin_model->getValueStore('inv_addLine_4');
        }else{
            $data['page_width'] = 0;
            $data['printer_type'] = 0;
            $data['details_img'] = 0;
            $data['logo_in'] = 0;
            $data['inv_addLine_1'] = "";
            $data['inv_addLine_2'] = "";
            $data['inv_addLine_3'] = "";
            $data['inv_addLine_4'] = "";
        }
        
        // Load data
        $data['mode'] = $this->pos_ses_lib->get_mode();
        if(strpos($data['mode'], 'purchase') !== false){
            $this->pos_ses_lib->clear_all();
            $this->pos_ses_lib->set_mode('sale');
            $data['mode'] = 'sale';
        }
        $data['payment_type'] = $this->pos_ses_lib->get_account_list();
        if(count($data['payment_type']) == 0 ){
            $data['payment_type'] = $this->Sales_model->get_payment_type();
            $this->pos_ses_lib->set_account_list($data['payment_type']);
        }
        $data['invDesShow'] = $this->Home_admin_model->getValueStore('invDesShow');
        $data['inv_date'] = $this->pos_ses_lib->get_inv_date();
        $data['items'] = $this->pos_ses_lib->get_cart();
        $data['subtotal'] = $this->pos_ses_lib->get_subtotal();
        $data['due_collect'] = $this->pos_ses_lib->get_due_collect();
        $data['labour_cost'] = $this->pos_ses_lib->get_labour_cost();
        $data['carrying_cost'] = $this->pos_ses_lib->get_carrying_cost();
        $data['other_cost'] = $this->pos_ses_lib->get_other_cost();
        $data['discount'] = $this->pos_ses_lib->get_discount();
        $data['total'] = $total = $this->pos_ses_lib->get_total();
        $data['customer_id'] = $this->pos_ses_lib->get_customer();
        if(is_numeric($data['customer_id']) && $data['customer_id'] > 0){
            $data['customer_info'] = $this->Sales_model->get_customer_info($data['customer_id']);
            if(empty($data['customer_info'])){ 
                $this->pos_ses_lib->remove_customer();
                $data['customer_id'] = -1;
                unset($data['customer_info']);
                $this->session->set_flashdata('error', 'Please select a valid customer.');
            }
            else{
                if($data['customer_info']->balance > 0 && $data['due_collect'] == 'yes'){
    				if($data['mode'] == 'sale')
    					$data['total'] += $data['customer_info']->balance;
    				elseif($data['mode'] == 'sale_return')
    					$data['total'] -= $data['customer_info']->balance;
                }   
            }
            // if(@$_SESSION['return_amount_'.$data['customer_id']] > 0){
                // $this->pos_ses_lib->add_payment('Return', $_SESSION['return_amount_'.$data['customer_id']]);
            // }
                
        }

        $data['payments_total'] = $this->pos_ses_lib->get_payments_total();
        if($data['payments_total']>0){
            $data['get_payments'] = $this->pos_ses_lib->get_payments();
            if($data['customer_id'] > 0){
                if($data['due_collect'] == 'yes' && $data['customer_info']->balance > 0){
                    $rest_amount = $data['payments_total'] - $total;
                    if($data['customer_info']->balance >= $rest_amount){
                        $data['get_payments'][] = $this->pos_ses_lib->add_temp_payments('Collection', $rest_amount);
                    }
                    else{
                        $data['get_payments'][] = $this->pos_ses_lib->add_temp_payments('Collection', $data['customer_info']->balance);
                    }
                }
            }

            if(($data['payments_total'] - $data['total'] ) > 0){
                $data['get_payments'][] = $this->pos_ses_lib->add_temp_payments('Change Amount', ($data['total'] - $data['payments_total']));
            }
        }
        $data['tab'] = $this->pos_ses_lib->get_tab();

        $this->pos_ses_lib->remove_tab();
        $this->load->view('_parts/header', $head);
        $this->load->view('sale/register', $data);
        $this->load->view('_parts/footer');
    }

    public function item_search()
    {
        $suggestions = array();
        $search = $this->input->get('term') != '' ? $this->input->get('term') : NULL;
        $mode = $this->pos_ses_lib->get_mode();
        $suggestions = array_merge($suggestions, $this->Sales_model->get_search_suggestions($mode, $search, array('is_delete' => 1)));
        echo json_encode($suggestions);
    }
    
    public function change_mode()
    {
        $mode = $this->input->post('mode');
        $this->pos_ses_lib->set_mode($mode);
        redirect('admin/sale');
    }
    public function add()
    {
        $product_id = $order_id = $this->input->post('product_code');
        $mode = $this->pos_ses_lib->get_mode();
        if($mode == 'sale_print_invoice'){
            redirect('admin/sale/print_inv/'.$order_id);
        }
        elseif($mode == 'sale_update'){
            redirect('admin/sale/update_inv/'.$order_id);
        }
        elseif($mode == 'sale' || $mode == 'sale_return'){
            $data = array();
            $quantity = 1;
            if(!$this->pos_ses_lib->add_item($product_id, $quantity))
            {
                $this->session->set_flashdata('error', 'Please select a valid product.');
            }

            redirect('admin/sale');
        }
    }
    public function multi_add()
    {
        $product_ids = $this->input->post('$product_ids');
        // var_dump($product_ids);
        file_put_contents('ids.txt', json_encode($product_ids));
        // exit();
        $mode = $this->pos_ses_lib->get_mode();
        if($mode == 'sale'){
            $data = array();
            $quantity = 1;
            for($i=0; $i<count($product_ids); $i++){
                if(!$this->pos_ses_lib->add_item($product_ids[$i], $quantity))
                {
                    $this->session->set_flashdata('error', 'Please select a valid product.');
                    return false;
                }
            }
            return true;
        }
    }

    public function edit_item($item_id)
	{
		$data = array();

		$this->form_validation->set_rules('price', 'Item price', 'required|trim|callback_numeric');
		$this->form_validation->set_rules('quantity', 'Item quantity', 'required|trim|callback_numeric');
		$this->form_validation->set_rules('size', 'Item Size', 'trim');
		$this->form_validation->set_rules('item_name', 'Item name', 'required|trim|callback_numeric');
		$this->form_validation->set_rules('description', 'Item description', 'trim|callback_numeric');

		$item_name = $this->input->post('item_name');
		$description = $this->input->post('description');
		$serialnumber = $this->input->post('serialnumber');
		$price = $this->input->post('price');
		$quantity = $this->input->post('quantity');
		$size = $this->input->post('size');
		
        if($this->form_validation->run() == FALSE)
		{
            $this->pos_ses_lib->edit_item($item_id, $item_name, $serialnumber, $quantity, $price, $description, $size);
            $this->pos_ses_lib->set_tab($this->input->post('tab'));
        }
        // echo "validation error";
        redirect('admin/sale');
	}

    public function remove_item($item_number)
    {
        $this->pos_ses_lib->delete_item($item_number);
        redirect('admin/sale');
    }

    public function customer_search()
    {
        $suggestions = array();
        $receipt = $search = $this->input->get('term') != '' ? $this->input->get('term') : NULL;
        $suggestions = array_merge($suggestions, $this->Sales_model->get_customer_search_suggestions($search));
        echo json_encode($suggestions);
    }

    public function add_customer()
    {
        $customer_id = $this->input->post('customer_id');

        $this->pos_ses_lib->set_customer($customer_id);
        $this->pos_ses_lib->set_tab($this->input->post('tab'));
        redirect('admin/sale');
    }

    public function add_new_customer()
    {
        if(isset($_POST['customer_name']) && isset($_POST['customer_mobile']) && isset($_POST['addnewcus']))
        {
            $id = $this->Sales_model->add_new_customer($_POST['customer_name'], $_POST['customer_mobile']);
            if($id > 0)
                $this->pos_ses_lib->set_customer($id);
        }
        
        redirect('admin/sale');
    }
    
    public function remove_customer()
    {
        $this->pos_ses_lib->remove_customer();
        redirect('admin/sale');
    }

    public function inv_date()
    {
        $inv_date = $this->input->post('inv_date');

        $this->pos_ses_lib->set_inv_date($inv_date);
        $this->pos_ses_lib->set_tab($this->input->post('tab'));
        redirect('admin/sale');
    }

    public function due_collect()
    {
        $due_collect = $this->input->post('due_collect') == 'yes' ? 'yes' : 'no';
        $this->pos_ses_lib->set_due_collect($due_collect);
        redirect('admin/sale');            
    }
    
    public function labour_cost()
    {
        $labour_cost = $this->input->post('labour_cost');

        $this->pos_ses_lib->set_labour_cost($labour_cost);
        $this->pos_ses_lib->set_tab($this->input->post('tab'));
        redirect('admin/sale');            
    }
	
	public function carrying_cost()
    {
        $carrying_cost = $this->input->post('carrying_cost');

        $this->pos_ses_lib->set_carrying_cost($carrying_cost);
        $this->pos_ses_lib->set_tab($this->input->post('tab'));
        redirect('admin/sale');            
    }
    
    public function other_cost()
    {
        $other_cost = $this->input->post('other_cost');

        $this->pos_ses_lib->set_other_cost($other_cost);
        $this->pos_ses_lib->set_tab($this->input->post('tab'));
        redirect('admin/sale');            
    }
    
    public function discount()
    {
        $discount = $this->input->post('discount');

        $this->pos_ses_lib->set_discount($discount);
        $this->pos_ses_lib->set_tab($this->input->post('tab'));
        redirect('admin/sale');
    }

    public function add_payment()
    {
        $data = array();

		$payment_type = $this->input->post('payment_type');
		$this->form_validation->set_rules('amount_tendered', 'lang:sales_amount_tendered', 'trim|required');
		
		if($this->form_validation->run() !== FALSE)
		{
			$amount_tendered = $this->input->post('amount_tendered');
            $this->pos_ses_lib->add_payment($payment_type, $amount_tendered);
        }
        redirect('admin/sale');
    }

    public function delete_payment($payment_id)
	{
		$this->pos_ses_lib->delete_payment($payment_id);

		redirect('admin/sale');
    }

    /**
	 * This is used to cancel a suspended pos sale, quote.
	 * Completed sales (POS Sales or Invoiced Sales) can not be removed from the system
	 * Work orders can be canceled but are not physically removed from the sales history
	 */
	public function cancel()
	{
		$this->pos_ses_lib->clear_all();
		redirect('admin/sale');
    }

    public function completed()
    {
        $data['mode'] = $this->pos_ses_lib->get_mode();
        $data['user_id'] = $_SESSION['logged_user_id'];
        $data['inv_date'] = $this->pos_ses_lib->get_inv_date();
        $data['items'] = $this->pos_ses_lib->get_cart();
        $data['sdubtotal'] = $this->pos_ses_lib->get_subtotal();
        $data['due_collect'] = $this->pos_ses_lib->get_due_collect();
        $data['labour_cost'] = $this->pos_ses_lib->get_labour_cost();
        $data['carrying_cost'] = $this->pos_ses_lib->get_carrying_cost();
        $data['other_cost'] = $this->pos_ses_lib->get_other_cost();
        $data['discount'] = $this->pos_ses_lib->get_discount();
        $data['total'] = $total = $this->pos_ses_lib->get_total();
        $data['customer_id'] = $this->pos_ses_lib->get_customer();
        if($data['customer_id'] > 0){
            $data['customer_info'] = $this->Sales_model->get_customer_info($data['customer_id']);
            if($data['customer_info']->balance > 0 && $data['due_collect'] == 'yes')
                $total += $data['customer_info']->balance;
        }
        else{
            $this->session->set_flashdata('error', 'Please select a customer.');
            redirect('admin/sale');
        }
        $data['payments_total'] = $this->pos_ses_lib->get_payments_total();
        if($data['payments_total']>0){
            $data['get_payments'] = $this->pos_ses_lib->get_payments();

            if($data['due_collect'] == 'yes' && $data['customer_info']->balance > 0){
                $rest_amount = $data['payments_total'] - $data['total'];
                if($data['customer_info']->balance >= $rest_amount){
                    $data['get_payments'][] = $this->pos_ses_lib->add_temp_payments('Collection', $rest_amount);
                }
                else{
                    $data['get_payments'][] = $this->pos_ses_lib->add_temp_payments('Collection', $data['customer_info']->balance);
                }
            }

            if(($data['payments_total'] - $total ) > 0){
                $data['get_payments'][] = $this->pos_ses_lib->add_temp_payments('Change Amount', ($total - $data['payments_total']));
            }
        }
        // if($data['mode'] == 'sales_order'){

        // }
        if($data['mode'] == 'sale' && $data['total'] <= $data['payments_total']){
            $order_id = $this->Sales_model->setOrder($data);
            if($order_id){
                $this->pos_ses_lib->clear_all();
		        redirect('admin/sale/print_inv/'.$order_id);
            }
            else{
                $this->session->set_flashdata('error', 'Order not completed');
                redirect('admin/sale');
            }
        }
        else{
            $this->session->set_flashdata('error', 'Payment not completed');
            redirect('admin/sale');
        }
    }
    
    public function print_inv($order_id)
    {
        $this->login_check();
        $data = array();
        $head = array();
        $head['title'] = 'Administration - Sales Invoice';
        $head['description'] = 'Sales Invoice';
        $head['keywords'] = '';

        $data['page_width'] = $this->Home_admin_model->getValueStore('page_width');
        $data['printer_type'] = $this->Home_admin_model->getValueStore('printer_type');
        $data['details_img'] = $this->Home_admin_model->getValueStore('details_img');
        $data['logo_in'] = $this->Home_admin_model->getValueStore('logo_in');
        
        $data['inv_addLine_1'] = $this->Home_admin_model->getValueStore('inv_addLine_1');
        $data['inv_addLine_2'] = $this->Home_admin_model->getValueStore('inv_addLine_2');
        $data['inv_addLine_3'] = $this->Home_admin_model->getValueStore('inv_addLine_3');
        $data['inv_addLine_4'] = $this->Home_admin_model->getValueStore('inv_addLine_4');
        
        $data['invDesShow'] = $this->Home_admin_model->getValueStore('invDesShow');

        $data['products_lang'] = $products_lang = $this->session->userdata('admin_lang_products');
        $data['order'] = $this->Sales_model->getOrder($order_id);
        $data['customer_info'] = $this->Sales_model->get_customer_info($data['order']['customer_id']);
        $data['barcode'] = $this->set_barcode($data['order']['order_id']);
        $data['nf'] = 2;
        $this->load->library('Inword');
        $data['iw'] = $this->inword->numberToStr($data['order']['total']);
        $this->load->view('_parts/header', $head);
        $this->load->view('sale/invoice', $data);
        $this->load->view('_parts/footer');
    }
	
	public function print_order($order_id)
    {
        $this->login_check();
        $data = array();
        $head = array();
        $head['title'] = 'Administration - Sales Invoice';
        $head['description'] = 'Sales Invoice';
        $head['keywords'] = '';

        $data['page_width'] = $this->Home_admin_model->getValueStore('page_width');
        $data['printer_type'] = $this->Home_admin_model->getValueStore('printer_type');
        $data['details_img'] = $this->Home_admin_model->getValueStore('details_img');
        $data['logo_in'] = $this->Home_admin_model->getValueStore('logo_in');
        $data['smsApi'] = $this->Home_admin_model->getValueStore('smsApi');

        $data['products_lang'] = $products_lang = $this->session->userdata('admin_lang_products');
        $data['order'] = $this->Sales_model->getOrder($order_id);
        $data['customer_info'] = $this->Sales_model->get_customer_info($data['order']['customer_id']);
        // print_r($data);
        if(!empty($data['smsApi']) && !empty($data['customer_info']->phone)){
            $companyName = $this->Home_admin_model->getValueStore('companyName');
            $name = !empty($data['customer_info']->name) ? $data['customer_info']->name : 'Sir';
            $this->sms_lib->toSms($data['customer_info']->phone, 'Dear '.$name.'! Your recent purchase has been successfully processed. Invoice No: '.$data['order']['order_id'].'. Total Amount: BDT '.$data['order']['total'].'. Thank you for choosing our services! - '.$companyName);
        }
        $data['barcode'] = $this->set_barcode($data['order']['order_id']);
        $data['nf'] = 2;
        $this->load->view('sale/invoice_pos', $data);
    }
    
    public function return_item($order_id)
    {
        $this->login_check();
        $data = array();
        $head = array();
        $head['title'] = 'Administration - Sales Invoice';
        $head['description'] = 'Sales Return';
        $head['keywords'] = '';
        
        if(isset($_POST["return_btn"])){
			$_POST['salesReturn'] = $this->Home_admin_model->getValueStore('salesReturn');
			$data['inv_status'] = $this->Sales_model->return_trans($order_id, $_POST);
        }
        if(isset($_POST["delete_btn"])){
            $data['inv_status'] = $this->Sales_model->void_trans($order_id);
        }
        $data['products_lang'] = $products_lang = $this->session->userdata('admin_lang_products');
        $data['order'] = $this->Sales_model->getOrder($order_id);
        $data['customer_info'] = $this->Sales_model->get_customer_info($data['order']['customer_id']);
        $data['barcode'] = $this->set_barcode($data['order']['order_id']);
        $data['nf'] = 2;
        $this->load->view('_parts/header', $head);
        $this->load->view('sale/sales_return', $data);
        $this->load->view('_parts/footer');
    }
	
	public function return_items()
	{
		$data['mode'] = $this->pos_ses_lib->get_mode();
        $data['user_id'] = $_SESSION['logged_user_id'];
        $data['inv_date'] = $this->pos_ses_lib->get_inv_date();
        $data['items'] = $this->pos_ses_lib->get_cart();
        $data['sdubtotal'] = $this->pos_ses_lib->get_subtotal();
        $data['due_collect'] = $this->pos_ses_lib->get_due_collect();
        $data['labour_cost'] = $this->pos_ses_lib->get_labour_cost();
        $data['carrying_cost'] = $this->pos_ses_lib->get_carrying_cost();
        $data['other_cost'] = $this->pos_ses_lib->get_other_cost();
        $data['discount'] = $this->pos_ses_lib->get_discount();
        $data['total'] = $total = $this->pos_ses_lib->get_total();
        $data['customer_id'] = $this->pos_ses_lib->get_customer();
		if($data['customer_id'] > 0){
            $data['customer_info'] = $this->Sales_model->get_customer_info($data['customer_id']);
        }
		else{
			$this->session->set_flashdata('error', 'Customer not set.');
            redirect('admin/sale');
		}

		$data['payments_total'] = $this->pos_ses_lib->get_payments_total();
		if($data['payments_total']>0){
            $data['get_payments'] = $this->pos_ses_lib->get_payments();
		}
		
		if($data['mode'] == 'sale_return' && $data['total'] <= $data['payments_total']){
            $order_id = $this->Sales_model->return_items($data);
            if($order_id){
                $this->pos_ses_lib->clear_all();
		        redirect('admin/sale/print_inv/'.$order_id);
            }
            else{
                $this->session->set_flashdata('error', 'Order not completed');
                redirect('admin/sale');
            }
        }
        else{
            $this->session->set_flashdata('error', 'Payment not completed');
            redirect('admin/sale');
        }
	}

    public function update_inv($order_id)
    {
        $this->login_check();
        $data = array();
        $head = array();
        $head['title'] = 'Administration - Sales Invoice';
        $head['description'] = 'Sales Invoice';
        $head['keywords'] = '';
        $data['order'] = $this->Sales_model->getOrder($order_id);
        
        if(isset($_POST["update_inv"])){
            $_POST['user_id'] = $_SESSION['logged_user_id'];
            $_POST['logged_in'] = $_SESSION['logged_in'];
            for($i=0; $i<count($_POST['payment_type']); $i++){
                $p = explode('-', $_POST['payment_type'][$i]);
                $_POST['get_payments'][] = array('payment_type' => $p[0], 'payment_title'=> $p[2], 'group'=>$p[1], 'payment_amount' => $_POST['price'][$i]);
            }
            if($this->Sales_model->updateOrder($data['order'], $_POST) === false){
                $this->session->set_flashdata('error', 'Invoice update error!!!');
                $this->saveHistory('Invoice update error - Invoice - '. $order_id .' User: ' . $_POST['logged_in'] . ' and ID: ' . $_POST['user_id'] . ' at '.date('Y-m-d H:i:s'));
            }else{
                $this->session->set_flashdata('success', 'Invoice update successfully!!!');
                $this->saveHistory('Invoice update successfully Invoice - '. $order_id .' User: ' . $_POST['logged_in'] . ' and ID: ' . $_POST['user_id'] . ' at '.date('Y-m-d H:i:s'));
                redirect('admin/sale');
            }
        }
        elseif(isset($_POST["void_inv"])){
            $_POST['user_id'] = $_SESSION['logged_user_id'];
            $_POST['logged_in'] = $_SESSION['logged_in'];
            if($this->Sales_model->void_trans($order_id) === false){
                $this->session->set_flashdata('error', 'Invoice delete error!!!');
                $this->saveHistory('Invoice delete error - Invoice - '. $order_id .' User: ' . $_POST['logged_in'] . ' and ID: ' . $_POST['user_id'] . ' at '.date('Y-m-d H:i:s'));
            }else{
                $this->session->set_flashdata('success', 'Invoice delete successfully!!!');
                $this->saveHistory('Invoice delete successfully Invoice - '. $order_id .' User: ' . $_POST['logged_in'] . ' and ID: ' . $_POST['user_id'] . ' at '.date('Y-m-d H:i:s'));
                redirect('admin/sale');
            }
        }
        
        $data['payment_type'] = $this->pos_ses_lib->get_account_list();

        $data['page_width'] = $this->Home_admin_model->getValueStore('page_width');
        $data['printer_type'] = $this->Home_admin_model->getValueStore('printer_type');
        $data['details_img'] = $this->Home_admin_model->getValueStore('details_img');
        $data['logo_in'] = $this->Home_admin_model->getValueStore('logo_in');
        
        $data['inv_addLine_1'] = $this->Home_admin_model->getValueStore('inv_addLine_1');
        $data['inv_addLine_2'] = $this->Home_admin_model->getValueStore('inv_addLine_2');
        $data['inv_addLine_3'] = $this->Home_admin_model->getValueStore('inv_addLine_3');
        $data['inv_addLine_4'] = $this->Home_admin_model->getValueStore('inv_addLine_4');
        
        $data['invDesShow'] = $this->Home_admin_model->getValueStore('invDesShow');

        $data['products_lang'] = $products_lang = $this->session->userdata('admin_lang_products');
        $data['customer_info'] = $this->Sales_model->get_customer_info($data['order']['customer_id']);
        $data['barcode'] = $this->set_barcode($data['order']['order_id']);
        $data['nf'] = 2;
        $this->load->library('Inword');
        $data['iw'] = $this->inword->numberToStr($data['order']['total']);
        $this->load->view('_parts/header', $head);
        $this->load->view('sale/update_inv', $data);
        $this->load->view('_parts/footer');
    }
}