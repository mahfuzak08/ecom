<?php

defined('BASEPATH') OR exit('No direct script access allowed');

/**
 * Count_visitor class
 * Help us to count and store visitor info
 *
 */
class Count_visitor {
    private $CI;

    public function __construct()
    {
        // Get the CodeIgniter reference
        $this->CI = &get_instance();
        $this->CI->load->library('user_agent');
    }

    public function set_visitor_count()
    {
        $data = array();
        $data['browser'] = $this->CI->agent->browser();
        $data['browser_version'] = $this->CI->agent->version();
        $data['os'] = $this->CI->agent->platform();
        $data['ip_address'] = $this->CI->input->ip_address();
        $data['is_robot'] = $this->CI->agent->is_robot();
        $data['is_mobile'] = $this->CI->agent->is_mobile();
		$data['session_id'] = $this->CI->session->session_id;
        $this->CI->Public_model->update_counter($data);
    }

    public function get_visitor_count($date=true, $type='sum')
    {
        return $this->CI->Public_model->get_visitor_counter($date, $type);
    }
}
