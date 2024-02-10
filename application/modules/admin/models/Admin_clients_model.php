<?php

class Admin_clients_model extends CI_Model
{

    public function __construct()
    {
        parent::__construct();
    }

    public function changeClientStatus($id, $data)
    {
        $this->db->where('id', $id);
        if (!$this->db->update('shop_lists', $data)) {
            log_message('error', print_r($this->db->error(), true));
            show_error(lang('database_error'));
        }
    }
    
    public function deleteClient($id)
    {
        $client_id = $this->db->select('base_url')->where('id', $id)->get('shop_lists')->row('base_url');
        $this->db->trans_begin();
        $this->db->where('id', $id);
        if (!$this->db->delete('shop_lists')) {
            log_message('error', print_r($this->db->error(), true));
            show_error(lang('database_error'));
        }
        
        if (!$this->db->where('shop_id', $id)->delete('value_store')) {
            log_message('error', print_r($this->db->error(), true));
            show_error(lang('database_error'));
        }

        if ($this->db->trans_status() === FALSE) {
            $this->db->trans_rollback();
            return false;
        } else {
            $this->db->trans_commit();
            return $client_id;
        }
    }

    public function getClients($id = null)
    {
        if ($id != null && is_numeric($id)) {
            $this->db->where('id', $id);
        }elseif ($id != null && is_string($id)) {
            $this->db->where('base_url', $id);
        }
        $query = $this->db->get('shop_lists');
        return $query->result_array();
    }

