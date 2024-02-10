<?php

defined('BASEPATH') OR exit('No direct script access allowed');

class Checkout extends MY_Controller
{

    private $orderId;
	private $companyName;
	private $footerContactPhone;

    public function __construct()
    {
        parent::__construct();
        $this->load->model('admin/Orders_model');
		$this->companyName = $this->Home_admin_model->getValueStore('companyName');
		$this->footerContactPhone = $this->Home_admin_model->getValueStore('footerContactPhone');
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
        if($this->config->item('template') == 'divisima'){
            $all_categories = $this->Public_model->getShopCategories();
            $data['home_categories'] = $head['all_categories'] = $this->getHomeCategories($all_categories);
            $data['all_categories'] = $all_categories;
        }
        $arrSeo = $this->Public_model->getSeo('checkout');
        $head['title'] = @$arrSeo['title'];
        $head['description'] = @$arrSeo['description'];
        $head['keywords'] = str_replace(" ", ",", $head['title']);
        
        if (isset($_POST['payment_type'])) {
            $_POST['final_amount'] = str_replace(",", "", $_POST['final_amount']);
            $total = $_POST['final_amount'];
            $errors = $this->userInfoValidate($_POST);
            if (!empty($errors)) {
                $this->session->set_flashdata('submit_error', $errors);
            } else {
                $_POST['referrer'] = $this->session->userdata('referrer');
                $_POST['clean_referrer'] = cleanReferral($_POST['referrer']);
                $_POST['user_id'] = isset($_SESSION['logged_user']) ? $_SESSION['logged_user'] : 0;
                // $_POST['user_id'] = isset($_SESSION['logged_user']) ? $_SESSION['logged_user'] : isset($_POST['user_id']) ? $_POST['user_id'] : 0;
                $orderId = $this->Public_model->setOrder($_POST);
                if ($orderId != false) {
                    /*
                     * Save product orders in vendors profiles
                     */
                    $_POST['last_order_id'] = $orderId;
                    $this->setVendorOrders();
                    // $this->orderId = $orderId;
                    // $this->setActivationLink();
                    // for localhost, it stop
                    // $this->sendNotifications($orderId, $total, $_POST['email'], $_POST['first_name']);
                    // $this->sendNotificationPhone($orderId, $total);
                    $_SESSION['temp_noti'] = array();
                    $_SESSION['temp_noti']['cus_mob'] = $_POST['phone'];
                    $_SESSION['temp_noti']['cus_name'] = $_POST['first_name'];
                    $_SESSION['temp_noti']['cus_email'] = $_POST['phone'];
                    $_SESSION['temp_noti']['order_id'] = $orderId;
                    $_SESSION['temp_noti']['total'] = $total;
                    $this->goToDestination();
                } else {
                    log_message('error', 'Cant save order!! ' . implode('::', $_POST));
                    $this->session->set_flashdata('order_error', true);
                    redirect(LANG_URL . '/checkout/order-error');
                }
                // echo $orderId;
            }
        }
        $data['bank_account'] = $this->Orders_model->getBankAccountSettings();
        $data['cashondelivery_visibility'] = $this->Home_admin_model->getValueStore('cashondelivery_visibility');
        $data['paypal_email'] = $this->Home_admin_model->getValueStore('paypal_email');
        $data['bestSellers'] = $this->Public_model->getbestSellers();
        $data['location'] = $this->Public_model->getLocation();
        $data['shippingOrder'] = $this->Home_admin_model->getValueStore('shippingOrder');
        if(isset($_SESSION['logged_user'])){
            $data['userInfo'] = $this->Public_model->getUserProfileInfo($_SESSION['logged_user']);
            if($this->Public_model->verify_phone($_SESSION['logged_user']) !== true)
                redirect(LANG_URL . '/myaccount');
        }
        $this->render('checkout', $head, $data);
    }

    private function setVendorOrders()
    {
        $this->Public_model->setVendorOrder($_POST);
    }

    /*
     * Send notifications to users that have nofify=1 in /admin/adminusers
     */

    private function sendNotifications($orderId, $total, $to, $uname)
    {
        $users = $this->Public_model->getNotifyUsers();
		$url = parse_url(base_url());
        if (!empty($users)) {
            foreach ($users as $user) {
                $this->sendmail->sendTo($user, "Admin", "New order", "Dear Concern, You have new order #$orderId. Please check and update soon.");
            }
            if($to != "")
                $this->sendmail->sendTo($to, $uname, $url['host'], "Hello $uname,\r\nWe received your order #$orderId and total bill amount is $total Tk.\r\nYou just saved yourself 2 hours by avoiding traffic, bargaining and crowded superstores. Go and spend some time with your family or watch a movie. Enjoy!\r\n\r\nThanks for ordering with us.\r\n\r\nRegards,\r\n".$companyName." Team");
        }
    }
    private function sendNotificationPhone($orderid, $total)
    {
        // $user_phone = $this->Public_model->getNotifyUsersPhone($id);
        // if (!empty($user_phone)) {
        //     foreach ($user_phone as $user) {
                $this->sms_lib->sendSMS($footerContactPhone, "New order #$orderid has been received. Total bill amount $total Tk. \r\nThanks\r\n".$companyName);
        //     }
        // }
    }

    private function setActivationLink()
    {
        if ($this->config->item('send_confirm_link') === true) {
            $link = md5($this->orderId . time());
            $result = $this->Public_model->setActivationLink($link, $this->orderId);
            if ($result == true) {
                $url = parse_url(base_url());
                $msg = lang('please_confirm') . base_url('confirm/' . $link);
                $this->sendmail->sendTo($_POST['email'], $_POST['first_name'], lang('confirm_order_subj') . $url['host'], $msg);
            }
        }
    }

