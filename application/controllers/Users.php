<?php

defined('BASEPATH') OR exit('No direct script access allowed');

class Users extends MY_Controller
{

    private $registerErrors = array();
    private $user_id;
    private $num_rows = 5;
    
    public function __construct()
    {
        parent::__construct();
        $this->load->library('email');
        
        $this->load->library(array('my_form_validation'));
		$this->form_validation->run($this);
    }

    public function index()
    {
        show_404();
    }

    public function login()
    {
        $host = str_replace("www.", "", $_SERVER["HTTP_HOST"]);
        $client = $this->Public_model->getClient($host);
        if($client["has_ecom"] === "0"){
            redirect(LANG_URL . '/admin');
        }
        if (isset($_POST['login'])) {
            $result = $this->Public_model->checkPublicUserIsValid($_POST);
            if ($result !== false) {
                $_SESSION['logged_user'] = $result["id"]; //id of user
                $_SESSION['logged_user_name'] = $result["name"]; //name of user
                if($this->Public_model->verify_phone($_SESSION['logged_user']) === true)
                    redirect(LANG_URL . '/checkout');
                else
                    redirect(LANG_URL . '/myaccount');
            } else {
                $this->session->set_flashdata('userError', lang('wrong_user'));
            }
        }
        $head = array();
        $data = array();
        if($this->config->item('template') == 'divisima'){
            $all_categories = $this->Public_model->getShopCategories();
            $data['home_categories'] = $head['all_categories'] = $this->getHomeCategories($all_categories);
            $data['all_categories'] = $all_categories;
        }
        $head['title'] = lang('user_login');
        $head['description'] = lang('user_login');
        $head['keywords'] = str_replace(" ", ",", $head['title']);
        $this->render('login', $head, $data);
    }
    
    public function register()
    {
        $host = str_replace("www.", "", $_SERVER["HTTP_HOST"]);
        $client = $this->Public_model->getClient($host);
        if($client["has_ecom"] === "0"){
            redirect(LANG_URL . '/admin');
        }
        $this->load->library('form_validation');
        if (isset($_POST['signup'])) {
            $this->form_validation->set_rules('name', 'Name', 'trim|required');
            $this->form_validation->set_rules('phone', 'Mobile', 'trim|required|callback_validate_userswithphone');
            $this->form_validation->set_rules('pass', 'Password', 'trim|required');
            $this->form_validation->set_rules('pass_repeat', 'Confirm Password', 'trim|required|matches[pass]');
            $this->form_validation->set_rules('email', 'Email', 'trim|valid_email|callback_validate_userswithemail');
            
            if ($this->form_validation->run() == FALSE) {
                $this->session->set_flashdata('userError', validation_errors());
                redirect(LANG_URL . '/register');
            }
            else
            {
                $this->user_id = $this->Public_model->registerUser($_POST);
                if($this->user_id > 0){
                    $_SESSION['logged_user'] = $this->user_id; //id of user
                    redirect(LANG_URL . '/myaccount');
                }
                else{
                    redirect(LANG_URL . '/login');
                }
            }
        }
        $head = array();
        $data = array();
        if($this->config->item('template') == 'divisima'){
            $all_categories = $this->Public_model->getShopCategories();
            $data['home_categories'] = $head['all_categories'] = $this->getHomeCategories($all_categories);
            $data['all_categories'] = $all_categories;
        }
        $head['title'] = lang('user_register');
        $head['description'] = lang('user_register');
        $head['keywords'] = str_replace(" ", ",", $head['title']);
        $this->render('signup', $head, $data);
    }
    
    public function validate_userswithphone($phone)
    {
        $r = $this->Public_model->countPublicUsersWithPhone($phone);
        // file_put_contents("a.txt", $r);
        if($r > 0){
            $this->form_validation->set_message('validate_userswithphone','This mobile number is already used!');
            return FALSE;
        }
        else
            return TRUE;
    }
    
    function validate_userswithemail($email)
    {
        if($this->Public_model->countPublicUsersWithEmail($email)){
            $this->form_validation->set_message('validate_userswithemail','This email is already used!');
            return FALSE;
        }
        else
            return TRUE;
    }

    public function myaccount($page = 0)
    {
        if(! isset($_SESSION['logged_user'])) redirect(LANG_URL . '/login');

        if (isset($_POST['verify_phone_number'])) {
            $_POST['id'] = $_SESSION['logged_user'];
            if ($this->Public_model->verify_otp($_POST['id'], $_POST['verify_phone']) === true)
                redirect(LANG_URL . '/myaccount');
        }
        
        $head = array();
        $data = array();
        $all_categories = $this->Public_model->getShopCategories();
        $data['home_categories'] = $head['all_categories'] = $this->getHomeCategories($all_categories);
        $data['all_categories'] = $all_categories;
        $head['title'] = lang('my_acc');
        $head['description'] = lang('my_acc');
        $head['keywords'] = str_replace(" ", ",", $head['title']);
        $data['userInfo'] = $this->Public_model->getUserProfileInfo($_SESSION['logged_user']);
        if($data['userInfo']['phone_verify'] != "Yes"){
            $this->load->Model('Home_admin_model');
            $companyName = $this->Home_admin_model->getValueStore('companyName');
            $footerContactPhone = $this->Home_admin_model->getValueStore('footerContactPhone');
            $this->sms_lib->sendSMS($data['userInfo']['phone'], "Your one time OTP is ".$data['userInfo']['phone_verify'].". Please visite ".base_url()."login and then verify. \r\nThanks\r\n".$companyName."\r\nHelp: ".$footerContactPhone);
        }            
        $rowscount = $this->Public_model->getUserOrdersHistoryCount($_SESSION['logged_user']);
        $data['orders_history'] = $this->Public_model->getUserOrdersHistory($_SESSION['logged_user'], $this->num_rows, $page);
        $data['links_pagination'] = pagination('myaccount', $rowscount, $this->num_rows, 2);
        $this->render('user', $head, $data);
    }
    
