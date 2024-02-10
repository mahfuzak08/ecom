<?php

defined('BASEPATH') OR exit('No direct script access allowed');

class Home extends MY_Controller
{

    private $num_rows = 20;

    public function __construct()
    {
        parent::__construct();
        $this->load->Model('admin/Brands_model');
        $this->load->Model('admin/Vendor_model');
    }

    public function index($page = 0)
    {
        $host = str_replace("www.", "", $_SERVER["HTTP_HOST"]);
        $client = $this->Public_model->getClient($host);
        if($client["has_ecom"] === "0"){
            redirect(LANG_URL . '/admin');
        }
        $data = array();
        $head = array();
        $arrSeo = $this->Public_model->getSeo('home');
        $head['title'] = @$arrSeo['title'];
        $head['description'] = @$arrSeo['description'];
        $head['keywords'] = str_replace(" ", ",", $head['title']);
        $all_categories = $this->Public_model->getShopCategories();
        $data['home_categories'] = $head['all_categories'] = $this->getHomeCategories($all_categories);
        $data['all_categories'] = $all_categories;
        $data['countQuantities'] = $this->Public_model->getCountQuantities();
        $data['bestSellers'] = $this->Public_model->getbestSellers();
        $data['newProducts'] = $this->Public_model->getNewProducts();
        $data['sliderProducts'] = $this->Public_model->getSliderProducts();
        $data['lastBlogs'] = $this->Public_model->getLastBlogs();
        $data['products'] = $this->Public_model->getProducts($this->num_rows, $page, $_GET);
        $rowscount = $this->Public_model->productsCount($_GET);
        $data['shippingOrder'] = $this->Home_admin_model->getValueStore('shippingOrder');
        $data['showOutOfStock'] = $this->Home_admin_model->getValueStore('outOfStock');
        $data['showBrands'] = $this->Home_admin_model->getValueStore('showBrands');
        $data['brands'] = $this->Brands_model->getBrands();
        $data['vendor'] = $this->Vendor_model->getVendor();
        $data['links_pagination'] = pagination('home', $rowscount, $this->num_rows);
        $this->render('home', $head, $data);
    }

    /*
     * Used from greenlabel template
     * shop page
     */

    public function shop($page = 0)
    {
        $data = array();
        $head = array();
        $arrSeo = $this->Public_model->getSeo('shop');
        $head['title'] = @$arrSeo['title'];
        $head['description'] = @$arrSeo['description'];
        $head['keywords'] = str_replace(" ", ",", $head['title']);
        $all_categories = $this->Public_model->getShopCategories();
        $data['home_categories'] = $this->getHomeCategories($all_categories);
        $data['all_categories'] = $all_categories;
        $data['showBrands'] = $this->Home_admin_model->getValueStore('showBrands');
        $data['brands'] = $this->Brands_model->getBrands();
        $data['vendor'] = $this->Vendor_model->getVendor();
        $data['showOutOfStock'] = $this->Home_admin_model->getValueStore('outOfStock');
        $data['shippingOrder'] = $this->Home_admin_model->getValueStore('shippingOrder');
        $data['products'] = $this->Public_model->getProducts($this->num_rows, $page, $_GET);
        $rowscount = $this->Public_model->productsCount($_GET);
        $data['links_pagination'] = pagination('home', $rowscount, $this->num_rows);
        $this->render('shop', $head, $data);
    }

    /*
     * Called to add/remove quantity from cart
     * If is ajax request send POST'S to class ShoppingCart
     */

    public function manageShoppingCart()
    {
        if (!$this->input->is_ajax_request()) {
            exit('No direct script access allowed');
        }
        $this->shoppingcart->manageShoppingCart();
    }

    /*
     * Called to remove product from cart
     * If is ajax request and send $_GET variable to the class
     */

    public function removeFromCart()
    {
        $backTo = $_GET['back-to'];
        $this->shoppingcart->removeFromCart();
        $this->session->set_flashdata('deleted', lang('deleted_product_from_cart'));
        redirect(LANG_URL . '/' . $backTo);
    }

    public function clearShoppingCart()
    {
        $this->shoppingcart->clearShoppingCart();
    }

