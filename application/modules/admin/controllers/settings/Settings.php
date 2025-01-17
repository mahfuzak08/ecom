<?php

/*
 * @Author:    Md. Mahfuzur Rahman
 *  Gitgub:    https://github.com/mahfuzak08/
 */
if (!defined('BASEPATH')) {
    exit('No direct script access allowed');
}

class Settings extends ADMIN_Controller
{

    public function __construct()
    {
        parent::__construct();
        $this->load->model('Languages_model');
    }

    public function index()
    {
        $this->login_check();
        $data = array();
        $head = array();
        $head['title'] = 'Administration - Settings';
        $head['description'] = '!';
        $head['keywords'] = '';

        $this->postChecker();

        $data['siteLogo'] = $this->Home_admin_model->getValueStore('sitelogo');
        $data['siteOverview'] = $this->Home_admin_model->getValueStore('siteoverview');
        $data['siteico'] = $this->Home_admin_model->getValueStore('siteico');
        $data['siteAPK'] = $this->Home_admin_model->getValueStore('siteAPK');
		$data['companyName'] = $this->Home_admin_model->getValueStore('companyName');
        $data['naviText'] = $this->Home_admin_model->getValueStore('navitext');
        $data['footerCopyright'] = $this->Home_admin_model->getValueStore('footercopyright');
        $data['contactsPage'] = $this->Home_admin_model->getValueStore('contactspage');
        $data['footerContactAddr'] = $this->Home_admin_model->getValueStore('footerContactAddr');
        $data['footerContactPhone'] = $this->Home_admin_model->getValueStore('footerContactPhone');
        $data['footerContactEmail'] = $this->Home_admin_model->getValueStore('footerContactEmail');
		$data['footerContactEmailPass'] = $this->Home_admin_model->getValueStore('footerContactEmailPass');

        $data['footerSocialFacebook'] = $this->Home_admin_model->getValueStore('footerSocialFacebook');
        $data['footerSocialTwitter'] = $this->Home_admin_model->getValueStore('footerSocialTwitter');
        $data['footerSocialGooglePlus'] = $this->Home_admin_model->getValueStore('footerSocialGooglePlus');
        $data['footerSocialPinterest'] = $this->Home_admin_model->getValueStore('footerSocialPinterest');
        $data['footerSocialYoutube'] = $this->Home_admin_model->getValueStore('footerSocialYoutube');
        $data['wish_list'] = $this->Home_admin_model->getValueStore('wish_list');
        $data['officeTimeStart'] = $this->Home_admin_model->getValueStore('officeTimeStart');
        $data['officeTimeEnd'] = $this->Home_admin_model->getValueStore('officeTimeEnd');

        $data['smsApi'] = $this->Home_admin_model->getValueStore('smsApi');
        $data['smsURL'] = $this->Home_admin_model->getValueStore('smsURL');
        $data['smsSenderId'] = $this->Home_admin_model->getValueStore('smsSenderId');
        $data['smsUserName'] = $this->Home_admin_model->getValueStore('smsUserName');
        $data['smsPass'] = $this->Home_admin_model->getValueStore('smsPass');
        $data['appId'] = $this->Home_admin_model->getValueStore('appId');
        $data['measurementId'] = $this->Home_admin_model->getValueStore('measurementId');
        
        $data['contactsEmailTo'] = $this->Home_admin_model->getValueStore('contactsEmailTo');
        $data['googleMaps'] = $this->Home_admin_model->getValueStore('googleMaps');
        $data['footerAboutUs'] = $this->Home_admin_model->getValueStore('footerAboutUs');
        $data['shippingOrder'] = $this->Home_admin_model->getValueStore('shippingOrder');
        $data['addJs'] = $this->Home_admin_model->getValueStore('addJs');
        $data['g_recaptcha_site_key'] = $this->Home_admin_model->getValueStore('g_recaptcha_site_key');
        $data['g_recaptcha_secret_key'] = $this->Home_admin_model->getValueStore('g_recaptcha_secret_key');
        $data['publicQuantity'] = $this->Home_admin_model->getValueStore('publicQuantity');
        $data['publicVisitor'] = $this->Home_admin_model->getValueStore('publicVisitor');
        $data['publicDateAdded'] = $this->Home_admin_model->getValueStore('publicDateAdded');
        $data['googleApi'] = $this->Home_admin_model->getValueStore('googleApi');
        $data['outOfStock'] = $this->Home_admin_model->getValueStore('outOfStock');
        $data['page_width'] = $this->Home_admin_model->getValueStore('page_width');
        $data['printer_type'] = $this->Home_admin_model->getValueStore('printer_type');
        $data['details_img'] = $this->Home_admin_model->getValueStore('details_img');
        $data['logo_in'] = $this->Home_admin_model->getValueStore('logo_in');
        
        $data['inv_addLine_1'] = $this->Home_admin_model->getValueStore('inv_addLine_1');
        $data['inv_addLine_2'] = $this->Home_admin_model->getValueStore('inv_addLine_2');
        $data['inv_addLine_3'] = $this->Home_admin_model->getValueStore('inv_addLine_3');
        $data['inv_addLine_4'] = $this->Home_admin_model->getValueStore('inv_addLine_4');
        
        $data['hasStock'] = $this->Home_admin_model->getValueStore('hasStock');
        $data['invImgShow'] = $this->Home_admin_model->getValueStore('invImgShow');
        $data['invDesShow'] = $this->Home_admin_model->getValueStore('invDesShow');
        $data['moreInfoBtn'] = $this->Home_admin_model->getValueStore('moreInfoBtn');
        $data['showBrands'] = $this->Home_admin_model->getValueStore('showBrands');
        $data['virtualProducts'] = $this->Home_admin_model->getValueStore('virtualProducts');
        $data['showInSlider'] = $this->Home_admin_model->getValueStore('showInSlider');
        $data['multiVendor'] = $this->Home_admin_model->getValueStore('multiVendor');
        $data['labourCost'] = $this->Home_admin_model->getValueStore('labourCost');
        $data['carryingCost'] = $this->Home_admin_model->getValueStore('carryingCost');
        $data['wholesalePrice'] = $this->Home_admin_model->getValueStore('wholesalePrice');
        $data['salesReturn'] = $this->Home_admin_model->getValueStore('salesReturn');
        $data['barcodeScanner'] = $this->Home_admin_model->getValueStore('barcodeScanner');
        $data['multiSize'] = $this->Home_admin_model->getValueStore('multiSize');
        $data['cookieLawInfo'] = $this->getCookieLaw();
        $data['languages'] = $this->Languages_model->getLanguages();
        $data['law_themes'] = array_diff(scandir('.' . DIRECTORY_SEPARATOR . 'assets' . DIRECTORY_SEPARATOR . 'imgs' . DIRECTORY_SEPARATOR . 'cookie-law-themes' . DIRECTORY_SEPARATOR), array('..', '.'));
        $data['cookieLawInfo'] = $this->getCookieLaw();
        $this->load->view('_parts/header', $head);
        $this->load->view('settings/settings', $data);
        $this->load->view('_parts/footer');
        $this->saveHistory('Go to Settings Page');
    }

