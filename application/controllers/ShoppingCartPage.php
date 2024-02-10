<?php

defined('BASEPATH') OR exit('No direct script access allowed');

class ShoppingCartPage extends MY_Controller
{

    public function __construct()
    {
        parent::__construct();
        $this->load->Model('Public_model');
    }

    public function index()
    {
        $host = str_replace("www.", "", $_SERVER["HTTP_HOST"]);
        $client = $this->Public_model->getClient($host);
        if($client["has_ecom"] === "0"){
            redirect(LANG_URL . '/admin');
        }
        $data = array();
        $head = array();
        $all_categories = $this->Public_model->getShopCategories();
        $data['home_categories'] = $head['all_categories'] = $this->getHomeCategories($all_categories);
        $data['all_categories'] = $all_categories;
        $arrSeo = $this->Public_model->getSeo('shoppingcart');
        $head['title'] = @$arrSeo['title'];
        $head['description'] = @$arrSeo['description'];
        $head['keywords'] = str_replace(" ", ",", $head['title']);
        $this->render('shopping_cart', $head, $data);
    }
    
    public function wish_list()
    {
        $data = array();
        $head = array();
        $arrSeo = $this->Public_model->getSeo('shoppingcart');
        $head['title'] = @$arrSeo['title'];
        $head['description'] = @$arrSeo['description'];
        $head['keywords'] = str_replace(" ", ",", $head['title']);
        if(isset($_POST["wish_submit"])){
            $errors = false;
            $_POST['user_id'] = isset($_SESSION['logged_user']) ? $_SESSION['logged_user'] : 0;
            if (empty($_POST['user_id'])) {
                $this->session->set_flashdata('submit_error', "Please login");
            } else {
                if(! $this->Public_model->setWish($_POST)){
                    $errors = true;
                    $this->session->set_flashdata('submit_error', "Some unwanted error. Please contact with administrator.");
                }
                $this->shoppingcart->clearWishCart();
                redirect(LANG_URL . '/');
            }
        }
        $head['wishItems'] = $this->shoppingcart->getWishItems();
        $this->render('wish_cart', $head, $data);
    }

}
