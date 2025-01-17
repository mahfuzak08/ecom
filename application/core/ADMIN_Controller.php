<?php

/*
 * @Author:    Md. Mahfuzur Rahman
 *  Gitgub:    https://github.com/mahfuzak08/
 */
if (!defined('BASEPATH')) {
    exit('No direct script access allowed');
}

class ADMIN_Controller extends MX_Controller
{

    protected $username;
    protected $activePages;
    protected $allowed_img_types;
    protected $history;

    public function __construct()
    {
        parent::__construct();
        $this->load->library(array('form_validation'));
        $this->history = $this->config->item('admin_history');
        $this->activePages = $vars['activePages'] = $this->getActivePages();
        $numNotPreviewOrders = $this->Home_admin_model->newOrdersCheck();
        $this->allowed_img_types = $this->config->item('allowed_img_types');
        $vars['textualPages'] = getTextualPages($this->activePages);
        $vars['nonDynPages'] = $this->config->item('no_dynamic_pages');
        $vars['numNotPreviewOrders'] = $numNotPreviewOrders;
        $vars['warnings'] = $this->warningChecker();
        $vars['sitelogo'] = $this->Home_admin_model->getValueStore('sitelogo');
        $vars['companyName'] = htmlentities($this->Home_admin_model->getValueStore('companyName'));
        $vars['footerContactAddr'] = htmlentities($this->Home_admin_model->getValueStore('footerContactAddr'));
        $vars['footerContactPhone'] = htmlentities($this->Home_admin_model->getValueStore('footerContactPhone'));
        $vars['footerContactEmail'] = htmlentities($this->Home_admin_model->getValueStore('footerContactEmail'));
        $vars['showBrands'] = $this->Home_admin_model->getValueStore('showBrands');
        $vars['multiVendor'] = $this->Home_admin_model->getValueStore('multiVendor');
        $vars['labourCost'] = $this->Home_admin_model->getValueStore('labourCost');
        $vars['carryingCost'] = $this->Home_admin_model->getValueStore('carryingCost');
        $vars['wholesalePrice'] = $this->Home_admin_model->getValueStore('wholesalePrice');
        $vars['multiSize'] = $this->Home_admin_model->getValueStore('multiSize');
        $vars['hasStock'] = $this->Home_admin_model->getValueStore('hasStock');
        $vars['invImgShow'] = $this->Home_admin_model->getValueStore('invImgShow');
        $vars['wish_list'] = $this->Home_admin_model->getValueStore('wish_list');
        $vars['barcodeScanner'] = $this->Home_admin_model->getValueStore('barcodeScanner');
        $vars['virtualProducts'] = $this->Home_admin_model->getValueStore('virtualProducts');
        $vars['access'] = $this->Home_admin_model->get_user_access($this->session->userdata('logged_user_id'));
        $vars['addedJs'] = $this->Home_admin_model->getValueStore('addJs');
        $vars['g_recaptcha_site_key'] = $this->Home_admin_model->getValueStore('g_recaptcha_site_key');
		$vars['g_recaptcha_secret_key'] = $this->Home_admin_model->getValueStore('g_recaptcha_secret_key');
        $vars['template'] = $this->Home_admin_model->getValueStore('template');
        $this->load->vars($vars);
    }

    protected function login_check()
    {
        if (!$this->session->userdata('logged_in')) {
            redirect('admin');
        }
        $this->username = $this->session->userdata('logged_in');
    }

    protected function saveHistory($activity)
    {
        if ($this->history === true) {
            $this->load->model('History_model');
            $usr = $this->username;
            $this->History_model->setHistory($activity, $usr);
        }
    }

    protected function set_barcode($code)
	{
		$this->load->library('zend');
		$this->zend->load('Zend/Barcode');
		$barcode = Zend_Barcode::factory('code128', 'image', array('text' => $code, 'barHeight'=>20, 'drawText'=>false, 'font-size'=>12, 'factor'=>2), array('imageType' => 'png'));
        $path = './attachments/barcode/'.$code.'.gif';
        imagegif($barcode->draw(), $path);
        $code_img_base64 = base64_encode(file_get_contents($path));
        unlink($path);
        return $code_img_base64;
	}

    public function getActivePages()
    {
        $this->load->model('Pages_model');
        return $this->Pages_model->getPages(true, false);
    }