    private function postChecker()
    {
        if (isset($_POST['uploadimage'])) {
            $config['upload_path'] = '.' . DIRECTORY_SEPARATOR . 'attachments' . DIRECTORY_SEPARATOR . SHOP_DIR . DIRECTORY_SEPARATOR . 'site_logo' . DIRECTORY_SEPARATOR;
            $config['allowed_types'] = 'gif|jpg|png';
            $config['max_size'] = 1500;
            $config['max_width'] = 1024;
            $config['max_height'] = 768;

            $this->load->library('upload', $config);

            if (!$this->upload->do_upload('sitelogo')) {
                $this->session->set_flashdata('resultSiteLogoPublish', $this->upload->display_errors());
            } else {
                $data = array('upload_data' => $this->upload->data());
                $newImage = $data['upload_data']['file_name'];
                $this->Home_admin_model->setValueStore('sitelogo', $newImage);
                $this->saveHistory('Change site logo');
                $this->session->set_flashdata('resultSiteLogoPublish', 'New logo is set!');
            }
            redirect('admin/settings');
        }
        if (isset($_POST['uploadoverview'])) {
            $config['upload_path'] = '.' . DIRECTORY_SEPARATOR . 'attachments' . DIRECTORY_SEPARATOR . SHOP_DIR . DIRECTORY_SEPARATOR . 'site_overview' . DIRECTORY_SEPARATOR;
            $config['allowed_types'] = 'gif|jpg|png';
            $config['max_size'] = 2048;
            $config['max_width'] = 1366;
            $config['max_height'] = 768;

            $this->load->library('upload', $config);

            if (!$this->upload->do_upload('siteoverview')) {
                $this->session->set_flashdata('resultSiteOverview', $this->upload->display_errors());
            } else {
                $data = array('upload_data' => $this->upload->data());
                $newImage = $data['upload_data']['file_name'];
                $this->Home_admin_model->setValueStore('siteoverview', $newImage);
                $this->saveHistory('Change site overview');
                $this->session->set_flashdata('resultSiteOverview', 'New site overview is set!');
            }
            redirect('admin/settings');
        }
        if (isset($_POST['uploadico'])) {
            $config['upload_path'] = '.' . DIRECTORY_SEPARATOR . 'attachments' . DIRECTORY_SEPARATOR . SHOP_DIR . DIRECTORY_SEPARATOR . 'site_ico' . DIRECTORY_SEPARATOR;
            $config['allowed_types'] = 'ico';
            $config['max_size'] = 100;
            $config['max_width'] = 100;
            $config['max_height'] = 100;

            $this->load->library('upload', $config);

            if (!$this->upload->do_upload('siteico')) {
                $this->session->set_flashdata('resultSiteIco', $this->upload->display_errors());
            } else {
                $data = array('upload_data' => $this->upload->data());
                $newImage = $data['upload_data']['file_name'];
                $this->Home_admin_model->setValueStore('siteico', $newImage);
                $this->saveHistory('Change site favicon ico');
                $this->session->set_flashdata('resultSiteIco', 'New site favicon ico is set!');
            }
            redirect('admin/settings');
        }
        if (isset($_POST['uploadAPK'])) {
            $config['upload_path'] = '.' . DIRECTORY_SEPARATOR . 'attachments' . DIRECTORY_SEPARATOR . SHOP_DIR . DIRECTORY_SEPARATOR . 'site_app' . DIRECTORY_SEPARATOR;
            $config['allowed_types'] = 'gif|jpg|png|apk';
            
            $this->load->library('upload', $config);

            if (!$this->upload->do_upload('siteAPK')) {
                $this->session->set_flashdata('resultSiteAPKPublish', mime_content_type($config['upload_path'].'Bazzar2021.apk'));
                // $this->session->set_flashdata('resultSiteAPKPublish', $this->upload->display_errors());
            } else {
                $data = array('upload_data' => $this->upload->data());
                $newAPK = $data['upload_data']['file_name'];
                $this->Home_admin_model->setValueStore('siteAPK', $newAPK);
                $this->saveHistory('Change site APK');
                $this->session->set_flashdata('resultSiteAPKPublish', 'New APK is set!');
            }
            redirect('admin/settings');
        }
        if (isset($_POST['companyName'])) {
            $this->Home_admin_model->setValueStore('companyName', $_POST['companyName']);
            $this->session->set_flashdata('resultCompanyName', 'Company name text is set!');
            $this->saveHistory('Change company name text');
            redirect('admin/settings');
        }
		if (isset($_POST['naviText'])) {
            $this->Home_admin_model->setValueStore('navitext', $_POST['naviText']);
            $this->session->set_flashdata('resultNaviText', 'New navigation text is set!');
            $this->saveHistory('Change navigation text');
            redirect('admin/settings');
        }
        if (isset($_POST['footerCopyright'])) {
            $this->Home_admin_model->setValueStore('footercopyright', $_POST['footerCopyright']);
            $this->session->set_flashdata('resultFooterCopyright', 'New navigation text is set!');
            $this->saveHistory('Change footer copyright');
            redirect('admin/settings');
        }
        if (isset($_POST['smsApi'])) {
            $this->Home_admin_model->setValueStore('smsApi', $_POST['smsApi']);
            $this->Home_admin_model->setValueStore('smsURL', $_POST['smsURL']);
            $this->Home_admin_model->setValueStore('smsSenderId', $_POST['smsSenderId']);
            $this->Home_admin_model->setValueStore('smsUserName', $_POST['smsUserName']);
            $this->Home_admin_model->setValueStore('smsPass', $_POST['smsPass']);
            $this->Home_admin_model->setValueStore('appId', $_POST['appId']);
            $this->Home_admin_model->setValueStore('measurementId', $_POST['measurementId']);
            $this->session->set_flashdata('resultSMSAPI', 'SMS API api key are updated!');
            $this->saveHistory('Update SMS Api Key');
            redirect('admin/settings');
        }
        if (isset($_POST['contactsPage'])) {
            $this->Home_admin_model->setValueStore('contactspage', $_POST['contactsPage']);
            $this->session->set_flashdata('resultContactspage', 'Contacts page is updated!');
            $this->saveHistory('Change contacts page');
            redirect('admin/settings');
        }
        if (isset($_POST['footerContacts'])) {
            $this->Home_admin_model->setValueStore('footerContactAddr', $_POST['footerContactAddr']);
            $this->Home_admin_model->setValueStore('footerContactPhone', $_POST['footerContactPhone']);
            $this->Home_admin_model->setValueStore('footerContactEmail', $_POST['footerContactEmail']);
			$this->Home_admin_model->setValueStore('footerContactEmailPass', $_POST['footerContactEmailPass']);
            $this->session->set_flashdata('resultfooterContacts', 'Contacts on footer are updated!');
            $this->saveHistory('Change footer contacts');
            redirect('admin/settings');
        }
		if (isset($_POST['wish_list'])) {
            $this->Home_admin_model->setValueStore('wish_list', $_POST['wish_list']);
            $this->session->set_flashdata('resultWish_list', 'Wish list are updated!');
            $this->saveHistory('Change wish list settings');
            redirect('admin/settings');
        }
		if (isset($_POST['officeTime'])) {
            $this->Home_admin_model->setValueStore('officeTimeStart', $_POST['officeTimeStart']);
            $this->Home_admin_model->setValueStore('officeTimeEnd', $_POST['officeTimeEnd']);
            $this->session->set_flashdata('resultOfficeTime', 'Office time are updated!');
            $this->saveHistory('Change office time');
            redirect('admin/settings');
        }
        if (isset($_POST['footerSocial'])) {
            $this->Home_admin_model->setValueStore('footerSocialFacebook', $_POST['footerSocialFacebook']);
            $this->Home_admin_model->setValueStore('footerSocialTwitter', $_POST['footerSocialTwitter']);
            $this->Home_admin_model->setValueStore('footerSocialGooglePlus', $_POST['footerSocialGooglePlus']);
            $this->Home_admin_model->setValueStore('footerSocialPinterest', $_POST['footerSocialPinterest']);
            $this->Home_admin_model->setValueStore('footerSocialYoutube', $_POST['footerSocialYoutube']);
            $this->session->set_flashdata('resultfooterSocial', 'Social on footer are updated!');
            $this->saveHistory('Change footer contacts');
            redirect('admin/settings');
        }
        if (isset($_POST['googleMaps'])) {
            $this->Home_admin_model->setValueStore('googleMaps', $_POST['googleMaps']);
            $this->Home_admin_model->setValueStore('googleApi', $_POST['googleApi']);
            $this->session->set_flashdata('resultGoogleMaps', 'Google maps coordinates and api key are updated!');
            $this->saveHistory('Update Google Maps Coordinates and Api Key');
            redirect('admin/settings');
        }
        if (isset($_POST['footerAboutUs'])) {
            $this->Home_admin_model->setValueStore('footerAboutUs', $_POST['footerAboutUs']);
            $this->session->set_flashdata('resultFooterAboutUs', 'Footer about us text changed!');
            $this->saveHistory('Change footer about us info');
            redirect('admin/settings');
        }
        if (isset($_POST['contactsEmailTo'])) {
            $this->Home_admin_model->setValueStore('contactsEmailTo', $_POST['contactsEmailTo']);
            $this->session->set_flashdata('resultEmailTo', 'Email changed!');
            $this->saveHistory('Change where going emails from contact form');
            redirect('admin/settings');
        }
        if (isset($_POST['shippingOrder'])) {
            $this->Home_admin_model->setValueStore('shippingOrder', $_POST['shippingOrder']);
            $this->session->set_flashdata('shippingOrder', 'Shipping Order price chagned!');
            $this->saveHistory('Change Shipping free for order more than ' . $_POST['shippingOrder']);
            redirect('admin/settings');
        }
        if (isset($_POST['addJs'])) {
            $this->Home_admin_model->setValueStore('addJs', $_POST['addJs']);
            $this->session->set_flashdata('addJs', 'JavaScript code is added');
            $this->saveHistory('Add JS to website');
            redirect('admin/settings');
        }
        if (isset($_POST['g_recaptcha_secret_key'])) {
            $this->Home_admin_model->setValueStore('g_recaptcha_site_key', $_POST['g_recaptcha_site_key']);
            $this->Home_admin_model->setValueStore('g_recaptcha_secret_key', $_POST['g_recaptcha_secret_key']);
            $this->session->set_flashdata('g_recaptcha_key', 'g-recaptcha key are added');
            $this->saveHistory('g-recaptcha key are added to website');
            redirect('admin/settings');
        }
        if (isset($_POST['publicVisitor'])) {
            $this->Home_admin_model->setValueStore('publicVisitor', $_POST['publicVisitor']);
            $this->session->set_flashdata('publicVisitor', 'Public visitor visibility changed');
            $this->saveHistory('Change publicVisitor visibility');
            redirect('admin/settings');
        }
        if (isset($_POST['publicQuantity'])) {
            $this->Home_admin_model->setValueStore('publicQuantity', $_POST['publicQuantity']);
            $this->session->set_flashdata('publicQuantity', 'Public quantity visibility changed');
            $this->saveHistory('Change publicQuantity visibility');
            redirect('admin/settings');
        }
        if (isset($_POST['publicDateAdded'])) {
            $this->Home_admin_model->setValueStore('publicDateAdded', $_POST['publicDateAdded']);
            $this->session->set_flashdata('publicDateAdded', 'Public date added visibility changed');
            $this->saveHistory('Change public date added visibility');
            redirect('admin/settings');
        }
        if (isset($_POST['outOfStock'])) {
            $this->Home_admin_model->setValueStore('outOfStock', $_POST['outOfStock']);
            $this->session->set_flashdata('outOfStock', 'Out of stock settings visibility change');
            $this->saveHistory('Change visibility of final checkout page');
            redirect('admin/settings');
        }
        if (isset($_POST['page_width'])) {
            $this->Home_admin_model->setValueStore('page_width', $_POST['page_width']);
            $this->session->set_flashdata('page_width', 'Invoice page size change');
            $this->saveHistory('Invoice page size change');
            redirect('admin/sale?settings');
        }
        if (isset($_POST['printer_type'])) {
            $this->Home_admin_model->setValueStore('printer_type', $_POST['printer_type']);
            $this->session->set_flashdata('printer_type', 'Invoice printer type change');
            $this->saveHistory('Invoice printer type change');
            redirect('admin/sale?settings');
        }
        if (isset($_POST['details_img'])) {
            $this->Home_admin_model->setValueStore('details_img', $_POST['details_img']);
            $this->session->set_flashdata('details_img', 'Invoice details change');
            $this->saveHistory('Invoice details change');
            redirect('admin/sale?settings');
        }
        if (isset($_POST['logo_in'])) {
            $this->Home_admin_model->setValueStore('logo_in', $_POST['logo_in']);
            $this->session->set_flashdata('logo_in', 'Invoice logo position change');
            $this->saveHistory('Invoice logo position change');
            redirect('admin/sale?settings');
        }
        
        if (isset($_POST['inv_addLine_1'])) {
            $this->Home_admin_model->setValueStore('inv_addLine_1', $_POST['inv_addLine_1']);
            $this->session->set_flashdata('inv_addLine_1', 'Invoice address line 1 change');
            $this->saveHistory('Invoice address line 1 change');
            $this->Home_admin_model->setValueStore('inv_addLine_2', $_POST['inv_addLine_2']);
            $this->session->set_flashdata('inv_addLine_2', 'Invoice address line 2 change');
            $this->saveHistory('Invoice address line 2 change');
            $this->Home_admin_model->setValueStore('inv_addLine_3', $_POST['inv_addLine_3']);
            $this->session->set_flashdata('inv_addLine_3', 'Invoice address line 3 change');
            $this->saveHistory('Invoice address line 3 change');
            $this->Home_admin_model->setValueStore('inv_addLine_4', $_POST['inv_addLine_4']);
            $this->session->set_flashdata('inv_addLine_4', 'Invoice address line 4 change');
            $this->saveHistory('Invoice address line 4 change');
            redirect('admin/sale?settings');
        }
        
        if (isset($_POST['hasStock'])) {
            $this->Home_admin_model->setValueStore('hasStock', $_POST['hasStock']);
            $this->session->set_flashdata('hasStock', 'POS sale rule change');
            $this->saveHistory('POS sale rule change');
            redirect('admin/settings');
        }
        if (isset($_POST['invImgShow'])) {
            $this->Home_admin_model->setValueStore('invImgShow', $_POST['invImgShow']);
            $this->session->set_flashdata('invImgShow', 'POS invoice register show the image');
            $this->saveHistory('POS invoice register show the image');
            redirect('admin/settings');
        }
        if (isset($_POST['invDesShow'])) {
            $this->Home_admin_model->setValueStore('invDesShow', $_POST['invDesShow']);
            $this->session->set_flashdata('invDesShow', 'POS invoice show description');
            $this->saveHistory('POS invoice show description');
            redirect('admin/settings');
        }
        if (isset($_POST['moreInfoBtn'])) {
            $this->Home_admin_model->setValueStore('moreInfoBtn', $_POST['moreInfoBtn']);
            $this->session->set_flashdata('moreInfoBtn', 'Button More Information visibility is changed');
            $this->saveHistory('Change visibility of More Information button in products list');
            redirect('admin/settings');
        }
        if (isset($_POST['showBrands'])) {
            $this->Home_admin_model->setValueStore('showBrands', $_POST['showBrands']);
            $this->session->set_flashdata('showBrands', 'Brands visibility changed');
            $this->saveHistory('Brands visibility changed');
            redirect('admin/settings');
        }
        if (isset($_POST['virtualProducts'])) {
            $this->Home_admin_model->setValueStore('virtualProducts', $_POST['virtualProducts']);
            $this->session->set_flashdata('virtualProducts', 'Virtual products visibility changed');
            $this->saveHistory('Virtual products visibility changed');
            redirect('admin/settings');
        }
        if (isset($_POST['showInSlider'])) {
            $this->Home_admin_model->setValueStore('showInSlider', $_POST['showInSlider']);
            $this->session->set_flashdata('showInSlider', 'In Slider products visibility changed');
            $this->saveHistory('In Slider products visibility changed');
            redirect('admin/settings');
        }
        if (isset($_POST['multiVendor'])) {
            $this->Home_admin_model->setValueStore('multiVendor', $_POST['multiVendor']);
            $this->session->set_flashdata('multiVendor', 'Multi Vendor Support changed');
            $this->saveHistory('Multi Vendor Support changed');
            redirect('admin/settings');
        }
        if (isset($_POST['labourCost'])) {
            $this->Home_admin_model->setValueStore('labourCost', $_POST['labourCost']);
            $this->session->set_flashdata('labourCost', 'Invoice Labour Cost Support Changed');
            $this->saveHistory('Invoice Labour Cost Support Changed');
            redirect('admin/settings');
        }
		if (isset($_POST['carryingCost'])) {
            $this->Home_admin_model->setValueStore('carryingCost', $_POST['carryingCost']);
            $this->session->set_flashdata('carryingCost', 'Invoice Carrying Cost Support Changed');
            $this->saveHistory('Invoice Carrying Cost Support Changed');
            redirect('admin/settings');
        }
		if (isset($_POST['wholesalePrice'])) {
            $this->Home_admin_model->setValueStore('wholesalePrice', $_POST['wholesalePrice']);
            $this->session->set_flashdata('wholesalePrice', 'Wholesale Price Enabled Successfully');
            $this->saveHistory('Wholesale Price Enabled Successfully');
            redirect('admin/settings');
        }
        if (isset($_POST['salesReturn'])) {
            $this->Home_admin_model->setValueStore('salesReturn', $_POST['salesReturn']);
            $this->session->set_flashdata('salesReturn', 'Sales Return Policy Changed');
            $this->saveHistory('Sales Return Policy Changed');
            redirect('admin/settings');
        }
        if (isset($_POST['barcodeScanner'])) {
            $this->Home_admin_model->setValueStore('barcodeScanner', $_POST['barcodeScanner']);
            $this->session->set_flashdata('barcodeScanner', 'Sales Return Policy Changed');
            $this->saveHistory('Sales Return Policy Changed');
            redirect('admin/settings');
        }
		if (isset($_POST['multiSize'])) {
            $this->Home_admin_model->setValueStore('multiSize', $_POST['multiSize']);
            $this->session->set_flashdata('multiSize', 'Product Multi Size Support Changed');
            $this->saveHistory('Product Multi Size Support Changed');
            redirect('admin/settings');
        }
        if (isset($_POST['setCookieLaw'])) {
            unset($_POST['setCookieLaw']);
            $this->setCookieLaw($_POST);
            $this->saveHistory('Cookie law information changed');
            redirect('admin/settings');
        }
    }

    private function setCookieLaw($post)
    {
        $this->Home_admin_model->setCookieLaw($post);
    }

    private function getCookieLaw()
    {
        return $this->Home_admin_model->getCookieLaw();
    }

}