    public function viewProduct($id)
    {
        $data = array();
        $head = array();
        $data['product'] = $this->Public_model->getOneProduct($id);
		
		$all_categories = $this->Public_model->getShopCategories();
        $data['home_categories'] = $head['all_categories'] = $this->getHomeCategories($all_categories);
        $data['all_categories'] = $all_categories;
		
        if ($data['product'] === null) {
            show_404();
        }
        $data['sameCagegoryProducts'] = $this->Public_model->sameCagegoryProducts($data['product']['shop_categorie'], $id);
        $vars['publicDateAdded'] = $this->Home_admin_model->getValueStore('publicDateAdded');
        $this->load->vars($vars);
        $head['title'] = $data['product']['title'];
        $description = url_title(character_limiter(strip_tags($data['product']['description']), 130));
        $description = str_replace("-", " ", $description) . '..';
        $head['description'] = $description;
        $head['keywords'] = str_replace(" ", ",", $data['product']['title']);
        $this->render('view_product', $head, $data);
    }

    public function confirmLink($md5)
    {
        if (preg_match('/^[a-f0-9]{32}$/', $md5)) {
            $result = $this->Public_model->confirmOrder($md5);
            if ($result === true) {
                $data = array();
                $head = array();
                $head['title'] = '';
                $head['description'] = '';
                $head['keywords'] = '';
                $this->render('confirmed', $head, $data);
            } else {
                show_404();
            }
        } else {
            show_404();
        }
    }

    public function discountCodeChecker()
    {
        if (!$this->input->is_ajax_request()) {
            exit('No direct script access allowed');
        }
        $result = $this->Public_model->getValidDiscountCode($_POST['enteredCode']);
        if ($result == null) {
            echo 0;
        } else {
            echo json_encode($result);
        }
    }

    public function sitemap()
    {
        header("Content-Type:text/xml");
        echo '<?xml version="1.0" encoding="UTF-8"?>
                <urlset xmlns="http://www.sitemaps.org/schemas/sitemap/0.9">';
        $products = $this->Public_model->sitemap();
        $blogPosts = $this->Public_model->sitemapBlog();

        foreach ($blogPosts->result() as $row1) {
            echo '<url>

      <loc>' . base_url('blog/' . $row1->url) . '</loc>

      <changefreq>monthly</changefreq>

      <priority>0.1</priority>

   </url>';
        }

        foreach ($products->result() as $row) {
            echo '<url>

      <loc>' . base_url($row->url) . '</loc>

      <changefreq>monthly</changefreq>

      <priority>0.1</priority>

   </url>';
        }

        echo '</urlset>';
    }

    public function suggestions()
    {
        $str = $this->Public_model->getProductsSuggestions($_POST['str']);
        echo json_encode($str);
    }

    private function send_email($orderId, $total, $to, $uname)
    {
        $users = $this->Public_model->getNotifyUsers();
		$url = parse_url(base_url());
        if (!empty($users)) {
            foreach ($users as $user) {
                $this->sendmail->sendTo($user, "Admin", "New order", "Dear Concern, You have new order #$orderId. Please check and update soon.");
            }
            if($to != "")
                $this->sendmail->sendTo($to, $uname, $url['host'], "Hello $uname,\r\nWe received your order #$orderId and total bill amount is $total Tk.\r\nYou just saved yourself 2 hours by avoiding traffic, bargaining and crowded superstores. Go and spend some time with your family or watch a movie. Enjoy!\r\n\r\nThanks for ordering with us.\r\n\r\nRegards");
        }
    }
    // private function send_sms($orderid, $total)
    public function send_sms()
    {
        $to = $_POST['to'];
        $str = $_POST['str'];
        // $user_phone = $this->Public_model->getNotifyUsersPhone($id);
        // if (!empty($user_phone)) {
        //     foreach ($user_phone as $user) {
                // $this->sms_lib->sendSMS($footerContactPhone, "New order #$orderid has been received. Total bill amount $total Tk. \r\nThanks");
                return $this->sms_lib->sendSMS($to, $str."\r\nThanks");
                // file_put_contents("sms.txt", $to."\r\n".$str);
        //     }
        // }
    }
    
    /*
     * privacy policy
     */
    public function privacy()
    {
        $data = array();
        $head = array();
        $arrSeo = $this->Public_model->getSeo('shop');
        $all_categories = $this->Public_model->getShopCategories();
        $data['home_categories'] = $head['all_categories'] = $this->getHomeCategories($all_categories);
        $data['all_categories'] = $all_categories;
        $head['title'] = @$arrSeo['title'];
        $head['description'] = @$arrSeo['description'];
        $head['keywords'] = str_replace(" ", ",", $head['title']);
        $this->render('policy', $head, $data);
    }
}
