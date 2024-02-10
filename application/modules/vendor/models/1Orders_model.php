<?php

class Orders_model extends CI_Model
{

    public function __construct()
    {
        parent::__construct();
    }

    public function ordersCount($vendor_id, $fd='', $td='')
    {
        if($fd != '' && $td != ''){
            $this->db->where('date >=', $fd);
            $this->db->where('date <=', $td);
        }
        $this->db->where('vendor_id', $vendor_id);
        return $this->db->count_all_results('vendors_orders');
    }

    public function orders($limit, $page, $vendor_id, $fd='', $td='')
    {
        if($fd != '' && $td != ''){
            $this->db->where('date >=', $fd);
            $this->db->where('date <=', $td);
        }
        $this->db->where('vendor_id', $vendor_id);
        $this->db->order_by('id', 'DESC');
        $this->db->select('vendors_orders.*, vendors_orders_clients.first_name,'
                . ' vendors_orders_clients.last_name, vendors_orders_clients.email, vendors_orders_clients.phone, '
                . 'vendors_orders_clients.address, vendors_orders_clients.city, vendors_orders_clients.post_code,'
                . ' vendors_orders_clients.notes, discount_codes.type as discount_type, discount_codes.amount as discount_amount');
        $this->db->join('vendors_orders_clients', 'vendors_orders_clients.for_id = vendors_orders.id', 'inner');
        $this->db->join('discount_codes', 'discount_codes.code = vendors_orders.discount_code', 'left');
        $result = $this->db->get('vendors_orders', $limit, $page);
        return $result->result_array();
    }

    public function changeOrderStatus($id, $to_status)
    {
        $this->db->where('id', $id);
        $this->db->select('processed');
        $result1 = $this->db->get('vendors_orders');
        $res = $result1->row_array();

        if ($res['processed'] != $to_status) {
            $this->db->where('id', $id);
            $result = $this->db->update('vendors_orders', array('processed' => $to_status, 'viewed' => '1'));
            return $result;
        }
    }

    public function wishs($vid)
    {
        // if($id > 0){
        //     $this->db->where('wish_list.id', $id);
        // }
        $this->db->select('wish_list.*');
        $this->db->select('customer.name, customer.phone');
        $this->db->select('products.url, products.quantity as stock, products.image, products.vendor_id');
        $this->db->select('products_translations.title, products_translations.price');
        $this->db->join('customer', 'customer.id = wish_list.customer_id', 'left');
        $this->db->join('products', 'products.id = wish_list.product_id', 'left');
        $this->db->join('products_translations', 'products_translations.for_id = wish_list.product_id', 'left');
        $this->db->select('vendors.name as vendor_name');
        $this->db->join('vendors', 'vendors.id = products.vendor_id');
        $this->db->where('products_translations.abbr', MY_DEFAULT_LANGUAGE_ABBR);
        $this->db->where('vendors.id', $vid);
        return $this->db->get('wish_list')->result();
    }

}
