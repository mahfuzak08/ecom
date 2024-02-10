<?php

class Wish_model extends CI_Model
{
    private $multiVendor;

    public function __construct()
    {
        parent::__construct();
        $this->load->Model('Home_admin_model');
        $this->multiVendor = $this->Home_admin_model->getValueStore('multiVendor');
    }

    public function wishs($id)
    {
        if($id > 0){
            $this->db->where('wish_list.id', $id);
        }
        $this->db->select('wish_list.*');
        $this->db->select('customer.name, customer.phone');
        $this->db->select('products.url, products.quantity as stock, products.image, products.vendor_id');
        $this->db->select('products_translations.title, products_translations.price');
        $this->db->join('customer', 'customer.id = wish_list.customer_id', 'left');
        $this->db->join('products', 'products.id = wish_list.product_id', 'left');
        $this->db->join('products_translations', 'products_translations.for_id = wish_list.product_id', 'left');
        if($this->multiVendor == 1){
            $this->db->select('vendors.name as vendor_name');
            $this->db->join('vendors', 'vendors.id = products.vendor_id');
        }
        $this->db->where('products_translations.abbr', MY_DEFAULT_LANGUAGE_ABBR);
        $this->db->where('wish_list.shop_id', SHOP_ID);
        return $this->db->get('wish_list')->result();
    }

    public function setOrder($data)
    {
        $q = $this->db->query('SELECT MAX(order_id) as order_id FROM orders WHERE shop_id = '.SHOP_ID);
        $rr = $q->row_array();
        if ($rr['order_id'] == 0) {
            $rr['order_id'] = 1233;
        }
        $data['order_id'] = $rr['order_id'] + 1;

        $i = 0;
        $products_to_order = [];
        if(!empty($data['wishs'][0]->product_id)) {
            $pd = $this->getOneProductForSerialize($data['wishs'][0]->product_id);
            $products_to_order[] = [
                'product_info' => array(
                    'shop_id' => SHOP_ID,
                    'id'=>$pd['id'],
                    'name'=>$pd['title'],
                    'description'=>$pd['description'],
                    'quantity'=>$data['wishs'][0]->quantity,
                    'image'=>$pd['image'],
                    'url'=>$pd['url'],
                    'shop_categorie'=>$pd['shop_categorie'],
                    'brand_id'=>$pd['brand_id'],
                    'vendor_id'=>$pd['vendor_id'],
                    'price'=>$pd['price'],
                    'wish_price'=>$data['wishs'][0]->amount,
                    'total'=>$data['wishs'][0]->amount * $data['wishs'][0]->quantity
                ),
                'product_quantity' => $data['wishs'][0]->quantity
            ];
        
        }
        $data['asof'] = $data['customer_info']->balance;
        $data['asof'] += $data['wishs'][0]->amount;
        $data['payment_type'] = 'cashOnDelivery';
        $data['products'] = serialize($products_to_order);
        $this->db->trans_begin();
        if (!$this->db->insert('orders', array(
                    'shop_id'       => SHOP_ID,
                    'order_id'      => $data['order_id'],
                    'customer_id'   => $data['customer_info']->id,
                    'products'      => $data['products'],
                    'date'          => date("Y-m-d"),
                    'referrer'      => 'Wish2Order',
                    'clean_referrer'=> 'WISH',
                    'payment_type'  => $data['payment_type'],
                    'total'         => $data['wishs'][0]->amount,
                    'asof_date_due' => $data['asof'],
                    'processed'     => 1,
                    'viewed'        => 1,
                    'user_id'       => $data['user_id']
                ))) {
            log_message('error', print_r($this->db->error(), true));
        }
        $lastId = $this->db->insert_id();
        if (!$this->db->insert('orders_clients', array(
                    'shop_id'       => SHOP_ID,
                    'for_id'        => $lastId,
                    'customer_id'   => $data['customer_info']->id,
                    'first_name'    => $data['customer_info']->name,
                    'email'         => @$data['customer_info']->email,
                    'phone'         => $data['customer_info']->phone,
                    'address'       => @$data['customer_info']->address,
                    'city'          => @$data['customer_info']->city,
                    'post_code'     => @$data['other_cost'],
                    'notes'         => @$data['discount']
                ))) {
            log_message('error', print_r($this->db->error(), true));
        }
        // insert payment option
        if (!$this->db->insert('cus_sup_trans', array(
            'shop_id'   => SHOP_ID,
            'type'      => 'customer',
            'trans_for' => $data['customer_info']->id,
            'trans_no'  => $lastId,
            'amt'       => $data['wishs'][0]->amount,
            'note'      => NULL,
            'trans_date'=> date("Y-m-d"),
            'user_id'   => $data['user_id']
            ))) {
            log_message('error', print_r($this->db->error(), true));
        }
        
        if(! $this->db->query('UPDATE customer SET balance = balance + ' . $data['wishs'][0]->amount . ' WHERE id = ' . $data['customer_info']->id)) {
            log_message('error', print_r($this->db->error(), true));
            show_error(lang('database_error'));
        }
        
        // update inventory
        $operator = "-";
        $type = 'out';
        if (!$this->db->query('UPDATE products SET quantity=quantity' . $operator . $data['wishs'][0]->quantity . ' WHERE id = ' . $data['wishs'][0]->product_id)) {
            log_message('error', print_r($this->db->error(), true));
            show_error(lang('database_error'));
        }
        $this->setProductTrans($type, $data['wishs'][0]->product_id, $data['wishs'][0]->quantity, $data['user_id'], '', 1, $lastId, '');
    
        if ($this->db->trans_status() === FALSE) {
            $this->db->trans_rollback();
            return false;
        } else {
            $this->db->trans_commit();
            return $lastId;
        }
    }

    public function change_wish_list_status($id, $status){
        if(! $this->db->query("UPDATE wish_list SET status=$status WHERE id=$id")){
            log_message('error', print_r($this->db->error(), true));
            show_error(lang('database_error'));
            return false;
        }
        return true;
    }

    private function getOneProductForSerialize($id)
    {
        $this->db->select('products.*, products_translations.price, products_translations.title, products_translations.description');
        // $this->db->select('vendors.name as vendor_name, vendors.id as vendor_id');
        $this->db->where('products.id', $id);
        // $this->db->join('vendors', 'vendors.id = products.vendor_id', 'left');
        $this->db->join('products_translations', 'products_translations.for_id = products.id', 'inner');
        $this->db->where('products_translations.abbr', MY_DEFAULT_LANGUAGE_ABBR);
        $this->db->where('products.shop_id', SHOP_ID);
        $query = $this->db->get('products');
        if ($query->num_rows() > 0) {
            return $query->row_array();
        } else {
            return false;
        }
    }

    private function setProductTrans($type, $item_id, $qty, $uid, $date = '', $loc = 1, $ref = '', $dec = NULL)
    {
        if (!$this->db->insert('product_trans', array(
                    'shop_id' => SHOP_ID,
                    'ref_id' => $ref,
                    'ref_type' => $type,
                    'location' => $loc,
                    'item_id' => $item_id,
                    'quantity' => $qty,
                    'description' => $dec,
                    'trans_date' => $date == '' ? date('Y-m-d') : $date,
                    'user_id' => $uid
                ))) {
            return false;
            log_message('error', print_r($this->db->error(), true));
        }
        return true;
    }
    
}
