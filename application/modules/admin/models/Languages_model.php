<?php

class Languages_model extends CI_Model
{

    public function __construct()
    {
        parent::__construct();
    }

    public function deleteLanguage($id)
    {
        $this->db->select('abbr');
        $this->db->where('id', $id);
        $this->db->where('shop_id', SHOP_ID);
        $res = $this->db->get('languages');
        $row = $res->row_array();
        $this->db->trans_start();
        $this->db->query('DELETE FROM languages WHERE shop_id = '. SHOP_ID .' AND id = ' . $this->db->escape($id));
        // $this->db->query('DELETE FROM products_translations WHERE shop_id = '. SHOP_ID .' AND abbr = "' . $row['abbr'] . '"');
        // $this->db->query('DELETE FROM shop_categories_translations WHERE shop_id = '. SHOP_ID .' AND abbr = "' . $row['abbr'] . '"');
        // $this->db->query('DELETE FROM textual_pages_tanslations WHERE shop_id = '. SHOP_ID .' AND abbr = "' . $row['abbr'] . '"');
        // $this->db->query('DELETE FROM blog_translations WHERE shop_id = '. SHOP_ID .' AND abbr = "' . $row['abbr'] . '"');
        // $this->db->query('DELETE FROM cookie_law_translations WHERE shop_id = '. SHOP_ID .' AND abbr = "' . $row['abbr'] . '"');
        $this->db->trans_complete();
        if ($this->db->trans_status() === FALSE) {
            return false;
        }
        return true;
    }

    public function countLangs($name = null, $abbr = null)
    {
        if ($abbr != null) {
            $this->db->where('abbr', $abbr);
        }
        elseif ($name != null) {
            $this->db->where('name', $name);
        }
        $this->db->where('shop_id', SHOP_ID);
        return $this->db->count_all_results('languages');
    }

    public function getLanguages()
    {
        $this->db->where('shop_id', SHOP_ID);
        return $this->db->get('languages')->result();
    }

    public function setLanguage($post)
    {
        $post['name'] = strtolower($post['name']);
        $post['abbr'] = strtolower($post['abbr']);
        $post['shop_id'] = SHOP_ID;
        if (!$this->db->insert('languages', $post)) {
            log_message('error', print_r($this->db->error(), true));
            show_error(lang('database_error'));
        }
    }

}
