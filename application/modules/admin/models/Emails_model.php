<?php

class Emails_model extends CI_Model
{

    public function __construct()
    {
        parent::__construct();
    }

    public function emailsCount()
    {
        $this->db->where('shop_id', SHOP_ID);
        return $this->db->count_all_results('subscribed');
    }

    public function getSuscribedEmails($limit, $page)
    {
        $this->db->where('shop_id', SHOP_ID);
        $this->db->order_by('id', 'desc');
        $query = $this->db->select('*')->get('subscribed', $limit, $page);
        return $query;
    }

    public function deleteEmail($id)
    {
        $this->db->where('shop_id', SHOP_ID);
        if (!$this->db->where('id', $id)->delete('subscribed')) {
            log_message('error', print_r($this->db->error(), true));
            show_error(lang('database_error'));
        }
    }

}
