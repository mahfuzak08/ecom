<?php

/*
 * @Author:    Md. Mahfuzur Rahman
 *  Gitgub:    https://github.com/mahfuzak08/
 */
if (!defined('BASEPATH')) {
    exit('No direct script access allowed');
}

class Clients extends ADMIN_Controller
{

    public function __construct()
    {
        parent::__construct();
        $this->load->model('Admin_clients_model');
    }

    public function index()
    {
        $this->login_check();
        $data = array();
        $head = array();
        $head['title'] = 'Administration - Client Lists';
        $head['description'] = 'Client Lists';
        $head['keywords'] = '';

        if (isset($_GET['id'])) {
            $status = $_GET['status'] == 1 ? 0 : 1;
            $this->Admin_clients_model->changeClientStatus($_GET['id'], array('is_active'=>$status));
            $this->session->set_flashdata('result_delete', 'Client status update');
            redirect('admin/clients');
        }
        if (isset($_GET['ecomId'])) {
            $status = $_GET['status'] == 1 ? 0 : 1;
            $this->Admin_clients_model->changeClientStatus($_GET['ecomId'], array('has_ecom'=>$status));
            $this->session->set_flashdata('result_delete', 'Client ecom status update');
            redirect('admin/clients');
        }
        if (isset($_GET['delete'])) {
            $base_url = $this->Admin_clients_model->deleteClient($_GET['delete']);
            $dir = '.' . DIRECTORY_SEPARATOR . 'attachments' . DIRECTORY_SEPARATOR . $base_url . DIRECTORY_SEPARATOR;
            $this->rrmdir($dir);
            $this->session->set_flashdata('result_delete', 'Client is deleted!');
            redirect('admin/clients');
        }
        if (isset($_GET['edit']) && !isset($_POST['base_url'])) {
            $data["client"] = $this->Admin_clients_model->getClients($_GET['edit']);
        }
        if (isset($_GET['noti'])) {
            $data["client"] = $this->Admin_clients_model->getClients($_GET['noti']);
        }
        
        $data['clients'] = $this->Admin_clients_model->getClients();
        if (isset($_POST['add_client'])) {
            file_put_contents("bu.txt", json_encode($_POST));
            $this->form_validation->set_rules('base_url', 'Base URL', 'trim|required');
            if ($this->form_validation->run($this)) {
                $this->make_client_dir($_POST['base_url']);
                $this->Admin_clients_model->setClients($_POST);
                if($_POST['id']>0)
                    $this->saveHistory('Update client - ' . $_POST['base_url']);
                else
                    $this->saveHistory('Create new client - ' . $_POST['base_url']);
                redirect('admin/clients');
            }else{
                $this->session->set_flashdata('result_delete', validation_errors());
            }
        }
        if (isset($_POST['add_notification'])) {
            $this->form_validation->set_rules('notification', 'notification', 'trim|required');
            if ($this->form_validation->run($this)) {
                $this->Admin_clients_model->changeClientStatus($_POST['id'], array('notification'=>$_POST['notification']));
                $this->saveHistory('Update notification - ' . $_POST['notification']);
                redirect('admin/clients');
            }
        }
        $this->load->view('_parts/header', $head);
        $this->load->view('advanced_settings/clientLists', $data);
        $this->load->view('_parts/footer');
        $this->saveHistory('Go to client lists');
    }

    public function make_client_dir($base_url)
    {
        $attch_dir = '.' . DIRECTORY_SEPARATOR . 'attachments' . DIRECTORY_SEPARATOR;
        $client_dir = $attch_dir . $base_url . DIRECTORY_SEPARATOR;
        if (!file_exists($client_dir)) {
            mkdir($client_dir, 0777);
            copy($attch_dir . 'index.php', $client_dir . 'index.php');
        }
        $dirs = array('blog_images', 'shop_images', 'site_app', 'site_ico', 'site_logo', 'site_overview');

        foreach($dirs as $dir){
            $dir_path = $client_dir . $dir . DIRECTORY_SEPARATOR;
            if (!file_exists($dir_path)) {
                mkdir($dir_path, 0777);
                copy($attch_dir . 'index.php', $dir_path . 'index.php');
            }
        }        
    }

    public function rrmdir($dir) {
        if (is_dir($dir)) {
            $objects = scandir($dir);
            foreach ($objects as $object) {
                if ($object != "." && $object != "..") {
                    if (filetype($dir."/".$object) == "dir") 
                        $this->rrmdir($dir."/".$object); 
                    else unlink($dir."/".$object);
                }
            }
            reset($objects);
            rmdir($dir);
        }
    }
}
