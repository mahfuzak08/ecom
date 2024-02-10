<?php

class Shop_model extends CI_Model
{

    public function __construct()
    {
        parent::__construct();
    }

    public function getShopId($server)
    {
        $this->db->select("id");
        $this->db->where("is_active", "1");
        $this->db->where("base_url", $server);
        return $this->db->get("shop_lists")->row("id");
    }
}