<?php

/*
 * @Author:    Md. Mahfuzur Rahman
 *  Gitgub:    https://github.com/mahfuzak08/
 */
if (!defined('BASEPATH')) {
    exit('No direct script access allowed');
}

class Visitor extends ADMIN_Controller
{

    public function __construct()
    {
        parent::__construct();
    }

    public function index()
    {
        $this->login_check();
        $data = array();
        $head = array();
        $head['title'] = 'Administration - Visitor';
        $head['description'] = 'Visitor';
        $head['keywords'] = '';
        if (isset($_POST['template'])) {
            $this->Home_admin_model->setValueStore('template', $_POST['template']);
            redirect('admin/templates');
        }
        // $data['seleced_template'] = $this->Home_admin_model->getValueStore('template');
        $data['visitors'] = $this->Public_model->get_visitor_counter(false, 'all');
        $this->load->view('_parts/header', $head);
        $this->load->view('settings/visitor', $data);
        $this->load->view('_parts/footer');
        $this->saveHistory('Go to Visitor Page');
    }

}