    public function orderhistory($page = 0)
    {
        if(! isset($_SESSION['logged_user'])) redirect(LANG_URL . '/login');

        $head = array();
        $data = array();
        $all_categories = $this->Public_model->getShopCategories();
        $data['home_categories'] = $head['all_categories'] = $this->getHomeCategories($all_categories);
        $data['all_categories'] = $all_categories;
        $head['title'] = lang('usr_order_history');
        $head['description'] = lang('usr_order_history');
        $head['keywords'] = str_replace(" ", ",", $head['title']);
        $data['userInfo'] = $this->Public_model->getUserProfileInfo($_SESSION['logged_user']);
        $rowscount = $this->Public_model->getUserOrdersHistoryCount($_SESSION['logged_user']);
        $data['orders_history'] = $this->Public_model->getUserOrdersHistory($_SESSION['logged_user'], $this->num_rows, $page);
        $data['links_pagination'] = pagination('myaccount', $rowscount, $this->num_rows, 2);
        $this->render('user_order', $head, $data);
    }
    
    public function updateprofile()
    {
        if(! isset($_SESSION['logged_user'])) redirect(LANG_URL . '/login');

        if (isset($_POST['update'])) {
            $_POST['id'] = $_SESSION['logged_user'];
            if($_POST['email'] != "")
                $count_emails = $this->Public_model->countPublicUsersWithEmail($_POST['email'], $_POST['id']);
            else $count_emails = 0;
            $count_phone = $this->Public_model->countPublicUsersWithPhone($_POST['phone'], $_POST['id']);
            if ($count_emails == 0 && $count_phone == 0) {
                $this->Public_model->updateProfile($_POST);
            }
            redirect(LANG_URL . '/myaccount');
        }

        $head = array();
        $data = array();
        $all_categories = $this->Public_model->getShopCategories();
        $data['home_categories'] = $head['all_categories'] = $this->getHomeCategories($all_categories);
        $data['all_categories'] = $all_categories;
        $head['title'] = lang('my_acc');
        $head['description'] = lang('my_acc');
        $head['keywords'] = str_replace(" ", ",", $head['title']);
        $data['userInfo'] = $this->Public_model->getUserProfileInfo($_SESSION['logged_user']);
        $this->render('user_profile', $head, $data);
    }
    
    public function changepassword()
    {
        if(! isset($_SESSION['logged_user'])) redirect(LANG_URL . '/login');

        if (isset($_POST['update'])) {
            $_POST['id'] = $_SESSION['logged_user'];
            $this->Public_model->updateUserPass($_POST);
            
            redirect(LANG_URL . '/myaccount');
        }
        $head = array();
        $data = array();
        $all_categories = $this->Public_model->getShopCategories();
        $data['home_categories'] = $head['all_categories'] = $this->getHomeCategories($all_categories);
        $data['all_categories'] = $all_categories;
        $head['title'] = lang('change_password');
        $head['description'] = lang('change_password');
        $head['keywords'] = str_replace(" ", ",", $head['title']);
        $data['userInfo'] = $this->Public_model->getUserProfileInfo($_SESSION['logged_user']);
        $this->render('user_change_pass', $head, $data);
    }

    public function logout()
    {
        unset($_SESSION['logged_user']);
        redirect(LANG_URL);
    }

    public function forgotten(){
        if (isset($_POST['recover-submit'])) {
            $result = $this->Public_model->getUserProfileInfoByPhone($_POST['phone']);
            if ($result == 1) {
                redirect(LANG_URL . '/login');
            } else {
                $this->session->set_flashdata('userError', lang('invalid_phone'));
            }
        }
        $head = array();
        $data = array();
        if($this->config->item('template') == 'divisima'){
            $all_categories = $this->Public_model->getShopCategories();
            $data['home_categories'] = $head['all_categories'] = $this->getHomeCategories($all_categories);
            $data['all_categories'] = $all_categories;
        }
        $head['title'] = lang('user_forgotten_page');
        $head['description'] = lang('user_forgotten_page');
        $head['keywords'] = str_replace(" ", ",", $head['title']);
        $this->render('recover_pass', $head, $data);
    }

    private function registerValidate()
    {
        $this->user_id = $this->Public_model->registerUser($_POST);
        return true;
    }

}
