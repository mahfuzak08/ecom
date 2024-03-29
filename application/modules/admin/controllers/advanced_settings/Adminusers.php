<?php

/*
 * @Author:    Md. Mahfuzur Rahman
 *  Gitgub:    https://github.com/mahfuzak08/
 */
if (!defined('BASEPATH')) {
    exit('No direct script access allowed');
}

class Adminusers extends ADMIN_Controller
{

    public function __construct()
    {
        parent::__construct();
        $this->load->model('Admin_users_model');
    }

    public function index()
    {
        $this->login_check();
        if (isset($_GET['delete'])) {
            $this->Admin_users_model->deleteAdminUser($_GET['delete']);
            $this->session->set_flashdata('result_delete', 'User is deleted!');
            redirect('admin/adminusers');
        }
        if (isset($_GET['edit']) && !isset($_POST['username'])) {
            $_POST = $this->Admin_users_model->getAdminUsers($_GET['edit']);
        }
        if(isset($_GET['reactive'])){
            $this->Admin_users_model->reactiveUser($_GET['reactive']);
            $this->session->set_flashdata('result_delete', 'User is re-active successfully!');
            redirect('admin/adminusers');
        }
        $data = array();
        $head = array();
        $head['title'] = 'Administration - Admin Users';
        $head['description'] = '!';
        $head['keywords'] = '';
        $data['users'] = $this->Admin_users_model->getAdminUsers();
        $this->form_validation->set_rules('username', 'User', 'trim|required');
        if (isset($_POST['edit']) && $_POST['edit'] == 0) {
            $this->form_validation->set_rules('password', 'Password', 'trim|required');
        }
        if ($this->form_validation->run($this)) {
            $this->Admin_users_model->setAdminUser($_POST);
            $this->saveHistory('Create admin user - ' . $_POST['username']);
            redirect('admin/adminusers');
        }

        $this->load->view('_parts/header', $head);
        $this->load->view('advanced_settings/adminUsers', $data);
        $this->load->view('_parts/footer');
        $this->saveHistory('Go to Admin Users');
    }

}