    private function goToDestination()
    {
        if ($_POST['payment_type'] == 'cashOnDelivery' || $_POST['payment_type'] == 'Bank') {
            $this->shoppingcart->clearShoppingCart();
            $this->session->set_flashdata('success_order', true);
        }
        if ($_POST['payment_type'] == 'Bank') {
            $_SESSION['order_id'] = $this->orderId;
            $_SESSION['final_amount'] = $_POST['final_amount'] . $_POST['amount_currency'];
            redirect(LANG_URL . '/checkout/successbank');
        }
        if ($_POST['payment_type'] == 'cashOnDelivery') {
            redirect(LANG_URL . '/checkout/successcash');
        }
        if ($_POST['payment_type'] == 'PayPal') {
            @set_cookie('paypal', $this->orderId, 2678400);
            $_SESSION['discountAmount'] = $_POST['discountAmount'];
            redirect(LANG_URL . '/checkout/paypalpayment');
        }
    }

    private function userInfoValidate($post)
    {
        $errors = array();
        if (mb_strlen(trim($post['first_name'])) == 0) {
            $errors[] = lang('first_name_empty');
        }
        // if (mb_strlen(trim($post['last_name'])) == 0) {
        //     $errors[] = lang('last_name_empty');
        // }
        if($post['email'] != ""){
            if (!filter_var($post['email'], FILTER_VALIDATE_EMAIL)) {
                $errors[] = lang('invalid_email');
            }
        }
        $post['phone'] = preg_replace("/[^0-9]/", '', $post['phone']);
        if (mb_strlen(trim($post['phone'])) == 0) {
            $errors[] = lang('invalid_phone');
        }
        if (mb_strlen(trim($post['address'])) == 0) {
            $errors[] = lang('address_empty');
        }
        if (mb_strlen(trim($post['city'])) == 0) {
            $errors[] = lang('invalid_location');
        }
        return $errors;
    }

    public function orderError()
    {
        if ($this->session->flashdata('order_error')) {
            $data = array();
            $head = array();
            $arrSeo = $this->Public_model->getSeo('checkout');
            $head['title'] = @$arrSeo['title'];
            $head['description'] = @$arrSeo['description'];
            $head['keywords'] = str_replace(" ", ",", $head['title']);
            $this->render('checkout_parts/order_error', $head, $data);
        } else {
            redirect(LANG_URL . '/checkout');
        }
    }

    public function paypalPayment()
    {
        $data = array();
        $head = array();
        $arrSeo = $this->Public_model->getSeo('checkout');
        $head['title'] = @$arrSeo['title'];
        $head['description'] = @$arrSeo['description'];
        $head['keywords'] = str_replace(" ", ",", $head['title']);
        $data['paypal_sandbox'] = $this->Home_admin_model->getValueStore('paypal_sandbox');
        $data['paypal_email'] = $this->Home_admin_model->getValueStore('paypal_email');
        $this->render('checkout_parts/paypal_payment', $head, $data);
    }

    public function successPaymentCashOnD()
    {
        if ($this->session->flashdata('success_order')) {
            $data = array();
            $head = array();
            if($this->config->item('template') == 'divisima'){
                $all_categories = $this->Public_model->getShopCategories();
                $data['home_categories'] = $head['all_categories'] = $this->getHomeCategories($all_categories);
                $data['all_categories'] = $all_categories;
            }
            $arrSeo = $this->Public_model->getSeo('checkout');
            $head['title'] = @$arrSeo['title'];
            $head['description'] = @$arrSeo['description'];
            $head['keywords'] = str_replace(" ", ",", $head['title']);
            $data['temp_noti'] = $_SESSION['temp_noti'];
            $_SESSION['temp_noti'] = "";
            $this->render('checkout_parts/payment_success_cash', $head, $data);
        } else {
            redirect(LANG_URL . '/checkout');
        }
    }

    public function successPaymentBank()
    {
        if ($this->session->flashdata('success_order')) {
            $data = array();
            $head = array();
            $arrSeo = $this->Public_model->getSeo('checkout');
            $head['title'] = @$arrSeo['title'];
            $head['description'] = @$arrSeo['description'];
            $head['keywords'] = str_replace(" ", ",", $head['title']);
            $data['bank_account'] = $this->Orders_model->getBankAccountSettings();
            $this->render('checkout_parts/payment_success_bank', $head, $data);
        } else {
            redirect(LANG_URL . '/checkout');
        }
    }

    public function paypal_cancel()
    {
        if (get_cookie('paypal') == null) {
            redirect(base_url());
        }
        @delete_cookie('paypal');
        $orderId = get_cookie('paypal');
        $this->Public_model->changePaypalOrderStatus($orderId, 'canceled');
        $data = array();
        $head = array();
        $head['title'] = '';
        $head['description'] = '';
        $head['keywords'] = '';
        $this->render('checkout_parts/paypal_cancel', $head, $data);
    }

    public function paypal_success()
    {
        if (get_cookie('paypal') == null) {
            redirect(base_url());
        }
        @delete_cookie('paypal');
        $this->shoppingcart->clearShoppingCart();
        $orderId = get_cookie('paypal');
        $this->Public_model->changePaypalOrderStatus($orderId, 'payed');
        $data = array();
        $head = array();
        $head['title'] = '';
        $head['description'] = '';
        $head['keywords'] = '';
        $this->render('checkout_parts/paypal_success', $head, $data);
    }
    
    public function find_user()
    {
        $str = $this->Public_model->getUserSuggestions($_POST['str']);
        echo json_encode($str);
    }

}
