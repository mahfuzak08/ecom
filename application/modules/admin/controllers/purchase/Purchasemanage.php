<?php

/*
 * @Author:    Md. Mahfuzur Rahman
 *  Gitgub:    https://github.com/mahfuzak08/
 */
if (!defined('BASEPATH')) {
    exit('No direct script access allowed');
}

class Purchasemanage extends ADMIN_Controller
{

    private $num_rows = 10;

    public function __construct()
    {
        parent::__construct();
        $this->load->model(array('Purchase_model', 'Sales_model', 'Customer_model', 'Languages_model', 'Categories_model'));
        $this->load->library('pos_ses_lib');
    }

    public function index($is = 0)
    {
        $this->login_check();
        $data = array();
        $head = array();
        $head['title'] = 'Administration - Purchase';
        $head['description'] = 'Purchase Register';
        $head['keywords'] = '';

        $data['products_lang'] = $products_lang = $this->session->userdata('admin_lang_products');
        
        // Load data
        $data['mode'] = $this->pos_ses_lib->get_mode();
        if(strpos($data['mode'], 'sale') !== false){
            $this->pos_ses_lib->clear_all();
            $this->pos_ses_lib->set_mode('purchase');
            $data['mode'] = 'purchase';
        }
        $data['payment_type'] = $this->pos_ses_lib->get_account_list();
        if(count($data['payment_type']) == 0 ){
            $data['payment_type'] = $this->Purchase_model->get_payment_type();
            $this->pos_ses_lib->set_account_list($data['payment_type']);
        }
        // print_r($data['payment_type']);
        $data['inv_date'] = $this->pos_ses_lib->get_inv_date();
        $data['items'] = $this->pos_ses_lib->get_cart();
        $data['subtotal'] = $this->pos_ses_lib->get_subtotal();
        $data['due_collect'] = $this->pos_ses_lib->get_due_collect();
        $data['labour_cost'] = $this->pos_ses_lib->get_labour_cost();
        $data['carrying_cost'] = $this->pos_ses_lib->get_carrying_cost();
        $data['other_cost'] = $this->pos_ses_lib->get_other_cost();
        $data['discount'] = $this->pos_ses_lib->get_discount();
        $data['total'] = $total = $this->pos_ses_lib->get_total();
        $data['supplier_id'] = $this->pos_ses_lib->get_supplier();
        if($data['supplier_id'] > 0){
            $data['supplier_info'] = $this->Purchase_model->get_supplier_info($data['supplier_id']);
            if($data['supplier_info']->balance > 0 && $data['due_collect'] == 'yes' && $data['mode'] == 'purchase')
                $data['total'] += $data['supplier_info']->balance;
        }

        $data['payments_total'] = $this->pos_ses_lib->get_payments_total();
        if($data['payments_total']>0){
            $data['get_payments'] = $this->pos_ses_lib->get_payments();
            if($data['supplier_id'] > 0){
                if($data['due_collect'] == 'yes' && $data['supplier_info']->balance > 0){
                    $rest_amount = $data['payments_total'] - $total;
                    if($data['supplier_info']->balance >= $rest_amount){
                        $data['get_payments'][] = $this->pos_ses_lib->add_temp_payments('Collection', $rest_amount);
                    }
                    else{
                        $data['get_payments'][] = $this->pos_ses_lib->add_temp_payments('Collection', $data['supplier_info']->balance);
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
        $this->load->view('purchase/register', $data);
        $this->load->view('_parts/footer');
    }

    public function item_search()
    {
        $suggestions = array();
        $search = $this->input->get('term') != '' ? $this->input->get('term') : NULL;
        $mode = $this->pos_ses_lib->get_mode();
        $suggestions = array_merge($suggestions, $this->Purchase_model->get_search_suggestions($mode, $search, array('is_delete' => 1)));
        echo json_encode($suggestions);
    }
    
    public function change_mode()
    {
        $mode = $this->input->post('mode');
        $this->pos_ses_lib->set_mode($mode);
        redirect('admin/purchase');
    }
    public function add()
    {
        $product_id = $order_id = $this->input->post('product_code');
        $mode = $this->pos_ses_lib->get_mode();
        if($mode == 'search_purchase_invoice'){
            redirect('admin/purchase/print_inv/'.$order_id);
        }
		// elseif($mode == 'purchase_return'){
            // redirect('admin/purchase/return_item/'.$order_id);
        // }
        elseif($mode == 'purchase' || $mode == 'purchase_return'){
            $data = array();
            $quantity = 1;
            if(!$this->pos_ses_lib->add_item($product_id, $quantity))
            {
                echo "Add item error...";
            }

            redirect('admin/purchase');
        }
    }

    public function edit_item($item_id)
	{
		$data = array();

		$this->form_validation->set_rules('price', 'Item price', 'required|trim|callback_numeric');
		$this->form_validation->set_rules('quantity', 'Item quantity', 'required|trim|callback_numeric');
		$this->form_validation->set_rules('item_name', 'Item name', 'required|trim|callback_numeric');
		$this->form_validation->set_rules('description', 'Item description', 'trim|callback_numeric');

		$item_name = $this->input->post('item_name');
		$description = $this->input->post('description');
		$serialnumber = $this->input->post('serialnumber');
		$price = $this->input->post('price');
		$quantity = $this->input->post('quantity');
		
        if($this->form_validation->run() == FALSE)
		{
            $this->pos_ses_lib->edit_item($item_id, $item_name, $serialnumber, $quantity, $price, $description);
            $this->pos_ses_lib->set_tab($this->input->post('tab'));
        }
        // echo "validation error";
        redirect('admin/purchase');
	}

    public function remove_item($item_number)
    {
        $this->pos_ses_lib->delete_item($item_number);
        redirect('admin/purchase');
    }

    public function supplier_search()
    {
        $suggestions = array();
        $receipt = $search = $this->input->get('term') != '' ? $this->input->get('term') : NULL;
        $suggestions = array_merge($suggestions, $this->Purchase_model->get_supplier_search_suggestions($search));
        echo json_encode($suggestions);
    }
    
    public function add_supplier()
    {
        $supplier_id = $this->input->post('supplier_id');

        $this->pos_ses_lib->set_supplier($supplier_id);
        $this->pos_ses_lib->set_tab($this->input->post('tab'));
        redirect('admin/purchase');
    }

    public function add_new_supplier()
    {
        if(isset($_POST['supplier_name']) && isset($_POST['supplier_mobile']) && isset($_POST['addnewcus']))
        {
            $id = $this->Purchase_model->add_new_supplier($_POST['supplier_name'], $_POST['supplier_mobile']);
            if($id > 0)
                $this->pos_ses_lib->set_supplier($id);
        }
        
        redirect('admin/purchase');
    }
    
    public function remove_supplier()
    {
        $this->pos_ses_lib->remove_supplier();
        redirect('admin/purchase');
    }

    public function inv_date()
    {
        $inv_date = $this->input->post('inv_date');

        $this->pos_ses_lib->set_inv_date($inv_date);
        $this->pos_ses_lib->set_tab($this->input->post('tab'));
        redirect('admin/purchase');
    }

    public function due_collect()
    {
        $due_collect = $this->input->post('due_collect') == 'yes' ? 'yes' : 'no';
        $this->pos_ses_lib->set_due_collect($due_collect);
        redirect('admin/purchase');            
    }
    
    public function labour_cost()
    {
        $labour_cost = $this->input->post('labour_cost');

        $this->pos_ses_lib->set_labour_cost($labour_cost);
        $this->pos_ses_lib->set_tab($this->input->post('tab'));
        redirect('admin/purchase');            
    }
	
	public function carrying_cost()
    {
        $carrying_cost = $this->input->post('carrying_cost');

        $this->pos_ses_lib->set_carrying_cost($carrying_cost);
        $this->pos_ses_lib->set_tab($this->input->post('tab'));
        redirect('admin/purchase');            
    }
	
	public function other_cost()
    {
        $other_cost = $this->input->post('other_cost');

        $this->pos_ses_lib->set_other_cost($other_cost);
        $this->pos_ses_lib->set_tab($this->input->post('tab'));
        redirect('admin/purchase');            
    }
    
    public function discount()
    {
        $discount = $this->input->post('discount');

        $this->pos_ses_lib->set_discount($discount);
        $this->pos_ses_lib->set_tab($this->input->post('tab'));
        redirect('admin/purchase');
    }

    public function add_payment()
    {
        $data = array();

		$payment_type = $this->input->post('payment_type');
		$this->form_validation->set_rules('amount_tendered', 'lang:sales_amount_tendered', 'trim|required');
		
		if($this->form_validation->run() !== FALSE)
		{
            $amount_tendered = $this->input->post('amount_tendered');
            $enough_balance = false;
            $ac_balance = $this->pos_ses_lib->get_account_list();
            foreach($ac_balance as $row){
                if($row->id == $payment_type){
                    if($row->type == 'Due'){
                        $enough_balance = true;
                    }
                    elseif($row->balance >= $amount_tendered){
                        $enough_balance = true;
                    }
                }
            }    
            if($enough_balance){
                $this->pos_ses_lib->add_payment($payment_type, $amount_tendered);
            }
            else{
                $this->session->set_flashdata('error', 'Not enough balance in this account.');
                redirect('admin/purchase');        
            }
        }
        redirect('admin/purchase');
    }

    public function delete_payment($payment_id)
	{
		$this->pos_ses_lib->delete_payment($payment_id);

		redirect('admin/purchase');
	}

    /**
	 * This is used to cancel a suspended pos sale, quote.
	 * Completed sales (POS Sales or Invoiced Sales) can not be removed from the system
	 * Work orders can be canceled but are not physically removed from the sales history
	 */
	public function cancel()
	{
		$this->pos_ses_lib->clear_all();
		redirect('admin/purchase');
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
        $data['supplier_id'] = $this->pos_ses_lib->get_supplier();
        if($data['supplier_id'] > 0){
            $data['supplier_info'] = $this->Purchase_model->get_supplier_info($data['supplier_id']);
            if($data['supplier_info']->balance > 0 && $data['due_collect'] == 'yes' && $data['mode'] == 'purchase')
                $total += $data['supplier_info']->balance;
        }
        else{
            $this->session->set_flashdata('error', 'Please select a supplier.');
            redirect('admin/purchase');
        }
        $data['payments_total'] = $this->pos_ses_lib->get_payments_total();
        if($data['payments_total']>0){
            $data['get_payments'] = $this->pos_ses_lib->get_payments();

            if($data['due_collect'] == 'yes' && $data['supplier_info']->balance > 0){
                $rest_amount = $data['payments_total'] - $data['total'];
                if($data['supplier_info']->balance >= $rest_amount){
                    $data['get_payments'][] = $this->pos_ses_lib->add_temp_payments('Collection', $rest_amount);
                }
                else{
                    $data['get_payments'][] = $this->pos_ses_lib->add_temp_payments('Collection', $data['supplier_info']->balance);
                }
            }

            if(($data['payments_total'] - $total ) > 0){
                $data['get_payments'][] = $this->pos_ses_lib->add_temp_payments('Change Amount', ($total - $data['payments_total']));
            }
        }
        // if($data['mode'] == 'sales_order'){

        // }
        if($data['mode'] == 'purchase' && $data['total'] <= $data['payments_total']){
            $order_id = $this->Purchase_model->setOrder($data);
            if($order_id){
                $this->pos_ses_lib->clear_all();
		        redirect('admin/purchase/print_inv/'.$order_id);
            }
            else{
                $this->session->set_flashdata('error', 'Order not completed');
                redirect('admin/purchase');
            }
        }
        else{
            $this->session->set_flashdata('error', 'Payment not completed');
            redirect('admin/purchase');
        }
    }
    
    public function print_inv($order_id)
    {
        $this->login_check();
        $data = array();
        $head = array();
        $head['title'] = 'Administration - Purchase Invoice';
        $head['description'] = 'Purchase Invoice';
        $head['keywords'] = '';

        $data['products_lang'] = $products_lang = $this->session->userdata('admin_lang_products');
        $data['order'] = $this->Purchase_model->getOrder($order_id);
        $data['supplier_info'] = $this->Purchase_model->get_supplier_info($data['order']['supplier_id']);
        $data['barcode'] = $this->set_barcode($data['order']['order_id']);
        $data['nf'] = 2;
        $this->load->view('_parts/header', $head);
        $this->load->view('purchase/invoice', $data);
        $this->load->view('_parts/footer');
    }
    
	public function return_item($order_id)
    {
        $this->login_check();
        $data = array();
        $head = array();
        $head['title'] = 'Administration - Purchase Invoice';
        $head['description'] = 'Purchase Return';
        $head['keywords'] = '';
		
        if(isset($_POST["return_btn"])){
			// $_POST['purchaseReturn'] = $this->Home_admin_model->getValueStore('purchaseReturn');
			$_POST['purchaseReturn'] = 1;
			$data['inv_status'] = $this->Purchase_model->return_trans($order_id, $_POST);
        }
        if(isset($_POST["delete_btn"])){
            $data['inv_status'] = $this->Purchase_model->void_trans($order_id);
        }
        $data['products_lang'] = $products_lang = $this->session->userdata('admin_lang_products');
        $data['order'] = $this->Purchase_model->getOrder($order_id);
        $data['supplier_info'] = $this->Purchase_model->get_supplier_info($data['order']['supplier_id']);
        $data['barcode'] = $this->set_barcode($data['order']['order_id']);
        $data['nf'] = 2;
        $this->load->view('_parts/header', $head);
        $this->load->view('purchase/purchase_return', $data);
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
		$data['supplier_id'] = $this->pos_ses_lib->get_supplier();
		if($data['supplier_id'] > 0){
            $data['supplier_info'] = $this->Purchase_model->get_supplier_info($data['supplier_id']);
        }
        else{
            $this->session->set_flashdata('error', 'Please select a supplier.');
            redirect('admin/purchase');
        }
		
        $data['payments_total'] = $this->pos_ses_lib->get_payments_total();
        if($data['payments_total']>0){
            $data['get_payments'] = $this->pos_ses_lib->get_payments();
        }
		
        if($data['mode'] == 'purchase_return' && $data['total'] <= $data['payments_total']){
            $order_id = $this->Purchase_model->return_items($data);
            if($order_id){
                $this->pos_ses_lib->clear_all();
		        redirect('admin/purchase/print_inv/'.$order_id);
            }
            else{
                $this->session->set_flashdata('error', 'Order not completed');
                redirect('admin/purchase');
            }
        }
        else{
            $this->session->set_flashdata('error', 'Payment not completed'.'='. $data['total'] .'='. $data['payments_total']);
            redirect('admin/purchase');
        }
	}
	
	
}