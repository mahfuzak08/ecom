<?php

/*
 * @Author:    Md. Mahfuzur Rahman
 *  Gitgub:    https://github.com/mahfuzak08/
 */
if (!defined('BASEPATH')) {
    exit('No direct script access allowed');
}

class Wishs extends ADMIN_Controller
{

    private $num_rows = 10;

    public function __construct()
    {
        parent::__construct();
        $this->load->library('SendMail');
        $this->load->model(array('Wish_model', 'Sales_model', 'Home_admin_model'));
    }

    public function index($id = 0)
    {
        $this->login_check();
        $data = array();
        $head = array();
        $head['title'] = 'Administration - Wish Lists';
        $head['description'] = 'Bit Lists';
        $head['keywords'] = '';

        $data['wishs'] = $this->Wish_model->wishs($id);
        
        $this->load->view('_parts/header', $head);
        $this->load->view('ecommerce/wishs', $data);
        $this->load->view('_parts/footer');
        if ($id == 0) {
            $this->saveHistory('Go to wish lists page');
        }
    }
    
    public function action_accept($id = 0)
    {
        $this->login_check();
        if($id>0){
            $data = array();
            $data['wishs'] = $this->Wish_model->wishs($id);
            $data['customer_info'] = $this->Sales_model->get_customer_info($data['wishs'][0]->customer_id);
            $data['user_id'] = $_SESSION['logged_user_id'];
            $order_id = $this->Wish_model->setOrder($data);
            $this->Wish_model->change_wish_list_status($id, 2);
            redirect('admin/wishs/print_inv/'.$order_id);
        }
        else{
            redirect('admin/wishs');
        }
    }
    
    public function action_reject($id = 0)
    {
        $this->login_check();
        $data = array();
        $this->Wish_model->change_wish_list_status($id, 3);
        redirect('admin/wishs');
    }

    public function print_inv($order_id)
    {
        $this->login_check();
        $data = array();
        $head = array();
        $head['title'] = 'Administration - Wish Lists';
        $head['description'] = 'Order from Bit';
        $head['keywords'] = '';

        $data['products_lang'] = $products_lang = $this->session->userdata('admin_lang_products');
        $data['order'] = $this->Sales_model->getOrder($order_id);
        $data['customer_info'] = $this->Sales_model->get_customer_info($data['order']['customer_id']);
        $data['barcode'] = $this->set_barcode($data['order']['order_id']);
        $data['nf'] = 2;
        // print_r($data);
        $this->load->view('_parts/header', $head);
        $this->load->view('ecommerce/wish_invoice', $data);
        $this->load->view('_parts/footer');
    }
    
    public function print_order_lists($fd, $td)
    {
        $this->login_check();
        $data = array();
        $head = array();
        $head['title'] = 'Administration - Orders';
        $head['description'] = '!';
        $head['keywords'] = '';

        $order_by = null;
        if (isset($_GET['order_by'])) {
            $order_by = $_GET['order_by'];
        }
        
        $fd = isset($fd)?strtotime($fd):strtotime(date('y-m-d'));
        $td = isset($fd)?strtotime($td):strtotime(date('y-m-d'));
        // $rowscount = $this->Orders_model->ordersCount(false, $fd, $td);
        $data['orders'] = $this->Orders_model->orders(5000, 0, $order_by, $fd, $td);
        // $data['links_pagination'] = pagination('admin/orders', $rowscount, $this->num_rows, 3);
        $data['fd'] = $fd;
        $data['td'] = $td;
        $data['sitelogo'] = $this->Home_admin_model->getValueStore('sitelogo');
        // $this->load->view('_parts/header', $head);
        $this->load->view('ecommerce/print_lists', $data);
        // $this->load->view('_parts/footer');
        $this->saveHistory('Go to order print');
    }

    public function changeOrdersOrderStatus()
    {
        $this->login_check();

        $result = false;
        $sendedVirtualProducts = true;
        // $virtualProducts = $this->Home_admin_model->getValueStore('virtualProducts');
        /*
         * If we want to use Virtual Products
         * Lets send email with download links to user email
         * In error logs will be saved if cant send email from PhpMailer
         */
        // if ($virtualProducts == 1) {
        //     if ($_POST['to_status'] == 1) {
        //         $sendedVirtualProducts = $this->sendVirtualProducts();
        //     }
        // }

        // if ($sendedVirtualProducts == true) {
            $result = $this->Orders_model->changeOrderStatus($_POST['the_id'], $_POST['to_status']);
        // }

        if ($result == true) {
            echo 1;
            // if($_POST['to_status'] == 3 && $_POST['to_status'] == 1){
            //     $this->sendSMSNotification($_POST['the_id'], $_POST['to_status']);
            // }
        } else {
            echo 0;
        }
        $this->saveHistory('Change status of Order Id ' . $_POST['the_id'] . ' to status ' . $_POST['to_status']);
    }

    private function sendVirtualProducts()
    {
        if(isset($_POST['products']) && $_POST['products'] != '') {
            $products = unserialize(html_entity_decode($_POST['products']));
            foreach ($products as $product_id => $product_quantity) {
                $productInfo = modules::run('admin/ecommerce/products/getProductInfo', $product_id);
                /*
                 * If is virtual product, lets send email to user
                 */
                if ($productInfo['virtual_products'] != null) {
                    if (!filter_var($_POST['userEmail'], FILTER_VALIDATE_EMAIL)) {
                        log_message('error', 'Ivalid customer email address! Cant send him virtual products!');
                        return false;
                    }
                    $result = $this->sendmail->sendTo($_POST['userEmail'], 'Dear Customer', 'Virtual products', $productInfo['virtual_products']);
                    return $result;
                }
            }
            return true;
        }
    }
    
    private function sendSMSNotification($order_id, $sms_type)
    {
        $data = $this->Orders_model->getOrderInfo($order_id);
        if($sms_type == 1)
            $this->sms_lib->sendSMS($data['phone'], "Your order #".$data['order_id']." is on the way for delivery. \r\nThanks\r\n".DISPLAY_NAME."\r\nHelp: ".MOBILE_NUMBER);
        else if($sms_type == 3)
            $this->sms_lib->sendSMS($data['phone'], "Your order #".$data['order_id']." has been placed properly. \r\nThanks\r\n".DISPLAY_NAME."\r\nHelp: ".MOBILE_NUMBER);
    }

}
