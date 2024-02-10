<?php

/*
 * @Author:    Md. Mahfuzur Rahman
 *  Gitgub:    https://github.com/mahfuzak08/
 */
if (!defined('BASEPATH')) {
    exit('No direct script access allowed');
}

class Vendormanage extends ADMIN_Controller
{

    private $num_rows = 10;

    public function __construct()
    {
        parent::__construct();
        $this->load->model(array('Vendor_model', 'Languages_model', 'Categories_model'));
    }

    public function index($page = 0)
    {
        $this->login_check();
        $data = array();
        $head = array();
        $head['title'] = 'Administration - Vendor Management';
        $head['description'] = 'Supplier';
        $head['keywords'] = '';

        if(isset($_POST['vendor_name']) && isset($_POST['vendor_mobile']) && isset($_POST['vendor_url'])){
            $result_id = $this->Vendor_model->setVendor($_POST, $_POST['id']);
            if ($_POST['id'] == 0 && $result_id > 0) {
				$this->session->set_flashdata('result_publish', 'Success add new vendor.');
                $this->saveHistory('Success add new vendor');
            } elseif($_POST['id'] > 0 && $result_id == $_POST['id']) {
                $this->session->set_flashdata('result_publish', 'Vendor update successfully.');
                $this->saveHistory('Success updated vendor');
            }
            redirect('admin/vendormanage');
        }
        if(isset($_GET['deactive'])) {
            $this->Vendor_model->deleteVendor($_GET['deactive'], 1);
            $this->session->set_flashdata('result_delete', 'Vendor is Deactiveted!');
            $this->saveHistory('Deactiveted vendor id - ' . $_GET['deactive']);
            redirect('admin/vendormanage');
        }
        elseif (isset($_GET['active'])) {
            $this->Vendor_model->deleteVendor($_GET['active'], 0);
            $this->session->set_flashdata('result_delete', 'Vendor is activeted!');
            $this->saveHistory('Activeted vendor id - ' . $_GET['active']);
            redirect('admin/vendormanage');
        }
        elseif(isset($_GET['delete'])) {
            $this->Vendor_model->deleteVendor($_GET['delete'], 2);
            $this->session->set_flashdata('result_delete', 'Vendor is deleted!' . $_GET['delete']);
            $this->saveHistory('Delete vendor id - ' . $_GET['delete']);
            redirect('admin/vendormanage');
        }
        elseif(isset($_GET['edit'])) {
            $data['vendor'] = $this->Vendor_model->getOneVendor($_GET['edit']);
        }
        
        $data['vendors'] = $this->Vendor_model->getvendors();
        $this->saveHistory('Go to Vendor');
        $this->load->view('_parts/header', $head);
        $this->load->view('vendor/vendormanage', $data);
        $this->load->view('_parts/footer');
    }

	public function addvendor($id = 0)
	{
		$this->login_check();
		$is_update = false;
        $trans_load = null;
		if ($id > 0 && $_POST == null) {
            $_POST = $this->Vendor_model->getOneVendor($id);
        }
        if (isset($_POST['saveVendorDetails'])) {
            $result_id = $this->Vendor_model->setVendor($_POST, $id);
            if ($id == 0 && $result_id > 0) {
				$this->session->set_flashdata('result_publish', 'Success add new vendor.');
                $this->saveHistory('Success add new vendor');
            } elseif($id > 0 && $result_id == $id) {
                $this->session->set_flashdata('result_publish', 'Vendor update successfully.');
                $this->saveHistory('Success updated vendor');
            }
            redirect('admin/vendormanage');
        }
        $data = array();
        $head = array();
        $head['title'] = "Administration - Vendor Add";
        $head['description'] = 'Vendor';
        $head['keywords'] = '';
		$data['id'] = $id;
        $data['trans_load'] = $trans_load;
        $this->load->view('_parts/header', $head);
        $this->load->view('vendor/profile', $data);
        $this->load->view('_parts/footer');
		$this->saveHistory('Go to publish product');
	}
    public function getProductInfo($id, $noLoginCheck = false)
    {
        /* 
         * if method is called from public(template) page
         */
        if ($noLoginCheck == false) {
            $this->login_check();
        }
        return $this->Products_model->getOneProduct($id);
    }

    /*
     * called from ajax
     */

    public function productStatusChange()
    {
        $this->login_check();
        $result = $this->Products_model->productStatusChange($_POST['id'], $_POST['to_status']);
        if ($result == true) {
            echo 1;
        } else {
            echo 0;
        }
        $this->saveHistory('Change product id ' . $_POST['id'] . ' to status ' . $_POST['to_status']);
    }

}
