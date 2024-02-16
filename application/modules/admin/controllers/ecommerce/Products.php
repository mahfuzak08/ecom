<?php

/*
 * @Author:    Md. Mahfuzur Rahman
 *  Gitgub:    https://github.com/mahfuzak08/
 */
if (!defined('BASEPATH')) {
    exit('No direct script access allowed');
}

class Products extends ADMIN_Controller
{

    private $num_rows = 20;

    public function __construct()
    {
        parent::__construct();
        $this->load->model(array('Products_model', 'Languages_model', 'Categories_model'));
    }

    public function index($page = 0)
    {
        $this->login_check();
        $data = array();
        $head = array();
        $head['title'] = 'Administration - View products';
        $head['description'] = '!';
        $head['keywords'] = '';

        if (isset($_GET['delete'])) {
            $this->Products_model->deleteProduct($_GET['delete']);
            $this->session->set_flashdata('result_delete', 'product is deleted!');
            $this->saveHistory('Delete product id - ' . $_GET['delete']);
            redirect('admin/products');
        }
        if (isset($_GET['active'])) {
            $this->Products_model->productStatusChange($_GET['active'], 1);
            $this->session->set_flashdata('result_delete', 'product is active');
            $this->saveHistory('Active product id - ' . $_GET['active']);
            redirect('admin/products');
        }
        if (isset($_GET['inactive'])) {
            $this->Products_model->productStatusChange($_GET['inactive'], 0);
            $this->session->set_flashdata('result_delete', 'product is inactive');
            $this->saveHistory('Inactive product id - ' . $_GET['inactive']);
            redirect('admin/products');
        }
        
        unset($_SESSION['filter']);
        $search_title = null;
        if ($this->input->get('search_title') !== NULL) {
            $search_title = $this->input->get('search_title');
            $_SESSION['filter']['search_title'] = $search_title;
            $this->saveHistory('Search for product title - ' . $search_title);
        }
        $orderby = null;
        if ($this->input->get('order_by') !== NULL) {
            $orderby = $this->input->get('order_by');
            $_SESSION['filter']['order_by '] = $orderby;
        }
        $category = null;
        if ($this->input->get('category') !== NULL) {
            $category = $this->input->get('category');
            $_SESSION['filter']['category '] = $category;
            $this->saveHistory('Search for product code - ' . $category);
        }
        $vendor = null;
        if ($this->input->get('show_vendor') !== NULL) {
            $vendor = $this->input->get('show_vendor');
        }
        $reorder_level = null;
        if ($this->input->get('reorder_level') !== NULL) {
            $reorder_level = $this->input->get('reorder_level');
            $_SESSION['filter']['reorder_level '] = $reorder_level;
        }
        $data['products_lang'] = $products_lang = $this->session->userdata('admin_lang_products');
        $rowscount = $this->Products_model->productsCount($search_title, $category);
        $data['products'] = $this->Products_model->getproducts($this->num_rows, $page, $search_title, $orderby, $category, $vendor, $reorder_level);
        // file_put_contents("lq.txt", $this->db->last_query());
        $data['links_pagination'] = pagination('admin/products', $rowscount, $this->num_rows, 3);
        $data['num_shop_art'] = $this->Products_model->numShopproducts();
        $data['languages'] = $this->Languages_model->getLanguages();
        $data['shop_categories'] = $this->Categories_model->getShopCategories(null, null, 2);
        $this->saveHistory('Go to products');
        $this->load->view('_parts/header', $head);
        $this->load->view('ecommerce/products', $data);
        $this->load->view('_parts/footer');
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
    
    public function reorder()
    {
        $this->login_check();
        $data = array();
        $head = array();
        $head['title'] = 'Administration - Product Reorder';
        $head['description'] = 'Product Reorder';
        $head['keywords'] = '';
        $data['id'] = $id = isset($_GET['id']) ? $_GET['id'] : 0;
        $is_update = false;
        $trans_load = null;
        // $data['supplierName'] = "";
        if(isset($_POST["reorder_submit"])){
            $this->Products_model->updateProduct($_POST, $id);
            $this->session->set_flashdata('result_publish', 'Product store successfully!');
            redirect('admin/products/reorder');
        }
        if ($id > 0 && $_POST == null) {
            $_POST = $this->Products_model->getOneProduct($id);
            $trans_load = $this->Products_model->getTranslations($id);
            // $data['supplierName'] = $this->Products_model->getLastSupplierName($id);
        }
        
        $data['trans_load'] = $trans_load;
        $data['languages'] = $this->Languages_model->getLanguages();
        $this->load->view('_parts/header', $head);
        $this->load->view('ecommerce/reorder', $data);
        $this->load->view('_parts/footer');
        $this->saveHistory('Go to product reorder');
    }

}
