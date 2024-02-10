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
	}

	/*
	 * SMS sending function
	 * Example of use: $response = sendSMS('4477777777', 'My test message');
	 */
	public function sendSMS($phone, $message)
	{
	    $apiUrl = $this->CI->Home_admin_model->getValueStore('smsURL');
	    $apiKey = $this->CI->Home_admin_model->getValueStore('smsApi');
        $senderid = $this->CI->Home_admin_model->getValueStore('smsSenderId');

		$response = "";

		// if any of the parameters is empty return with a FALSE
		if(empty($senderid) || empty($phone) || empty($message) || empty($apiKey))
		{
			echo $senderid . ' ' . $phone . ' ' . $message . ' ' . $apiKey;
		}
		else
		{
			// make sure passed string is url encoded
			$message = rawurlencode($message);

			// add call to send a message via 3rd party API here
			// Some examples
			$url = "$apiUrl?api_key=$apiKey&type=text&contacts=$phone&senderid=$senderid&msg=$message";

			$c = curl_init();
			curl_setopt($c, CURLOPT_RETURNTRANSFER, 1);
			curl_setopt($c, CURLOPT_URL, $url);
			$response = curl_exec($c);
			curl_close($c);
            // print_r($response);
            
            if (strpos($response, 'CamID') !== false && strpos($response, '"status":"SUCCESS"') !== false) {
                return true;
            }
		}

		return FALSE;
	}
}

?>