    public function setClients($post)
    {
        if ($post['id'] > 0) {
            $this->db->where('id', $post['id']);
            if (!$this->db->update('shop_lists', array('base_url' => $post['base_url']))) {
                log_message('error', print_r($this->db->error(), true));
                show_error(lang('database_error'));
            }
        } else {
            $this->db->trans_begin();
            if (!$this->db->insert('shop_lists', array('base_url' => $post['base_url']))) {
                log_message('error', print_r($this->db->error(), true));
                show_error(lang('database_error'));
            }
            $client_id = $this->db->insert_id();
            
            if (!$this->db->insert('languages', array('shop_id'=>$client_id, 'abbr' => 'en', 'name'=>'english', 'currency'=>'$', 'currencyKey'=>'USD', 'flag'=>'en.jpg'))) {
                log_message('error', print_r($this->db->error(), true));
                show_error(lang('database_error'));
            }
            
            $insert_arr = array(
                    array('shop_id' => $client_id, 'thekey' => 'sitelogo', 'value' => 'logo2.png'),
                    array('shop_id' => $client_id, 'thekey' => 'navitext', 'value' => ''),
                    array('shop_id' => $client_id, 'thekey' => 'footercopyright', 'value' => 'All Rights Reserved By ABSoftBD'),
                    array('shop_id' => $client_id, 'thekey' => 'contactspage', 'value' => '<p>Hello dear client, feel free to contact at</p>\r\n\r\n<p>Mobile: +8801719455709</p>\r\n\r\n<p>Phone:</p>\r\n\r\n<p>Email: info@absoft-bd.com</p>\r\n\r\n<p>Dhaka.</p>\r\n'),
                    array('shop_id' => $client_id, 'thekey' => 'footerContactAddr', 'value' => 'Dhaka'),
                    array('shop_id' => $client_id, 'thekey' => 'footerContactEmail', 'value' => 'info@absoft-bd.com'),
                    array('shop_id' => $client_id, 'thekey' => 'footerContactPhone', 'value' => '+8801719455709'),
                    array('shop_id' => $client_id, 'thekey' => 'googleMaps', 'value' => '42.671840, 83.279163'),
                    array('shop_id' => $client_id, 'thekey' => 'footerAboutUs', 'value' => 'It is all up to you. Find the right software for your work, and get free. Go forward, access us.'),
                    array('shop_id' => $client_id, 'thekey' => 'footerSocialFacebook', 'value' => ''),
                    array('shop_id' => $client_id, 'thekey' => 'footerSocialTwitter', 'value' => ''),
                    array('shop_id' => $client_id, 'thekey' => 'footerSocialGooglePlus', 'value' => ''),
                    array('shop_id' => $client_id, 'thekey' => 'footerSocialPinterest', 'value' => ''),
                    array('shop_id' => $client_id, 'thekey' => 'footerSocialYoutube', 'value' => ''),
                    array('shop_id' => $client_id, 'thekey' => 'contactsEmailTo', 'value' => 'info@absoft-bd.com'),
                    array('shop_id' => $client_id, 'thekey' => 'shippingOrder', 'value' => ''),
                    array('shop_id' => $client_id, 'thekey' => 'addJs', 'value' => ''),
                    array('shop_id' => $client_id, 'thekey' => 'g_recaptcha_site_key', 'value' => ''),
                    array('shop_id' => $client_id, 'thekey' => 'g_recaptcha_secret_key', 'value' => ''),
                    array('shop_id' => $client_id, 'thekey' => 'publicQuantity', 'value' => '0'),
                    array('shop_id' => $client_id, 'thekey' => 'paypal_email', 'value' => ''),
                    array('shop_id' => $client_id, 'thekey' => 'paypal_sandbox', 'value' => '0'),
                    array('shop_id' => $client_id, 'thekey' => 'publicDateAdded', 'value' => '0'),
                    array('shop_id' => $client_id, 'thekey' => 'googleApi', 'value' => ''),
                    array('shop_id' => $client_id, 'thekey' => 'template', 'value' => 'food'),
                    array('shop_id' => $client_id, 'thekey' => 'cashondelivery_visibility', 'value' => '1'),
                    array('shop_id' => $client_id, 'thekey' => 'showBrands', 'value' => '0'),
                    array('shop_id' => $client_id, 'thekey' => 'showInSlider', 'value' => '1'),
                    array('shop_id' => $client_id, 'thekey' => 'codeDiscounts', 'value' => '1'),
                    array('shop_id' => $client_id, 'thekey' => 'virtualProducts', 'value' => '0'),
                    array('shop_id' => $client_id, 'thekey' => 'multiVendor', 'value' => '0'),
                    array('shop_id' => $client_id, 'thekey' => 'outOfStock', 'value' => '1'),
                    array('shop_id' => $client_id, 'thekey' => 'smsApi', 'value' => ''),
                    array('shop_id' => $client_id, 'thekey' => 'smsUserName', 'value' => ''),
                    array('shop_id' => $client_id, 'thekey' => 'smsPass', 'value' => ''),
                    array('shop_id' => $client_id, 'thekey' => 'smsURL', 'value' => ''),
                    array('shop_id' => $client_id, 'thekey' => 'smsSenderId', 'value' => ''),
                    array('shop_id' => $client_id, 'thekey' => 'companyName', 'value' => 'ABSoftBD'),
                    array('shop_id' => $client_id, 'thekey' => 'siteAPK', 'value' => ''),
                    array('shop_id' => $client_id, 'thekey' => 'siteoverview', 'value' => ''),
                    array('shop_id' => $client_id, 'thekey' => 'siteico', 'value' => ''),
                    array('shop_id' => $client_id, 'thekey' => 'footerContactEmailPass', 'value' => ''),
                    array('shop_id' => $client_id, 'thekey' => 'officeTimeStart', 'value' => ''),
                    array('shop_id' => $client_id, 'thekey' => 'officeTimeEnd', 'value' => ''),
                    array('shop_id' => $client_id, 'thekey' => 'wish_list', 'value' => '0'),
                    array('shop_id' => $client_id, 'thekey' => 'hasStock', 'value' => '0'),
                    array('shop_id' => $client_id, 'thekey' => 'page_width', 'value' => '50'));
            
            $this->db->insert_batch('value_store', $insert_arr);

            if ($this->db->trans_status() === FALSE) {
                $this->db->trans_rollback();
                return false;
            } else {
                $this->db->trans_commit();
                return $client_id;
            }
        }
    }

}
