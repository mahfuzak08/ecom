<?php

/*
 * @Author:    Md. Mahfuzur Rahman
 *  Gitgub:    https://github.com/mahfuzak08/
 */
if (!defined('BASEPATH')) {
    exit('No direct script access allowed');
}

class Location extends ADMIN_Controller
{

    public function __construct()
    {
        parent::__construct();
        $this->load->model('Location_model');
    }

    public function index()
    {
        $this->login_check();
        $data = array();
        $head = array();
        $head['title'] = 'Administration - Location';
        $head['description'] = '!';
        $head['keywords'] = '';

        if (isset($_POST['name']) && isset($_POST['cost'])) {
            $this->Location_model->setLocation($_POST['id'], $_POST['name'], $_POST['cost']);
            redirect('admin/location');
        }

        if (isset($_GET['delete'])) {
            $this->Location_model->deleteLocation($_GET['delete']);
            redirect('admin/location');
        }

        $data['location'] = $this->Location_model->getLocation();

        $this->load->view('_parts/header', $head);
        $this->load->view('ecommerce/location', $data);
        $this->load->view('_parts/footer');
        $this->saveHistory('Go to location page');
    }

}
