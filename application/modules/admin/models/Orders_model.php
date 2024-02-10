<?php

class Orders_model extends CI_Model
{

    public function __construct()
    {
        parent::__construct();
    }

    public function ordersCount($onlyNew = false, $fd='', $td='')
    {
        if ($onlyNew == true) {
            $this->db->where('viewed', 0);
        }
        if($fd != '' && $td != ''){
            $this->db->where('date >=', $fd);
            $this->db->where('date <=', $td);
        }
        $this->db->where('shop_id', SHOP_ID);
        return $this->db->count_all_results('orders');
    }

    public function orders($limit, $page, $order_by, $fd='', $td='')
    {
        if ($order_by != null) {
            $this->db->order_by($order_by, 'DESC');
        } else {
            $this->db->order_by('id', 'DESC');
        }
        
        if($fd != '' && $td != ''){
            $this->db->where('date >=', $fd);
            $this->db->where('date <=', $td);
        }
        $this->db->select('orders.*, orders_clients.first_name,'
                . ' orders_clients.email, orders_clients.phone, '
                . 'orders_clients.address, orders_clients.city, orders_clients.post_code,'
                . ' orders_clients.notes, discount_codes.type as discount_type, discount_codes.amount as discount_amount');
        $this->db->join('orders_clients', 'orders_clients.for_id = orders.id', 'inner');
        $this->db->join('discount_codes', 'discount_codes.code = orders.discount_code', 'left');
        $this->db->where('orders.shop_id', SHOP_ID);
        $result = $this->db->get('orders', $limit, $page);
        return $result->result_array();
    }

    public function changeOrderStatus($id, $to_status)
    {
        $this->db->where('id', $id);
        $this->db->where('shop_id', SHOP_ID);
        $this->db->select('processed');
        $result1 = $this->db->get('orders');
        $res = $result1->row_array();

        $result = true;
        if ($res['processed'] != $to_status) {
            $this->db->where('id', $id);
            $this->db->where('shop_id', SHOP_ID);
            $result = $this->db->update('orders', array('processed' => $to_status, 'viewed' => '1'));
            if ($result == true) {
                return $this->manageQuantitiesAndProcurement($id, $to_status, $res['processed']);
            }
        }
        return $result;
    }

    private function manageQuantitiesAndProcurement($id, $to_status, $current)
    {
        if (($to_status == 0 || $to_status == 2) && $current == 1) {
            $operator = '+';
            $type = 'in';
            $operator_pro = '-';
        }
        elseif ($to_status == 1) {
            $operator = '-';
            $type = 'out';
            $operator_pro = '+';
        }
        elseif( $to_status == 3)
            return true;
        // $this->db->select('products');
        $this->db->where('id', $id);
        $this->db->where('shop_id', SHOP_ID);
        $result = $this->db->get('orders');
        $arr = $result->row_array();
        $products = unserialize($arr['products']);
        $bq = "";
        foreach ($products as $product) {
            $qs = ""; $ps = "";
            if (isset($operator)) {
                if(isset($product['product_info']['size']) && $product['product_info']['size'] != 0 && $product['product_info']['size'] != 'N'){
                    $prow = $this->db->where('id', $product['product_info']['id'])->get('products')->row()->size;
                    $ps = "Size: ". $product['product_info']['size'];
                    $psize = array();
                    if($prow != 'N' && $prow != ''){
                        $sizes = explode(";", $prow);
                        for($i=0; $i<count($sizes); $i++){
                            $per_size = explode("x", $sizes[$i]);
                            if($per_size[0] == $product['product_info']['size']){
                                if($operator == '-' && (int)$per_size[1] >= $product['product_quantity']){
                                    $per_size[1] = (int)$per_size[1] - $product['product_quantity'];
                                }
                                elseif($operator == '+' && (int)$per_size[1] <= $product['product_quantity']){
                                    $per_size[1] = (int)$per_size[1] + $product['product_quantity'];
                                }
                            }
                            $psize[] = $per_size[0]."x".$per_size[1];
                        }
                        $prow = implode(";", $psize);
                        $qs = ", size = '".$prow."'";
                    }
                }
                $bq = 'UPDATE products SET quantity=quantity' . $operator . $product['product_quantity'] . ', procurement=procurement' . $operator_pro . $product['product_quantity'] . $qs . ' WHERE id = ' . $product['product_info']['id'].'; ';
                if (!$this->db->query($bq)) {
                    log_message('error', print_r($this->db->error(), true));
                    show_error(lang('database_error'));
                    return false;
                }
                $this->setProductTrans($type, $product['product_info']['id'], $product['product_quantity'], $_SESSION['logged_user_id'], '', 1, $id, $ps);
            }
        }
        if (!$this->db->insert('cus_sup_trans', array(
            'shop_id' => SHOP_ID,
            'type'      => 'customer',
            'trans_for' => $arr['customer_id'],
            'trans_no'  => $id,
            'amt'       => $arr['total'],
            'note'      => 'eCommerce Sale',
            'trans_date'=> date("Y-m-d"),
            'user_id'   => $_SESSION['logged_user_id']
            ))) {
            log_message('error', print_r($this->db->error(), true));
        }
        if(! $this->db->insert('account_trans', array(
            'shop_id'       => SHOP_ID,
            'type'          => 'sale',
            'trans_no'      => $id,
            'bank_act'      => $arr['payment_type'] == "cashOnDelivery" ? 1 : $arr['payment_type'],
            'trans_date'    => date("Y-m-d"),
            'person_id'     => $_SESSION['logged_user_id'],
            'amount'        => $arr['total']
            ))) {
            log_message('error', print_r($this->db->error(), true));
        }

        return true;
    }

    public function setBankAccountSettings($post)
    {
        $query = $this->db->query('SELECT id FROM bank_accounts WHERE shop_id =' . SHOP_ID);
        if ($query->num_rows() == 0) {
            $id = 1;
        } else {
            $result = $query->row_array();
            $id = $result['id'];
        }
        $post['id'] = $id;
        if (!$this->db->replace('bank_accounts', $post)) {
            log_message('error', print_r($this->db->error(), true));
            show_error(lang('database_error'));
        }
    }

    public function getBankAccountSettings()
    {
        $result = $this->db->query("SELECT * FROM bank_accounts WHERE shop_id = ". SHOP_ID ." LIMIT 1");
        return $result->row_array();
    }
    
    public function getOrderInfo($id)
    {
        $this->db->where('orders.id', $id);
        $this->db->where('orders.shop_id', SHOP_ID);
        $this->db->select('orders.*, orders_clients.phone');
        $this->db->join('orders_clients', 'orders_clients.for_id = orders.id', 'inner');
        $result = $this->db->get('orders');
        return $result->row_array();
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
