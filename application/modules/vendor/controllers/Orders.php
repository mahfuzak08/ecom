<?php

/*
 * @Author:    Md. Mahfuzur Rahman
 *  Gitgub:    https://github.com/mahfuzak08/
 */
if (!defined('BASEPATH')) {
    exit('No direct script access allowed');
}

class Orders extends VENDOR_Controller
{

    private $num_rows = 20;

    public function __construct()
    {
        parent::__construct();
        $this->load->model(array('Orders_model', 'Products_model'));
    }

    public function index($page = 0)
    {

        $data = array();
        $head = array();
        $head['title'] = lang('vendor_orders');
        $head['description'] = lang('vendor_orders');
        $head['keywords'] = '';
        if (isset($_POST['filter_by_date'])){
            $data['fd'] = $_POST['valid_from_date'];
            $data['td'] = $_POST['valid_to_date'];
            $fd = isset($_POST['valid_from_date'])?strtotime($_POST['valid_from_date']):strtotime(date('y-m-d'));
            $td = isset($_POST['valid_from_date'])?strtotime($_POST['valid_to_date']):strtotime(date('y-m-d'));
            $rowscount = $this->Orders_model->ordersCount($this->vendor_id, $fd, $td);
            $data['orders'] = $this->Orders_model->orders($this->num_rows, $page, $this->vendor_id, $fd, $td);
            // $data['links_pagination'] = pagination('admin/orders', $rowscount, $this->num_rows, 3);
        }else{
            $rowscount = $this->Orders_model->ordersCount($this->vendor_id);
            $data['orders'] = $this->Orders_model->orders($this->num_rows, $page, $this->vendor_id);
        }
        $this->load->view('_parts/header', $head);
        $this->load->view('orders', $data);
        $this->load->view('_parts/footer');
    }

    public function getProductInfo($product_id, $vendor_id)
    {
        return $this->Products_model->getOneProduct($product_id, $vendor_id);
    }

    public function changeOrdersOrderStatus()
    {
        $result = $this->Orders_model->changeOrderStatus($_POST['the_id'], $_POST['to_status']);
        if ($result == false) {
            echo '0';
        } else {
            echo '1';
        }
    }

    public function wish()
    {
        $data = array();
        $head = array();
        $head['title'] = lang('vendor_wish_list');
        $head['description'] = lang('vendor_wish_list');
        $head['keywords'] = '';

        $data['wishs'] = $this->Orders_model->wishs($_SESSION['logged_vendor_id']);
        
        $this->load->view('_parts/header', $head);
        $this->load->view('wishs', $data);
        $this->load->view('_parts/footer');
    }

}
