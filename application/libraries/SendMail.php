<?php

defined('BASEPATH') OR exit('No direct script access allowed');
require 'PHPMailer/PHPMailerAutoload.php';

class SendMail
{

	private $CI;
    public $mail;

    public function __construct()
    {
		$this->CI =& get_instance();
		$from = $this->CI->Home_admin_model->getValueStore('footerContactEmail');
		$display = $this->CI->Home_admin_model->getValueStore('companyName');
		$pass = $this->CI->Home_admin_model->getValueStore('footerContactEmailPass');
		
		$this->mail = new PHPMailer; 
		$this->mail->isSMTP(); 
// 		$this->mail->SMTPDebug = 2; 
		$this->mail->Debugoutput = 'html'; 
		$this->mail->Host = 'server121.web-hosting.com'; 
		$this->mail->Port = 465; 
		$this->mail->SMTPSecure = 'ssl'; 
		$this->mail->SMTPAuth = true; 
		$this->mail->Username = $from; 
		$this->mail->Password = $pass;
		$this->mail->SetFrom($from, $display);
		$this->mail->CharSet = 'UTF-8';
		$this->mail->isHTML(true); 
    }

    public function sendTo($toEmail, $recipientName, $subject, $msg)
    {
        $this->mail->addAddress($toEmail, $recipientName);
        $this->mail->Subject = $subject;
        $this->mail->Body = $msg;
        $result = $this->mail->send();
        if (!$result) {
            log_message('error', 'Mailer Error: ' . $this->mail->ErrorInfo);
            return false;
        }
        return true;
    }

}
