<?php

class Admin_users_model extends CI_Model
{

    public function __construct()
    {
        parent::__construct();
    }

    public function deleteAdminUser($id)
    {
        $this->db->where('shop_id', SHOP_ID);
        $this->db->where('id', $id);
        if (!$this->db->delete('users')) {
            log_message('error', print_r($this->db->error(), true));
            show_error(lang('database_error'));
        }
    }
    
    public function reactiveUser($id)
    {
        $this->db->where('shop_id', SHOP_ID);
        $this->db->where('id', $id);
        if (!$this->db->update('users', array('login_attempts_failed'=>0, 'failed_time'=>null))) {
            log_message('error', print_r($this->db->error(), true));
            show_error(lang('database_error'));
        }
    }

    public function getAdminUsers($user = null)
    {
        $this->db->where('shop_id', SHOP_ID);
        if ($user != null && is_numeric($user)) {
            $this->db->where('id', $user);
        } else if ($user != null && is_string($user)) {
            $this->db->where('username', $user);
        }
        $query = $this->db->get('users');
        if ($user != null) {
            return $query->row_array();
        } else {
            return $query;
        }
    }

    public function setAdminUser($post)
    {
        if ($post['edit'] > 0) {
            if (trim($post['password']) == '') {
                unset($post['password']);
            } else {
                $post['password'] = md5($post['password']);
            }
            $this->db->where('shop_id', SHOP_ID);
            $this->db->where('id', $post['edit']);
            unset($post['id'], $post['edit']);
            $post['access'] = implode(',', $post['access']);
            if (!$this->db->update('users', $post)) {
                log_message('error', print_r($this->db->error(), true));
                show_error(lang('database_error'));
            }
        } else {
            unset($post['edit']);
            $post['password'] = md5($post['password']);
            $post['access'] = implode(',', $post['access']);
            $post['shop_id'] = SHOP_ID;
            if (!$this->db->insert('users', $post)) {
                log_message('error', print_r($this->db->error(), true));
                show_error(lang('database_error'));
            }
        }
    }

}
