<?php

/*
 * @Author:    Md. Mahfuzur Rahman
 *  Gitgub:    https://github.com/mahfuzak08/
 */
if (!defined('BASEPATH')) {
    exit('No direct script access allowed');
}

class Barcode extends ADMIN_Controller
{

    public function __construct()
    {
        parent::__construct();
        $this->load->model(array('Products_model'));
    }

    public function index()
    {
        $this->login_check();
        $data = array();
        $head = array();
        $head['title'] = 'Administration - Barcode';
        $head['description'] = 'Barcode';
        $head['keywords'] = '';

        if (isset($_POST['print_preview'])) {
            $data['barcode'] = $this->set_barcode($_POST['barcode']);
            $data['product'] = $this->Products_model->getProductPriceGivenBarcode($_GET['bc']);
        }
		if(isset($_GET['bc'])) $_POST['barcode'] = $_GET['bc'];
		
        $this->load->view('_parts/header', $head);
        $this->load->view('ecommerce/barcode', $data);
        $this->load->view('_parts/footer');
        $this->saveHistory('Go to barcode page');
    }

}
