<?php

defined('BASEPATH') OR exit('No direct script access allowed');
/*
 * Shopping Cart class for manage products
 */

class ShoppingCart
{

    protected $CI;
    public $sumValues;
    /*
     * 1 month expire time
     */
    private $cookieExpTime = 2678400;

    public function __construct()
    {
        $this->CI = & get_instance();
        $this->CI->load->model('admin/Home_admin_model');
    }

    public function manageShoppingCart()
    {
        if ($_POST['action'] == 'add') {
            if (!isset($_SESSION['shopping_cart'])) {
                $_SESSION['shopping_cart'] = array();
            }
            @$_SESSION['shopping_cart'][] = (int) $_POST['article_id'];
        }
        if ($_POST['action'] == 'remove') {
            if (($key = array_search($_POST['article_id'], $_SESSION['shopping_cart'])) !== false) {
                unset($_SESSION['shopping_cart'][$key]);
            }
        }
        @set_cookie('shopping_cart', serialize($_SESSION['shopping_cart']), $this->cookieExpTime);
        $result = 0;
        if (!empty($_SESSION['shopping_cart'])) {
            $result = $this->getCartItems();
        }
        // get items from db and add him to cart products list from ajax
        $loop = $this->CI->loop;
        $loop::getCartItems($result);
    }

    public function removeFromCart()
    {
        $count = count(array_keys($_SESSION['shopping_cart'], $_GET['delete-product']));
        $i = 1;
        do {
            if (($key = array_search($_GET['delete-product'], $_SESSION['shopping_cart'])) !== false) {
                unset($_SESSION['shopping_cart'][$key]);
            }
            $i++;
        } while ($i <= $count);
        @set_cookie('shopping_cart', serialize($_SESSION['shopping_cart']), $this->cookieExpTime);
    }

    public function getCartItems()
    {
        $item_ids = array();
        $item_size = array();
        if (get_cookie('shopping_cart') != NULL) {
            $cart = json_decode(get_cookie('shopping_cart'));
            foreach($cart as $item){
                $item_ids[] = $item->id;
                $item_size[$item->id] = $item->size;
                if($item->qty > 1){
                    for($n=1; $n<$item->qty; $n++)
                        $item_ids[] = $item->id;
                }
            }
        } else
            return 0;
            
        $result['array'] = $this->CI->Public_model->getShopItems(array_unique($item_ids));
        if (empty($result['array'])) {
            @delete_cookie('shopping_cart');
            return 0;
        }
        $count_articles = array_count_values($item_ids);
        $this->sumValues = array_sum($count_articles);
        $finalSum = 0;

        foreach ($result['array'] as &$article) {
            $article['num_added'] = $count_articles[$article['id']];
            $article['price'] = $article['price'] == '' ? 0 : $article['price'];
            $article['size'] = $article['size'];
            $article['size_select'] = $item_size[$article['id']];
            $article['sum_price'] = $article['price'] * $count_articles[$article['id']];
            $finalSum = $finalSum + $article['sum_price'];
            $article['sum_price'] = number_format($article['sum_price'], 2);
            $article['price'] = $article['price'] != '' ? number_format($article['price'], 2) : 0;
        }
        $result['shipping'] = number_format(0, 2);
        $finalSum += $result['shipping'];
        $result['finalSum'] = number_format($finalSum, 2);
        return $result;
        // if ((!isset($_SESSION['shopping_cart']) || empty($_SESSION['shopping_cart'])) && get_cookie('shopping_cart') != NULL) {
        //     $_SESSION['shopping_cart'] = unserialize(get_cookie('shopping_cart'));
        // } elseif (!isset($_SESSION['shopping_cart']) || !is_array($_SESSION['shopping_cart'])) {
        //     return 0;
        // }
        // $result['array'] = $this->CI->Public_model->getShopItems(array_unique($_SESSION['shopping_cart']));
        // if (empty($result['array'])) {
        //     unset($_SESSION['shopping_cart']);
        //     @delete_cookie('shopping_cart');
        //     return 0;
        // }
        // $count_articles = array_count_values($_SESSION['shopping_cart']);
        // $this->sumValues = array_sum($count_articles);
        // $finalSum = 0;

        // foreach ($result['array'] as &$article) {
        //     $article['num_added'] = $count_articles[$article['id']];
        //     $article['price'] = $article['price'] == '' ? 0 : $article['price'];
        //     $article['sum_price'] = $article['price'] * $count_articles[$article['id']];
        //     $finalSum = $finalSum + $article['sum_price'];
        //     $article['sum_price'] = number_format($article['sum_price'], 2);
        //     $article['price'] = $article['price'] != '' ? number_format($article['price'], 2) : 0;
        // }
        // $result['shipping'] = number_format(0, 2);
        // $finalSum += $result['shipping'];
        // $result['finalSum'] = number_format($finalSum, 2);
        // return $result;
    }

