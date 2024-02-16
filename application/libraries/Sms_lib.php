<?php

defined('BASEPATH') OR exit('No direct script access allowed');

/**
 * SMS library
 *
 * Library with utilities to send texts via SMS Gateway (requires proxy implementation)
 */

class Sms_lib
{
	private $CI;

  	public function __construct()
	{
		$this->CI =& get_instance();
        $this->CI->load->library('session');
        $this->CI->load->database();
	}

	public function toSms($mobile, $body)
    {
        $url = $this->CI->Home_admin_model->getValueStore('smsURL');
        $apiKey = $this->CI->Home_admin_model->getValueStore('smsApi');
        $senderId = $this->CI->Home_admin_model->getValueStore('smsSenderId');

        $data = [
            "url"=>$url,
            "user_id"=>$_SESSION['logged_user_id'],
            "api_key" => $apiKey,
            "type" => "text",
            "contacts" => $mobile,
            "senderid" => $senderId,
            "label" => 'transactional',
            "msg" => $body
        ];

        try{
            if (!$this->CI->db->insert('sms_logs', $data)) {
                print_r($this->CI->db->error());
            }
            $smslogid = $this->CI->db->insert_id();

            $ch = curl_init();
            curl_setopt($ch, CURLOPT_URL, $url);
            curl_setopt($ch, CURLOPT_POST, 1);
            curl_setopt($ch, CURLOPT_POSTFIELDS, $data);
            curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
            curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, false);
            $response = curl_exec($ch);
            curl_close($ch);
            
            $update = ["response"=>$response];
            
            if (!$this->CI->db->where('id', $smslogid)->update('sms_logs', $update)) {
                print_r($this->CI->db->error());
            }

        }catch(\Exception $e) {
            flash()->addError($e);
        }
    }
    
    public function getBalance()
    {
        $url = $this->CI->Home_admin_model->getValueStore('smsURL');
        $apiKey = $this->CI->Home_admin_model->getValueStore('smsApi');
        $url = str_replace('smsapi', 'miscapi', $url);
        $url = $url.'/'.$apiKey.'/getBalance';

        try{            
            $ch = curl_init();
            curl_setopt($ch, CURLOPT_URL, $url);
            curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
            curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, false);
            $response = curl_exec($ch);
            curl_close($ch);
            $bal = (float) str_replace('Your Balance is:BDT ', '', $response);
            return $bal + $bal * 0.25;
        }catch(\Exception $e) {
            flash()->addError($e);
        }
    }

	/*
	 * SMS sending function
	 * Example of use: $response = sendSMS('4477777777', 'My test message');
	 */
	// public function sendSMS($phone, $message)
	// {
	//     $apiUrl = $this->CI->Home_admin_model->getValueStore('smsURL');
	//     $apiKey = $this->CI->Home_admin_model->getValueStore('smsApi');
    //     $senderid = $this->CI->Home_admin_model->getValueStore('smsSenderId');

	// 	$response = "";

	// 	// if any of the parameters is empty return with a FALSE
	// 	if(empty($senderid) || empty($phone) || empty($message) || empty($apiKey))
	// 	{
	// 		echo $senderid . ' ' . $phone . ' ' . $message . ' ' . $apiKey;
	// 	}
	// 	else
	// 	{
	// 		// make sure passed string is url encoded
	// 		$message = rawurlencode($message);

	// 		// add call to send a message via 3rd party API here
	// 		// Some examples
	// 		$url = "$apiUrl?api_key=$apiKey&type=text&contacts=$phone&senderid=$senderid&msg=$message";

	// 		$c = curl_init();
	// 		curl_setopt($c, CURLOPT_RETURNTRANSFER, 1);
	// 		curl_setopt($c, CURLOPT_URL, $url);
	// 		$response = curl_exec($c);
	// 		curl_close($c);
    //         // print_r($response);
            
    //         if (strpos($response, 'CamID') !== false && strpos($response, '"status":"SUCCESS"') !== false) {
    //             return true;
    //         }
	// 	}

	// 	return FALSE;
	// }
}

?>
