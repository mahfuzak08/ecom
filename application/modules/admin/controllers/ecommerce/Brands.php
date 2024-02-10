<?php

/*
 * @Author:    Md. Mahfuzur Rahman
 *  Gitgub:    https://github.com/mahfuzak08/
 */
if (!defined('BASEPATH')) {
    exit('No direct script access allowed');
}

class Brands extends ADMIN_Controller
{

    public function __construct()
    {
        parent::__construct();
        $this->load->model(array('Brands_model', 'Languages_model'));
    }

    public function index()
    {
        $this->login_check();
        $data = array();
        $head = array();
        $head['title'] = 'Administration - Brands';
        $head['description'] = '!';
        $head['keywords'] = '';

        if (isset($_POST['add_brand'])) {
            $this->Brands_model->setBrand($_POST);
            redirect('admin/brands');
        }

        if (isset($_GET['delete'])) {
            $this->Brands_model->deleteBrand($_GET['delete']);
            redirect('admin/brands');
        }
        $data['languages'] = $this->Languages_model->getLanguages();
        $data['brands'] = $this->Brands_model->getBrands();

        $this->load->view('_parts/header', $head);
        $this->load->view('ecommerce/brands', $data);
        $this->load->view('_parts/footer');
        $this->saveHistory('Go to brands page');
    }

}