    public function clearShoppingCart()
    {
        unset($_SESSION['shopping_cart']);
        @delete_cookie('shopping_cart');
        if ($this->CI->input->is_ajax_request()) {
            echo 1;
        }
    }


    public function manageWishCart()
    {
        if ($_POST['action'] == 'add') {
            if (!isset($_SESSION['wish_cart'])) {
                $_SESSION['wish_cart'] = array();
            }
            @$_SESSION['wish_cart'][] = (int) $_POST['article_id'];
        }
        if ($_POST['action'] == 'remove') {
            if (($key = array_search($_POST['article_id'], $_SESSION['wish_cart'])) !== false) {
                unset($_SESSION['wish_cart'][$key]);
            }
        }
        @set_cookie('wish_cart', serialize($_SESSION['wish_cart']), $this->cookieExpTime);
        $result = 0;
        if (!empty($_SESSION['wish_cart'])) {
            $result = $this->getWishItems();
        }
        // get items from db and add him to cart products list from ajax
        $loop = $this->CI->loop;
        $loop::getWishItems($result);
    }

    public function removeFromWish()
    {
        $count = count(array_keys($_SESSION['wish_cart'], $_GET['delete-product']));
        $i = 1;
        do {
            if (($key = array_search($_GET['delete-product'], $_SESSION['wish_cart'])) !== false) {
                unset($_SESSION['wish_cart'][$key]);
            }
            $i++;
        } while ($i <= $count);
        @set_cookie('wish_cart', serialize($_SESSION['wish_cart']), $this->cookieExpTime);
    }

    public function getWishItems()
    {
        $item_ids = array();
        if (get_cookie('wish_cart') != NULL) {
            $cart = json_decode(get_cookie('wish_cart'));
            foreach($cart as $item){
                $item_ids[] = $item->id;
                if($item->qty > 1){
                    for($n=1; $n<$item->qty; $n++)
                        $item_ids[] = $item->id;
                }
            }
        } else
            return 0;
            
        $result['array'] = $this->CI->Public_model->getShopItems(array_unique($item_ids));
        if (empty($result['array'])) {
            @delete_cookie('wish_cart');
            return 0;
        }
        $count_articles = array_count_values($item_ids);
        $this->sumValues = array_sum($count_articles);
        $finalSum = 0;

        foreach ($result['array'] as &$article) {
            $article['num_added'] = $count_articles[$article['id']];
            $article['price'] = $article['price'] == '' ? 0 : $article['price'];
            $article['size'] = $article['size'];
            $article['sum_price'] = $article['price'] * $count_articles[$article['id']];
            $finalSum = $finalSum + $article['sum_price'];
            $article['sum_price'] = number_format($article['sum_price'], 2);
            $article['price'] = $article['price'] != '' ? number_format($article['price'], 2) : 0;
        }
        return $result;
    }

    public function clearWishCart()
    {
        unset($_SESSION['wish_cart']);
        @delete_cookie('wish_cart');
        if ($this->CI->input->is_ajax_request()) {
            echo 1;
        }
    }

}
