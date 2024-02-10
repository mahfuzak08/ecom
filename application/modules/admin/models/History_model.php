<?php

class History_model extends CI_Model
{

    public function __construct()
    {
        parent::__construct();
    }

    public function historyCount()
    {
        $this->db->where('shop_id', SHOP_ID);
        return $this->db->count_all_results('history');
    }

    public function getHistory($limit, $page)
    {
        $this->db->where('shop_id', SHOP_ID);
        $this->db->order_by('id', 'desc');
        $query = $this->db->select('*')->get('history', $limit, $page);
        return $query;
    }

    public function setHistory($activity, $user)
    {
        if (!$this->db->insert('history', array(
                    'shop_id' => SHOP_ID,
                    'activity' => $activity,
                    'username' => $user,
                    'time' => time())
                )) {
            log_message('error', print_r($this->db->error(), true));
            show_error(lang('database_error'));
        }
    }

}
