<?php

class Sales_model extends CI_Model
{

    public function __construct()
    {
        parent::__construct();
    }

    public function get_payment_type()
    {
        // $this->db->select('accounts.*, SUM(account_trans.amount) as balance');
        // $this->db->join('account_trans', 'account_trans.bank_act = accounts.id', 'left');
        // $this->db->group_by('account_trans.bank_act');
        $this->db->where('accounts.shop_id', SHOP_ID);
		return $this->db->get('accounts')->result();
    }

    public function get_search_suggestions($mode, $search, $filters = array('is_delete' => 0), $limit = 25)
    {
        $suggestions = array();
        if($mode == 'sale' || $mode == 'sale_return'){
            // $this->db->select('products.id, products_translations.title');
            // $this->db->from('products');
            // $this->db->join('products_translations', 'products.id = products_translations.for_id', 'left');
		    // $this->db->where('products_translations.abbr', MY_DEFAULT_LANGUAGE_ABBR);
            // $this->db->where('products.visibility', $filters['is_delete']);
            // $this->db->where('shop_id', SHOP_ID);
            // $this->db->like('products_translations.title', $search);
            // $this->db->or_like('products.barcode', $search);
            // $this->db->order_by('products_translations.title', 'asc');
			
			$str = "SELECT p.id, pt.title FROM products as p LEFT JOIN products_translations as pt ON p.id = pt.for_id WHERE pt.abbr = '". MY_DEFAULT_LANGUAGE_ABBR ."' AND p.shop_id = '".SHOP_ID."' AND p.visibility = '".$filters['is_delete']."' AND (pt.title LIKE '%".$search."%' OR p.barcode = '".$search."') ORDER BY pt.title";
			
            foreach($this->db->query($str)->result() as $row)
            {
                $suggestions[] = array('value' => $row->id, 'label' => $row->title);
            }
        }
        elseif($mode == 'sale_print_invoice' || $mode == 'sale_update'){
            $this->db->select('id, order_id');
            $this->db->from('orders');
            $this->db->where('shop_id', SHOP_ID);
            $this->db->like('order_id', $search);
            $this->db->order_by('order_id', 'asc');
            foreach($this->db->get()->result() as $row)
            {
                $suggestions[] = array('value' => $row->id, 'label' => $row->order_id);
            }
        }
        
        //only return $limit suggestions
		if(count($suggestions) > $limit)
		{
			$suggestions = array_slice($suggestions, 0, $limit);
		}

		return $suggestions;
	}
	
	public function get_customer_search_suggestions($search, $limit = 25)
    {
        $suggestions = array();
		$this->db->select('id, name');
        $this->db->from('customer');
        $this->db->where('shop_id', SHOP_ID);
		// $this->db->where('visibility', $filters['is_delete']);
		if(is_numeric($search)) $this->db->like('phone', $search);
		else $this->db->like('name', $search);
		$this->db->order_by('name', 'asc');
		foreach($this->db->get()->result() as $row)
		{
			$suggestions[] = array('value' => $row->id, 'label' => $row->name);
        }
        
        //only return $limit suggestions
		if(count($suggestions) > $limit)
		{
			$suggestions = array_slice($suggestions, 0, $limit);
		}

		return $suggestions;
	}
	
	public function get_info_by_id_or_number($item_id)
	{
	    $where_or_where = "(p.id = '".$item_id."' OR p.barcode = '".$item_id."')";
		$this->db->select('p.*, pt.title, pt.description, pt.price, pt.buy_price');
		$this->db->from('products as p');
		$this->db->join('products_translations as pt', 'p.id = pt.for_id AND pt.abbr = "en"', 'left');
		$this->db->where('p.visibility', 1);
        $this->db->where('p.shop_id', SHOP_ID);
		$this->db->where($where_or_where);
// 		$this->db->or_where('p.barcode', $item_id);
        $this->db->limit(1);

		$query = $this->db->get();
        file_put_contents("test.txt", $this->db->last_query());
		if($query->num_rows() == 1)
		{
			return $query->row();
		}

		return '';
	}

