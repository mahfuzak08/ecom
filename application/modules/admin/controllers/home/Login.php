<?php

/*
 * @Author:    Md. Mahfuzur Rahman
 *  Gitgub:    https://github.com/mahfuzak08/
 */
if (!defined('BASEPATH')) {
    exit('No direct script access allowed');
}

class Login extends ADMIN_Controller
{

    public function index()
    {
        $data = array();
        $head = array();
        $head['title'] = 'Administration - Login';
        $head['description'] = '';
        $head['keywords'] = '';
		$this->load->helper('cookie');
        $this->load->view('_parts/header', $head);
        if ($this->session->userdata('logged_in')) {
            redirect('admin/home');
        } else {
			if(! $this->has_login_block()){
			    $this->form_validation->set_rules('username', 'Username', 'trim|required');
				$this->form_validation->set_rules('password', 'Password', 'trim|required');
				if ($this->form_validation->run($this)) {
				    $g_recaptcha_secret_key = $this->load->get_var('g_recaptcha_secret_key');
    			    if(isset($_POST["LoginPost"]) && $g_recaptcha_secret_key != ""){
    			        if(isset($_POST["g-recaptcha-response"])){
    					    $gresponse = $_POST["g-recaptcha-response"];
    						$ip = $_SERVER["REMOTE_ADDR"];
    						echo $ip;
    						$url = "https://www.google.com/recaptcha/api/siteverify?secret=$g_recaptcha_secret_key&response=$gresponse&remoteip=$ip";
    						$recap_res = file_get_contents($url);
    						$recap_res_json = json_decode($recap_res);
    						if($recap_res_json->success != true){
    							$this->session->set_flashdata('err_login', 'Recaptcha validation error. Please try again.');
    							redirect('admin');
    						}
    					}else{
    						$this->session->set_flashdata('err_login', 'Recaptcha not found');
    						redirect('admin');
    					}
    				}
					$result = $this->Home_admin_model->loginCheck($_POST);
					if (!empty($result)) {
					   // print_r($_POST); exit;
						$_SESSION['last_login'] = $result['last_login'];
						$this->session->set_userdata('logged_in', $result['username']);
						$this->session->set_userdata('logged_user_id', $result['id']);
						$this->saveHistory('User ' . $result['username'] . ' logged in');
						setcookie("user_ip_fa", null);
						setcookie("user_ip_ft", null);
						redirect('admin/home');
					} else {
						$this->Home_admin_model->login_attempts_failed($_POST['username']);
						setcookie("user_ip_fa", intval(@$_COOKIE["user_ip_fa"]) + 1);
						setcookie("user_ip_ft", time());
						$this->saveHistory('Cant login with - User: ' . $_POST['username'] . ' and Pass: ' . $_POST['username']);
						$this->session->set_flashdata('err_login', 'Wrong username or password!');
						redirect('admin');
					}
				}
				$this->load->view('home/login');
			}else{
				$this->session->set_flashdata('err_login', 'Please try with correct user name and password after '.$_COOKIE['display_ft']);
				$this->load->view('home/login_faild');
			}
        }
        $this->load->view('_parts/footer');
    }

	function has_login_block(){
		// return false;
		$fa = intval(@$_COOKIE["user_ip_fa"]);
		$end_block_time = 0;
		
		if($fa > 2){
			switch($fa){
				case 3: $end_block_time = 1800; setcookie("display_ft", "30 minutes."); break; // 30min
				case 4: $end_block_time = 3600; setcookie("display_ft", "60 minutes."); break; // 60min
				case 5: $end_block_time = 7200; setcookie("display_ft", "2 hours."); break; // 120min
				case 6: $end_block_time = 86400; setcookie("display_ft", "1 day."); break; // 1 day
				default: $end_block_time = 1314000; setcookie("display_ft", "temporary disabled unblock."); break; // temporary disabled for 1 year
			}
		}
		
		// echo time() < intval($_COOKIE["user_ip_ft"] + $end_block_time);
		if(time() < intval(@$_COOKIE["user_ip_ft"] + $end_block_time))
			return true;
		else
			return false;
	}
}