    private function warningChecker()
    {
        $errors = array();

        // Check application/language folder is writable
        if (!is_writable(APPPATH . 'language')) {
            $errors[] = 'Language folder is not writable!';
        }

        // Check application/logs folder is writable
        if (!is_writable(APPPATH . 'logs')) {
            $errors[] = 'Logs folder is not writable!';
        }

        // Check attachments folder is writable
        if (!is_writable('.' . DIRECTORY_SEPARATOR . 'attachments')) {
            $errors[] = 'Attachments folder is not writable!';
        } else {
            /*
             *  Check attachment directories exsists..
             *  ..and create him if no exsists
             */
            if (!file_exists('.' . DIRECTORY_SEPARATOR . 'attachments' . DIRECTORY_SEPARATOR . SHOP_DIR . DIRECTORY_SEPARATOR . 'blog_images')) {
                $old = umask(0);
                mkdir('.' . DIRECTORY_SEPARATOR . 'attachments' . DIRECTORY_SEPARATOR . SHOP_DIR . DIRECTORY_SEPARATOR . 'blog_images', 0777, true);
                umask($old);
            }
            if (!file_exists('.' . DIRECTORY_SEPARATOR . 'attachments' . DIRECTORY_SEPARATOR . 'barcode')) {
                $old = umask(0);
                mkdir('.' . DIRECTORY_SEPARATOR . 'attachments' . DIRECTORY_SEPARATOR . 'barcode', 0777, true);
                umask($old);
            }
            if (!file_exists('.' . DIRECTORY_SEPARATOR . 'attachments' . DIRECTORY_SEPARATOR . 'lang_flags')) {
                $old = umask(0);
                mkdir('.' . DIRECTORY_SEPARATOR . 'attachments' . DIRECTORY_SEPARATOR . 'lang_flags', 0777, true);
                umask($old);
            }
            if (!file_exists('.' . DIRECTORY_SEPARATOR . 'attachments' . DIRECTORY_SEPARATOR . SHOP_DIR . DIRECTORY_SEPARATOR . 'shop_images')) {
                $old = umask(0);
                mkdir('.' . DIRECTORY_SEPARATOR . 'attachments' . DIRECTORY_SEPARATOR . SHOP_DIR . DIRECTORY_SEPARATOR . 'shop_images', 0777, true);
                umask($old);
            }
            if (!file_exists('.' . DIRECTORY_SEPARATOR . 'attachments' . DIRECTORY_SEPARATOR . SHOP_DIR . DIRECTORY_SEPARATOR . 'site_app')) {
                $old = umask(0);
                mkdir('.' . DIRECTORY_SEPARATOR . 'attachments' . DIRECTORY_SEPARATOR . SHOP_DIR . DIRECTORY_SEPARATOR . 'site_app', 0777, true);
                umask($old);
            }
            if (!file_exists('.' . DIRECTORY_SEPARATOR . 'attachments' . DIRECTORY_SEPARATOR . SHOP_DIR . DIRECTORY_SEPARATOR . 'site_ico')) {
                $old = umask(0);
                mkdir('.' . DIRECTORY_SEPARATOR . 'attachments' . DIRECTORY_SEPARATOR . SHOP_DIR . DIRECTORY_SEPARATOR . 'site_ico', 0777, true);
                umask($old);
            }
            if (!file_exists('.' . DIRECTORY_SEPARATOR . 'attachments' . DIRECTORY_SEPARATOR . SHOP_DIR . DIRECTORY_SEPARATOR . 'site_logo')) {
                $old = umask(0);
                mkdir('.' . DIRECTORY_SEPARATOR . 'attachments' . DIRECTORY_SEPARATOR . SHOP_DIR . DIRECTORY_SEPARATOR . 'site_logo', 0777, true);
                umask($old);
            }
            if (!file_exists('.' . DIRECTORY_SEPARATOR . 'attachments' . DIRECTORY_SEPARATOR . SHOP_DIR . DIRECTORY_SEPARATOR . 'site_overview')) {
                $old = umask(0);
                mkdir('.' . DIRECTORY_SEPARATOR . 'attachments' . DIRECTORY_SEPARATOR . SHOP_DIR . DIRECTORY_SEPARATOR . 'site_overview', 0777, true);
                umask($old);
            }
        }
        return $errors;
    }

}