	public function get_customer_info($id)
	{
		$this->db->select('id, name, phone, email, address, balance');
		$this->db->from('customer');
        $this->db->where('id', $id);
        $this->db->where('shop_id', SHOP_ID);
		$this->db->limit(1);

		$query = $this->db->get();

		if($query->num_rows() == 1)
		{
			return $query->row();
		}

		return '';
    }
    
    public function add_new_customer($name, $phone)
    {
        if($this->countCustomerByPhone($phone) == 0){
            $this->db->insert('customer', array(
                    'shop_id'       => SHOP_ID,
                    'name'          => $name,
                    'phone'         => $phone,
                    'phone_verify'  => 'Yes'
            ));
            return $this->db->insert_id();
        }
        else
            return -1;
    }

	public function setOrder($data)
    {
        $q = $this->db->query('SELECT MAX(order_id) as order_id FROM orders WHERE shop_id = '. SHOP_ID);
        $rr = $q->row_array();
        if ($rr['order_id'] == 0) {
            $rr['order_id'] = 1233;
        }
        $data['order_id'] = $rr['order_id'] + 1;

        $i = 0;
        $products_to_order = [];
        if(!empty($data['items'])) {
            foreach($data['items'] as $pr_val) {
                $products_to_order[] = [
                    'product_info' => $pr_val,
                    'product_quantity' => $pr_val['quantity']
                ];
            }
        }
        $data['asof'] = $data['customer_info']->balance;
        $return_amt = $change_amt = 0;
        foreach($data['get_payments'] as $payment){
            if($payment['group'] == 'Due'){
                $data['asof'] += $payment['payment_amount'];
            }
            if($payment['group'] == 'GiftCard'){
                $data['asof'] += $payment['payment_amount'];
            }
            if($payment['group'] == 'Collection'){
                $data['asof'] -= $payment['payment_amount'];
            }
            if($payment['group'] == 'Change Amount'){
                $change_amt = $payment['payment_amount'];
            }
            if($payment['group'] == 'Return'){
                $_SESSION['return_amount_'.$data['customer_info']->id] = 0;
                $return_amt = $payment['payment_amount'];
            }
        }
        
        $data['products'] = serialize($products_to_order);
        $this->db->trans_begin();
        if (!$this->db->insert('orders', array(
                    'shop_id'       => SHOP_ID,
                    'order_id'      => $data['order_id'],
                    'order_type'    => $data['mode'],
                    'customer_id'   => $data['customer_info']->id,
                    'products'      => $data['products'],
                    'date'          => $data['inv_date'],
                    'referrer'      => 'POS',
                    'clean_referrer'=> 'POS',
                    'payment_type'  => serialize($data['get_payments']),
                    'paypal_status' => @$data['paypal_status'],
                    'labour_cost'   => @$data['labour_cost'],
                    'carrying_cost' => @$data['carrying_cost'],
                    'other_cost'    => @$data['other_cost'],
                    'discount_code' => @$data['discount'],
                    'total'         => $data['total'],
                    'asof_date_due' => @$data['asof'],
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
                    'address'       => $data['customer_info']->address,
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
            'amt'       => $data['total'],
            'note'      => NULL,
            'trans_date'=> $data['inv_date'],
            'user_id'   => $data['user_id']
            ))) {
            log_message('error', print_r($this->db->error(), true));
        }
        
        foreach($data['get_payments'] as $payment){
            if($payment['group'] == 'Change Amount') continue;
            if($payment['group'] == 'Return') continue;
            $is_valid_acc = true;
            
            if($payment['group'] == 'Cash'){
                if($change_amt < 0) $payment['payment_amount'] += $change_amt;
                if($return_amt > 0) $payment['payment_amount'] += $return_amt;
            }
            
            
            // if($payment['group'] == 'Cash' && $data['asof'] < 0 && $temp_flag === false){
            //     $bal = $payment['payment_amount'] >= $data['asof'] ? $data['asof'] : $payment['payment_amount'];
            //     if(! $this->db->query('UPDATE customer SET balance = balance + ' . $bal . ' WHERE id = ' . $data['customer_info']->id)) {
            //         log_message('error', print_r($this->db->error(), true));
            //         show_error(lang('database_error'));
            //     }
            // }
            
            if($payment['group'] == 'Due'){
                $is_valid_acc = false;
                if(! $this->db->query('UPDATE customer SET balance = balance + ' . $payment['payment_amount'] . ' WHERE id = ' . $data['customer_info']->id)) {
                    log_message('error', print_r($this->db->error(), true));
                    show_error(lang('database_error'));
                }
            }
            
            if($payment['group'] == 'GiftCard'){
                $is_valid_acc = false;
                if(! $this->db->query('UPDATE customer SET balance = balance + ' . $payment['payment_amount'] . ' WHERE id = ' . $data['customer_info']->id)) {
                    log_message('error', print_r($this->db->error(), true));
                    show_error(lang('database_error'));
                }
            }
            
            // if($payment['group'] == 'Advance'){
            //     $is_valid_acc = false;
            //     if(! $this->db->query('UPDATE customer SET balance = balance - ' . $payment['payment_amount'] . ' WHERE id = ' . $data['customer_info']->id)) {
            //         log_message('error', print_r($this->db->error(), true));
            //         show_error(lang('database_error'));
            //     }
            // }
            
            if($payment['group'] == 'Collection'){
                $is_valid_acc = false;
                if (!$this->db->insert('cus_sup_trans', array(
                    'shop_id'   => SHOP_ID,
                    'type'      => 'customer',
                    'trans_for' => $data['customer_info']->id,
                    'trans_no'  => $lastId,
                    'amt'       => $payment['payment_amount'],
                    'note'      => 'Due Collection',
                    'trans_date'=> $data['inv_date'],
                    'user_id'   => $data['user_id']
                    ))) {
                    log_message('error', print_r($this->db->error(), true));
                }
                if(! $this->db->query('UPDATE customer SET balance = balance - ' . $payment['payment_amount'] . ' WHERE id = ' . $data['customer_info']->id)) {
                    log_message('error', print_r($this->db->error(), true));
                    show_error(lang('database_error'));
                }
            }

            if($payment['group'] == 'Cash' && $return_amt > 0){
                $payment['payment_amount'] -= $return_amt;
            }
            if($payment['payment_amount'] != 0 && $is_valid_acc){
                if(! $this->db->insert('account_trans', array(
                        'shop_id'       => SHOP_ID,
                        'type'          => $data['mode'],
                        'trans_no'      => $lastId,
                        'bank_act'      => $payment['payment_type'],
                        'trans_date'    => $data['inv_date'],
                        'person_id'     => $data['user_id'],
                        'amount'        => $payment['payment_amount']
                    ))) {
                    log_message('error', print_r($this->db->error(), true));
                }
            }
        }

        // update inventory
        if($data['mode'] == 'sale'){ 
            $operator = "-";
            $type = 'out';
        }else{
            $operator = "+";
            $type = 'in';
        }
        if($data['mode'] == 'sale' || $data['mode'] == 'sales_return'){
            foreach($data['items'] as $product) {
                $size_query = "";
                $ps = "";
                if($product['size'] != ''){
                    $prow = $this->db->where('id', $product['id'])->get('products')->row()->size;
                    $ps = "Size: ". $product['size'];
                    $psize = array();
                    if($prow != 'N' && $prow != ''){
                        $sizes = explode(";", $prow);
                        for($i=0; $i<count($sizes); $i++){
                            $per_size = explode("x", $sizes[$i]);
                            if($per_size[0] == $product['size']){
                                if($operator == '-' && (int)$per_size[1] >= $product['quantity']){
                                    $per_size[1] = (int)$per_size[1] - $product['quantity'];
                                }
                                elseif($operator == '+' && (int)$per_size[1] <= $product['quantity']){
                                    $per_size[1] = (int)$per_size[1] + $product['quantity'];
                                }
                            }
                            $psize[] = $per_size[0]."x".$per_size[1];
                        }
                        $prow = implode(";", $psize);
                        $size_query = ", size = '".$prow."'";
                    }
                }
                if (!$this->db->query('UPDATE products SET quantity=quantity' . $operator . $product['quantity']. $size_query . ' WHERE id = ' . $product['id'])) {
                    log_message('error', print_r($this->db->error(), true));
                    show_error(lang('database_error'));
                }
                $this->setProductTrans($type, $product['id'], $product['quantity'], $_SESSION['logged_user_id'], '', 1, $lastId, $ps);
            }
        } 
        
        if ($this->db->trans_status() === FALSE) {
            $this->db->trans_rollback();
            return false;
        } else {
            $this->db->trans_commit();
            return $lastId;
        }
    }

