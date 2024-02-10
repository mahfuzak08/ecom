<?php

defined('BASEPATH') OR exit('No direct script access allowed');

class Shopid
{

    protected $CI;

    public function __construct()
    {
        $this->CI = & get_instance();
        $this->CI->load->model('Shop_model');
        $this->setShop();
    }

    private function setShop()
    {
        $http_host = str_replace('www.', '', $_SERVER['HTTP_HOST']);
        $shop_id = $this->CI->Shop_model->getShopId($http_host);
        if($shop_id == null) {
            echo "<h1 style='text-align:center;font-size: 150px;'>404</h1><h4 style='text-align:center;font-size: 80px;'>Not Found</h4>";
            exit();
        }

        define('SHOP_ID', $shop_id);
        define('SHOP_DIR', $http_host);
    }
}
