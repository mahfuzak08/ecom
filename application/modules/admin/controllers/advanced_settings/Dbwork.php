<?php

/*
 * @Author:    Md. Mahfuzur Rahman
 *  Gitgub:    https://github.com/mahfuzak08/
 */
if (!defined('BASEPATH')) {
    exit('No direct script access allowed');
}

class Dbwork extends ADMIN_Controller
{

    public function __construct()
    {
        parent::__construct();
        $this->load->model('Dbwork_model');
    }

    public function index()
    {
        $this->login_check();
        if (isset($_GET['deleteTrans'])) {
            if (isset($_GET['deleteProductsAlso'])){
                $this->Dbwork_model->delete_trans($_GET['deleteTrans'], $_GET['deleteProductsAlso']);
                $this->session->set_flashdata('result_delete', 'All transectional data and products deleted successfully!');
            }else{
                $this->Dbwork_model->delete_trans($_GET['deleteTrans']);
                $this->session->set_flashdata('result_delete', 'All transectional data deleted successfully!');
            }
            redirect('admin/dbwork');
        }
        if (isset($_GET['backup']) && $_GET['backup'] == 'all') {
            $this->Dbwork_model->full_db_backup();
            $this->session->set_flashdata('result_add', 'Full database backup successfully!');
            redirect('admin/dbwork');
        }
		if (isset($_GET['backup']) && $_GET['backup'] == 'today') {
            $this->Dbwork_model->todays_backup();
            // $this->session->set_flashdata('result_add', 'Full database backup successfully!');
            // redirect('admin/dbwork');
        }
        if (isset($_GET['edit']) && !isset($_POST['username'])) {
            $_POST = $this->Admin_users_model->getdbwork($_GET['edit']);
        }
        $data = array();
        $head = array();
        $head['title'] = 'Administration - DBA';
        $head['description'] = 'Database Admin';
        $head['keywords'] = '';
        // $data['users'] = $this->Admin_users_model->getdbwork();
        // $this->form_validation->set_rules('username', 'User', 'trim|required');
        // if (isset($_POST['edit']) && $_POST['edit'] == 0) {
        //     $this->form_validation->set_rules('password', 'Password', 'trim|required');
        // }
        // if ($this->form_validation->run($this)) {
        //     $this->Admin_users_model->setAdminUser($_POST);
        //     $this->saveHistory('Create admin user - ' . $_POST['username']);
        //     redirect('admin/dbwork');
        // }

        $this->load->view('_parts/header', $head);
        $this->load->view('advanced_settings/dbwork', $data);
        $this->load->view('_parts/footer');
        $this->saveHistory('Go to Admin Users');
    }

}