    public function getOrder($id)
    {
        $this->db->select("orders.*");
        $this->db->where("id", $id);
        $this->db->where('shop_id', SHOP_ID);
        $query = $this->db->get("orders");
        if ($query->num_rows() > 0) {
            return $query->row_array();
        } else {
            return false;
        }
    }
    
    private function getOneProductForSerialize($id)
    {
        $this->db->select('vendors.name as vendor_name, vendors.id as vendor_id, products.*, products_translations.price');
        $this->db->where('products.id', $id);
        $this->db->join('vendors', 'vendors.id = products.vendor_id', 'left');
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

    private function countCustomerByPhone($phone)
    {
        $this->db->where('phone', $phone);
        $this->db->where('shop_id', SHOP_ID);
        return $this->db->count_all_results('customer');
    }

    public function getCustomerOrder($id = 0)
    {
        $result = array();
        if($id > 0){
            $this->db->where('customer_id', $id);
            $this->db->where('shop_id', SHOP_ID);
            // return $this->db->get("orders")->result();

            $result = $this->db->get("orders")->result_array();
            // need to update the code
            // $this->db->where("trns_type", "customer_payment");
            // $this->db->where("trns_for_id", $id);
            // foreach($this->db->get("account_trans")->result_array() as $res)
            // {
            //     $result[] = array('id' => $res['id'], 'asof_date_due' => 0, 'processed'=>1, 'shipping_cost'=>0, 'other_cost'=>0, 'total'=>$res['amount'], 'order_type'=>$res['pay_type'], 'order_id'=> $res['trns_id'], 'date' => $res['trns_date']);
            // }
        }
        return $result;
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
    
	public function return_items($data)
	{
		$q = $this->db->query('SELECT MAX(order_id) as order_id FROM orders WHERE shop_id = '. SHOP_ID);
        $rr = $q->row_array();
        if ($rr['order_id'] == 0) {
            $rr['order_id'] = 1233;
        }
        $data['order_id'] = $rr['order_id'] + 1;

        $i = 0;
        $products_to_order = [];
        if(!empty($data['items'])) {
            foreach($data['items'] as $pr_val) {
                $products_to_order[] = [
                    'product_info' => $pr_val,
                    'product_quantity' => $pr_val['quantity']
                ];
            }
        }
        $data['asof'] = $data['customer_info']->balance;
        $return_amt = $change_amt = 0;
        foreach($data['get_payments'] as $payment){
            if($payment['group'] == 'Due'){
                $data['asof'] -= $payment['payment_amount'];
            }
        }
        
        $data['products'] = serialize($products_to_order);
        $this->db->trans_begin();
        if (!$this->db->insert('orders', array(
                    'shop_id'       => SHOP_ID,
                    'order_id'      => $data['order_id'],
                    'order_type'    => $data['mode'],
                    'customer_id'   => $data['customer_info']->id,
                    'products'      => $data['products'],
                    'date'          => $data['inv_date'],
                    'referrer'      => 'POS',
                    'clean_referrer'=> 'POS',
                    'payment_type'  => serialize($data['get_payments']),
                    'paypal_status' => @$data['paypal_status'],
                    'labour_cost'   => @$data['labour_cost'],
                    'carrying_cost' => @$data['carrying_cost'],
                    'other_cost'    => @$data['other_cost'],
                    'discount_code' => @$data['discount'],
                    'total'         => $data['total'],
                    'asof_date_due' => @$data['asof'],
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
                    'address'       => $data['customer_info']->address,
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
            'amt'       => $data['total'],
            'note'      => 'Sales Return',
            'trans_date'=> $data['inv_date'],
            'user_id'   => $data['user_id']
            ))) {
            log_message('error', print_r($this->db->error(), true));
        }
        
        foreach($data['get_payments'] as $payment){
            if($payment['group'] == 'Change Amount') continue;
            $is_valid_acc = true;
            
            if($payment['group'] == 'Due'){
                $is_valid_acc = false;
                if(! $this->db->query('UPDATE customer SET balance = balance - ' . $payment['payment_amount'] . ' WHERE id = ' . $data['customer_info']->id)) {
                    log_message('error', print_r($this->db->error(), true));
                    show_error(lang('database_error'));
                }
            }
            
            if($payment['payment_amount'] != 0 && $is_valid_acc){
                if(! $this->db->insert('account_trans', array(
                        'shop_id'       => SHOP_ID,
                        'type'          => $data['mode'],
                        'trans_no'      => $lastId,
                        'bank_act'      => $payment['payment_type'],
                        'trans_date'    => $data['inv_date'],
                        'person_id'     => $data['user_id'],
                        'amount'        => -($payment['payment_amount'])
                    ))) {
                    log_message('error', print_r($this->db->error(), true));
                }
            }
        }

        // update inventory
        $operator = "+";
		$type = $data['mode'];
		
        if($data['mode'] == 'sale_return'){
            foreach($data['items'] as $product) {
                $size_query = "";
                $ps = "";
                if($product['size'] != ''){
                    $prow = $this->db->where('id', $product['id'])->get('products')->row()->size;
                    $ps = "Size: ". $product['size'];
                    $psize = array();
                    if($prow != 'N' && $prow != ''){
                        $sizes = explode(";", $prow);
                        for($i=0; $i<count($sizes); $i++){
                            $per_size = explode("x", $sizes[$i]);
                            if($per_size[0] == $product['size']){
                                if($operator == '+' && (int)$per_size[1] <= $product['quantity']){
                                    $per_size[1] = (int)$per_size[1] + $product['quantity'];
                                }
                            }
                            $psize[] = $per_size[0]."x".$per_size[1];
                        }
                        $prow = implode(";", $psize);
                        $size_query = ", size = '".$prow."'";
                    }
                }
                if (!$this->db->query('UPDATE products SET quantity=quantity' . $operator . $product['quantity']. $size_query . ' WHERE id = ' . $product['id'])) {
                    log_message('error', print_r($this->db->error(), true));
                    show_error(lang('database_error'));
                }
                $this->setProductTrans($type, $product['id'], $product['quantity'], $_SESSION['logged_user_id'], '', 1, $lastId, $ps);
            }
        } 
        
        if ($this->db->trans_status() === FALSE) {
            $this->db->trans_rollback();
            return false;
        } else {
            $this->db->trans_commit();
            return $lastId;
        }
	}
	
    public function return_acc()
	{
		$this->db->where('accounts.shop_id', SHOP_ID);
		$this->db->where('accounts.type', 'Cash');
		$this->db->where('accounts.name', 'Cash');
		return $this->db->get('accounts')->row()->id;
	}

    public function return_trans($id, $post)
    {
        $order = $this->getOrder($id);
        $inv_pro = unserialize($order['products']);
        
        // file_put_contents("lineno.txt", $post["total_line"]);
        // $i = 0;
        // file_put_contents("qty.txt", $post["qty".$i]);


        $this->db->trans_begin();

        $total_return = 0; $return_items = [];
        for($i=0; $i<$post["total_line"]; $i++){
            if(isset($post["item_select".$i]) && $post["item_select".$i]>0){
                $item_id = $post["item_select".$i];
                // total qty return
                if($post["qty".$i] == $post["old_qty".$i]){
                    if(! $this->db->delete('product_trans', array('ref_id'=> $id, 'ref_type'=> 'out', 'item_id'=> $item_id, 'shop_id'=> SHOP_ID))){
                        log_message('error', print_r($this->db->error(), true));
                    }
                }
                // some qty return
                elseif($post["qty".$i] < $post["old_qty".$i]){
                    $rqty = $post["old_qty".$i] - $post["qty".$i];
                    if(! $this->db->update('product_trans', array("quantity"=> $rqty), array('ref_id'=> $id, 'ref_type'=> 'out', 'item_id'=> $item_id, 'shop_id'=> SHOP_ID))){
                        log_message('error', print_r($this->db->error(), true));
                    }
                }

                // product in 
                if (! $this->db->query('UPDATE products SET quantity=quantity+' . $post["qty".$i] . ' WHERE shop_id = '.SHOP_ID.' AND id = ' . $item_id)) {
                    log_message('error', print_r($this->db->error(), true));
                }

                foreach($inv_pro as $line=>$item) { 
                    if($item['product_info']['id'] == $item_id){
                        $total_return += $item['product_info']['price'] * $post["qty".$i];
                        $return_items[] = [
                            'id' => $item_id,
                            'quantity' => $post["qty". $i],
                            'name' => $item['product_info']['name'],
                            'description' => $item['product_info']['description'],
                            'image' => $item['product_info']['image'],
                            'url' => $item['product_info']['url'],
                            'price' => $item['product_info']['price'],
                        ];
                    }
                }
                
            }
            
        }
    
        if($order['total'] == $total_return){
            if(! $this->db->delete('cus_sup_trans', array('trans_no'=>$id, 'trans_for'=> $order['customer_id'], 'type'=> 'customer', 'shop_id'=> SHOP_ID))){
                log_message('error', print_r($this->db->error(), true));
            }

            if(! $this->db->delete('orders_clients', array('for_id'=>$id, 'customer_id'=> $order['customer_id'], 'shop_id'=> SHOP_ID))){
                log_message('error', print_r($this->db->error(), true));
            }
        }
        else{
            if(! $this->db->update('cus_sup_trans', array('amt'=> ($order['total'] - $total_return)), array('trans_no'=>$id, 'trans_for'=> $order['customer_id'], 'type'=> 'customer', 'shop_id'=> SHOP_ID))){
                log_message('error', print_r($this->db->error(), true));
            }
        }
        
        //return policy return cash
        if($post['salesReturn'] == 1){
			if(! $this->db->insert('account_trans', array(
				'shop_id'       => SHOP_ID,
				'type'          => 'sale_return',
				'trans_no'      => $id,
				'bank_act'      => $this->return_acc(),
				'trans_date'    => date('Y-m-d'),
				'person_id'     => $_SESSION['logged_user_id'],
				'amount'        => -($total_return)
				))) {
				log_message('error', print_r($this->db->error(), true));
			}
		}else{
			$cus_bal = $this->get_customer_info($order['customer_id'])->balance;
			// jodi customer er kace baki thake
			if($cus_bal > 0){
				$bal4update = 0;
				// jodi baki return kora ponner thake dam besi hoy
				if($cus_bal >= $total_return){
					// return kora invoice er dam dia customarer baki komano holo
					$bal4update = $total_return;
				}
				// bakir thake dam kom hole
				else{
					// dam thake baki bad dia holo & baki taka session a add kore dia holo
					$_SESSION['return_amount_'.$order['customer_id']] = $total_return - $cus_bal;
					// baki 0 kore dia holo
					$bal4update = $cus_bal;
				}
				// jodi balance update kora lage
				if($bal4update > 0){
					if(! $this->db->query('UPDATE customer SET balance = balance - ' . $bal4update . ' WHERE shop_id = '.SHOP_ID.' AND id = ' . $order['customer_id'])) {
						log_message('error', print_r($this->db->error(), true));
					}
				}
			}
			// customer er kace kono baki nai, nogode sale kora hoile
			else{
				// invoice er taka session a add kore dia holo
				$_SESSION['return_amount_'.$order['customer_id']] = $total_return;
			}
		}

        
        if(! $this->db->update('orders', array("order_type"=>"sale_return", "note"=>$post["return_note"], "return_items"=>serialize($return_items)), array('id'=>$id, 'shop_id'=> SHOP_ID))){
            log_message('error', print_r($this->db->error(), true));
        }

        if ($this->db->trans_status() === FALSE) {
            $this->db->trans_rollback();
            return false;
        } else {
            $this->db->trans_commit();
            return true;
        }       
    }
    
    public function void_trans($id)
    {
        $order = $this->getOrder($id);
        
        $this->db->trans_begin();

        if(! $this->db->delete('product_trans', array('ref_id'=> $id, 'ref_type'=> 'out', 'shop_id'=> SHOP_ID))){
            log_message('error', print_r($this->db->error(), true));
        }
        
        foreach(unserialize($order['products']) as $line=>$item) { 
            if (! $this->db->query('UPDATE products SET quantity=quantity+' . $item['product_info']['quantity'] . ' WHERE shop_id = '.SHOP_ID.' AND id = ' . $item['product_info']['id'])) {
                log_message('error', print_r($this->db->error(), true));
            }
        }

        if(! $this->db->delete('cus_sup_trans', array('trans_no'=>$id, 'trans_for'=> $order['customer_id'], 'type'=> 'customer', 'shop_id'=> SHOP_ID))){
            log_message('error', print_r($this->db->error(), true));
        }
        
        if(! $this->db->delete('orders_clients', array('for_id'=>$id, 'customer_id'=> $order['customer_id'], 'shop_id'=> SHOP_ID))){
            log_message('error', print_r($this->db->error(), true));
        }
        
        if(! $this->db->delete('account_trans', array('trans_no'=>$id, 'type'=> 'sale', 'shop_id'=> SHOP_ID))){
            log_message('error', print_r($this->db->error(), true));
        }

        $cus_bal = $this->get_customer_info($order['customer_id'])->balance;
        
        $oCashAmt = 0;
        $oDueAmt = 0;
        foreach(unserialize($order['payment_type']) as $pay){
            if($pay['group'] == 'Due') {
                $oDueAmt += $pay['payment_amount'];
            }
        }
        
        // jodi customer er kace baki thake
        if($cus_bal > 0){
            if(! $this->db->query('UPDATE customer SET balance = balance - ' . $oDueAmt . ' WHERE shop_id = '.SHOP_ID.' AND id = ' . $order['customer_id'])) {
                    log_message('error', print_r($this->db->error(), true));
            }
        }

        if(! $this->db->update('orders', array("order_type"=>"sale_void", "note"=>"User delete this invoice"), array('id'=>$id, 'shop_id'=> SHOP_ID))){
            log_message('error', print_r($this->db->error(), true));
        }

        if ($this->db->trans_status() === FALSE) {
            $this->db->trans_rollback();
            return false;
        } else {
            $this->db->trans_commit();
            return true;
        }       
    }
    
    public function updateOrder($order, $data)
    {
        $oldCash = $newCash = 0;
        $oldDue = $newDue = 0;
        foreach(unserialize($order['payment_type']) as $pay){
            if($pay['group'] == 'Cash') $oldCash += $pay['payment_amount'];
            elseif($pay['group'] == 'Due') $oldDue += $pay['payment_amount'];
        }
        
        foreach($data['get_payments'] as $pay){
            if($pay['group'] == 'Cash') $newCash += $pay['payment_amount'];
            elseif($pay['group'] == 'Due') $newDue += $pay['payment_amount'];
        }
        
        if($order['order_type'] == 'sale'){
        
            $asof = $order['asof_date_due'] - $oldDue + $newDue;
            
            $this->db->trans_begin();
            if (!$this->db->update('orders', array('payment_type' => serialize($data['get_payments']),'asof_date_due' => @$asof, 'note'=> 'Invoice update by '.$data['logged_in'].' ('.$data['user_id'].') at '. date("Y-m-d H:i:s")), array('shop_id' => SHOP_ID, 'order_id' => $order['order_id'] ) ) ) {
                log_message('error', print_r($this->db->error(), true));
            }
            $lastId = $order['order_id'];
            // insert payment option
            if($oldCash != $newCash){
                if($oldCash > 0){ // update old trns
                    foreach($data['get_payments'] as $payment){
                        if($payment['group'] == 'Cash'){
                            if(! $this->db->update('account_trans', array('amount' => $payment['payment_amount']), array('shop_id' => SHOP_ID, 'type' => 'sale', 'trans_no' => $lastId, 'bank_act' => $payment['payment_type']))) {
                                log_message('error', print_r($this->db->error(), true));
                            }
                        }
                    }
                }
                elseif($oldCash == 0 && $newCash > 0){ // insert new trns
                    foreach($data['get_payments'] as $payment){
                        if($payment['group'] == 'Cash'){
                            if(! $this->db->insert('account_trans', array(
                                    'shop_id'       => SHOP_ID,
                                    'type'          => 'sale',
                                    'trans_no'      => $lastId,
                                    'bank_act'      => $payment['payment_type'],
                                    'trans_date'    => $order['date'],
                                    'person_id'     => $data['user_id'],
                                    'amount'        => $payment['payment_amount']
                                ))) {
                                log_message('error', print_r($this->db->error(), true));
                            }
                        }
                    }
                }
            }
            if($oldDue != $newDue){
                if($oldDue > 0){ // update old trns
                    if(! $this->db->query('UPDATE customer SET balance = balance + ' . ($newDue  - $oldDue) . ' WHERE id = ' . $order['customer_id'])) {
                        log_message('error', print_r($this->db->error(), true));
                        show_error(lang('database_error'));
                    }
                }
                elseif($oldDue == 0 && $newCash > 0){ // insert new trns
                    if(! $this->db->query('UPDATE customer SET balance = balance + ' . $newDue . ' WHERE id = ' . $order['customer_id'])) {
                        log_message('error', print_r($this->db->error(), true));
                        show_error(lang('database_error'));
                    }
                }
            }
        }
        elseif($order['order_type'] == 'sale_return'){
            $asof = $order['asof_date_due'] + $oldDue - $newDue;
            
            $this->db->trans_begin();
            if (!$this->db->update('orders', array('payment_type' => serialize($data['get_payments']),'asof_date_due' => @$asof, 'note'=> 'Invoice update by '.$data['logged_in'].' ('.$data['user_id'].') at '. date("Y-m-d H:i:s")), array('shop_id' => SHOP_ID, 'order_id' => $order['order_id'] ) ) ) {
                log_message('error', print_r($this->db->error(), true));
            }
            $lastId = $order['order_id'];
            // insert payment option
            if($oldCash != $newCash){
                if($oldCash > 0){ // update old trns
                    foreach($data['get_payments'] as $payment){
                        if($payment['group'] == 'Cash'){
                            $uc = $oldCash - $newCash;
                            if(! $this->db->update('account_trans', array('amount' => $uc), array('shop_id' => SHOP_ID, 'type' => 'sale', 'trans_no' => $lastId, 'bank_act' => $payment['payment_type']))) {
                                log_message('error', print_r($this->db->error(), true));
                            }
                        }
                    }
                }
                elseif($oldCash == 0 && $newCash > 0){ // insert new trns
                    foreach($data['get_payments'] as $payment){
                        if($payment['group'] == 'Cash'){
                            if(! $this->db->insert('account_trans', array(
                                    'shop_id'       => SHOP_ID,
                                    'type'          => 'sale_return',
                                    'trans_no'      => $lastId,
                                    'bank_act'      => $payment['payment_type'],
                                    'trans_date'    => $order['date'],
                                    'person_id'     => $data['user_id'],
                                    'amount'        => -($payment['payment_amount'])
                                ))) {
                                log_message('error', print_r($this->db->error(), true));
                            }
                        }
                    }
                }
            }
            if($oldDue != $newDue){
                if($oldDue > 0){ // update old trns
                    if(! $this->db->query('UPDATE customer SET balance = balance - ' . $newDue . ' WHERE id = ' . $order['customer_id'])) {
                        log_message('error', print_r($this->db->error(), true));
                        show_error(lang('database_error'));
                    }
                }
                elseif($oldDue >= 0 && $newCash > 0){ // insert new trns
                    if(! $this->db->query('UPDATE customer SET balance = balance - ' . $newDue . ' WHERE id = ' . $order['customer_id'])) {
                        log_message('error', print_r($this->db->error(), true));
                        show_error(lang('database_error'));
                    }
                }
            }
        }
        
        if ($this->db->trans_status() === FALSE) {
            $this->db->trans_rollback();
            return false;
        } else {
            $this->db->trans_commit();
            return $lastId;
        }
    }
}