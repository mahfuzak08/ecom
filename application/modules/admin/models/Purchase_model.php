<?php

class Purchase_model extends CI_Model
{

    public function __construct()
    {
        parent::__construct();
    }

    public function get_payment_type()
    {
        $this->db->select('accounts.*, SUM(account_trans.amount) as balance');
        $this->db->join('account_trans', 'account_trans.bank_act = accounts.id', 'left');
        $this->db->group_by('account_trans.bank_act');
        $this->db->where('accounts.shop_id', SHOP_ID);
		return $this->db->get('accounts')->result();
    }

    public function get_search_suggestions($mode, $search, $filters = array('is_delete' => 0), $limit = 25)
    {
        $suggestions = array();
        if($mode == 'purchase' || $mode == 'purchase_return'){
            // $this->db->select('products.id, products_translations.title');
            // $this->db->from('products');
            // $this->db->join('products_translations', 'products.id = products_translations.for_id', 'left');
		    // $this->db->where('products_translations.abbr', MY_DEFAULT_LANGUAGE_ABBR);
            // $this->db->where('products.visibility', $filters['is_delete']);
            // $this->db->where('shop_id', SHOP_ID);
            // $this->db->like('products_translations.title', $search);
			// $this->db->or_like('products.barcode', $search);
            // $this->db->order_by('products_translations.title', 'asc');
            // foreach($this->db->get()->result() as $row)
            // {
                // $suggestions[] = array('value' => $row->id, 'label' => $row->title);
            // }
			
			$str = "SELECT p.id, pt.title FROM products as p LEFT JOIN products_translations as pt ON p.id = pt.for_id WHERE pt.abbr = '". MY_DEFAULT_LANGUAGE_ABBR ."' AND p.shop_id = '".SHOP_ID."' AND p.visibility = '".$filters['is_delete']."' AND (pt.title LIKE '%".$search."%' OR p.barcode = '".$search."')";
			
            foreach($this->db->query($str)->result() as $row)
            {
                $suggestions[] = array('value' => $row->id, 'label' => $row->title);
            }
        }
		elseif($mode == 'search_purchase_invoice'){
            $this->db->select('id, order_id');
            $this->db->from('purchase_orders');
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
	
	public function get_supplier_search_suggestions($search, $limit = 25)
    {
        $suggestions = array();
		$this->db->select('id, name');
        $this->db->from('vendors');
        $this->db->where('shop_id', SHOP_ID);
		// $this->db->where('visibility', $filters['is_delete']);
		if(is_numeric($search)) $this->db->like('mobile', $search);
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

	public function get_supplier_info($id)
	{
		$this->db->select('id, name, mobile, email, url, balance');
		$this->db->from('vendors');
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
    
    public function add_new_supplier($name, $phone)
    {
        if($this->countSupplierByPhone($phone) == 0){
            $this->db->insert('vendors', array(
                    'shop_id'       => SHOP_ID,
                    'name'          => $name,
                    'mobile'        => $phone
            ));
            return $this->db->insert_id();
        }
        else
            return -1;
    }

	public function setOrder($data)
    {
        $q = $this->db->query('SELECT MAX(order_id) as order_id FROM purchase_orders WHERE shop_id ='.SHOP_ID);
        $rr = $q->row_array();
        if ($rr['order_id'] == 0)
            $data['order_id'] = 1;
        else
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
        $data['asof'] = $data['supplier_info']->balance;
        $change_amt = 0;
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
        }

        $data['products'] = serialize($products_to_order);
        $this->db->trans_begin();
        if (!$this->db->insert('purchase_orders', array(
                    'shop_id'       => SHOP_ID,
                    'order_id'      => $data['order_id'],
                    'order_type'    => $data['mode'],
                    'supplier_id'   => $data['supplier_info']->id,
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
        if (!$this->db->insert('cus_sup_trans', array(
            'shop_id'   => SHOP_ID,
            'type'      => 'supplier',
            'trans_for' => $data['supplier_info']->id,
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
            $is_valid_acc = true;

            if($payment['group'] == 'Cash' && $change_amt < 0){
                $payment['payment_amount'] += $change_amt;
            }

            if($payment['group'] == 'Due'){
                $is_valid_acc = false;
                if(! $this->db->query('UPDATE vendors SET balance = balance + ' . $payment['payment_amount'] . ' WHERE id = ' . $data['supplier_info']->id)) {
                    log_message('error', print_r($this->db->error(), true));
                    show_error(lang('database_error'));
                }
            }
            
            if($payment['group'] == 'GiftCard'){
                $is_valid_acc = false;
                if(! $this->db->query('UPDATE vendors SET balance = balance + ' . $payment['payment_amount'] . ' WHERE id = ' . $data['supplier_info']->id)) {
                    log_message('error', print_r($this->db->error(), true));
                    show_error(lang('database_error'));
                }
            }
            
            // if($payment['payment_title'] == 'Advance'){
            //     if(! $this->db->query('UPDATE vendors SET balance = balance - ' . $payment['payment_amount'] . ' WHERE id = ' . $data['supplier_info']->id)) {
            //         log_message('error', print_r($this->db->error(), true));
            //         show_error(lang('database_error'));
            //     }
            // }
            
            if($payment['group'] == 'Collection'){
                $is_valid_acc = false;
                if (!$this->db->insert('cus_sup_trans', array(
                    'shop_id'   => SHOP_ID,
                    'type'      => 'supplier',
                    'trans_for' => $data['supplier_info']->id,
                    'trans_no'  => $lastId,
                    'amt'       => $payment['payment_amount'],
                    'note'      => 'Due Payment',
                    'trans_date'=> $data['inv_date'],
                    'user_id'   => $data['user_id']
                    ))) {
                    log_message('error', print_r($this->db->error(), true));
                }
                if(! $this->db->query('UPDATE vendors SET balance = balance - ' . $payment['payment_amount'] . ' WHERE id = ' . $data['supplier_info']->id)) {
                    log_message('error', print_r($this->db->error(), true));
                    show_error(lang('database_error'));
                }
            }

            if($payment['payment_amount'] != 0 && $is_valid_acc){
                if($payment['payment_amount']  != 0){
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
        }

        // update inventory
        if($data['mode'] == 'purchase'){ 
            $operator = "+";
            $type = 'in';
        }else{
            $operator = "-";
            $type = 'out';
        }
        if($data['mode'] == 'purchase' || $data['mode'] == 'purchase_return'){
            foreach($data['items'] as $product) {
                if (!$this->db->query('UPDATE products SET quantity=quantity' . $operator . $product['quantity'] . ' WHERE id = ' . $product['id'])) {
                    log_message('error', print_r($this->db->error(), true));
                    show_error(lang('database_error'));
                }
                if (!$this->db->query('UPDATE products_translations SET buy_price='.$product['price'].' WHERE for_id = ' . $product['id'])) {
                    log_message('error', print_r($this->db->error(), true));
                    show_error(lang('database_error'));
                }
                $this->setProductTrans($type, $product['id'], $product['quantity'], $_SESSION['logged_user_id'], '', 1, $lastId, '');
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
        $this->db->select("purchase_orders.*");
        $this->db->where("id", $id);
        $this->db->where('shop_id', SHOP_ID);
        $query = $this->db->get("purchase_orders");
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

    private function countSupplierByPhone($phone)
    {
        $this->db->where('mobile', $phone);
        $this->db->where('shop_id', SHOP_ID);
        return $this->db->count_all_results('vendors');
    }

    public function getCustomerOrder($id = 0)
    {
        $result = array();
        if($id > 0){
            $this->db->where('vendor_id', $id);
            $this->db->where('shop_id', SHOP_ID);
            // return $this->db->get("purchase_orders")->result();

            $result = $this->db->get("purchase_orders")->result_array();
            // need to be adjust for new code
            // $this->db->where("trns_type", "vendor_payment");
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
        
        $this->db->trans_begin();

        $total_return = 0; $return_items = [];
        for($i=0; $i<$post["total_line"]; $i++){
            if(isset($post["item_select".$i]) && $post["item_select".$i]>0){
                $item_id = $post["item_select".$i];
                // total qty return
                if($post["qty".$i] == $post["old_qty".$i]){
                    if(! $this->db->delete('product_trans', array('ref_id'=> $id, 'ref_type'=> 'in', 'item_id'=> $item_id, 'shop_id'=> SHOP_ID))){
                        log_message('error', print_r($this->db->error(), true));
                    }
                }
                // some qty return
                elseif($post["qty".$i] < $post["old_qty".$i]){
                    $rqty = $post["old_qty".$i] - $post["qty".$i];
                    if(! $this->db->update('product_trans', array("quantity"=> $rqty), array('ref_id'=> $id, 'ref_type'=> 'in', 'item_id'=> $item_id, 'shop_id'=> SHOP_ID))){
                        log_message('error', print_r($this->db->error(), true));
                    }
                }

                // product out
                if (! $this->db->query('UPDATE products SET quantity=quantity-' . $post["qty".$i] . ' WHERE shop_id = '.SHOP_ID.' AND id = ' . $item_id)) {
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
            if(! $this->db->delete('cus_sup_trans', array('trans_no'=>$id, 'trans_for'=> $order['supplier_id'], 'type'=> 'supplier', 'shop_id'=> SHOP_ID))){
                log_message('error', print_r($this->db->error(), true));
            }

            // if(! $this->db->delete('orders_clients', array('for_id'=>$id, 'supplier_id'=> $order['supplier_id'], 'shop_id'=> SHOP_ID))){
                // log_message('error', print_r($this->db->error(), true));
            // }
        }
        else{
            if(! $this->db->update('cus_sup_trans', array('amt'=> ($order['total'] - $total_return)), array('trans_no'=>$id, 'trans_for'=> $order['supplier_id'], 'type'=> 'supplier', 'shop_id'=> SHOP_ID))){
                log_message('error', print_r($this->db->error(), true));
            }
        }
        
		//return policy return cash
        if($post['purchaseReturn'] == 1){
			if(! $this->db->insert('account_trans', array(
				'shop_id'       => SHOP_ID,
				'type'          => 'purchase_return',
				'trans_no'      => $id,
				'bank_act'      => $this->return_acc(),
				'trans_date'    => date('Y-m-d'),
				'person_id'     => $_SESSION['logged_user_id'],
				'amount'        => $total_return
				))) {
				log_message('error', print_r($this->db->error(), true));
			}
		}else{
			// $cus_bal = $this->get_supplier_info($order['supplier_id'])->balance;
			// // jodi supplier er kace baki thake
			// if($cus_bal > 0){
				// $bal4update = 0;
				// // jodi baki return kora ponner thake dam besi hoy
				// if($cus_bal >= $total_return){
					// // return kora invoice er dam dia customarer baki komano holo
					// $bal4update = $total_return;
				// }
				// // bakir thake dam kom hole
				// else{
					// // dam thake baki bad dia holo & baki taka session a add kore dia holo
					// $_SESSION['return_amount_'.$order['supplier_id']] = $total_return - $cus_bal;
					// // baki 0 kore dia holo
					// $bal4update = $cus_bal;
				// }
				// // jodi balance update kora lage
				// if($bal4update > 0){
					// if(! $this->db->query('UPDATE customer SET balance = balance - ' . $bal4update . ' WHERE shop_id = '.SHOP_ID.' AND id = ' . $order['supplier_id'])) {
						// log_message('error', print_r($this->db->error(), true));
					// }
				// }
			// }
			// // customer er kace kono baki nai, nogode sale kora hoile
			// else{
				// // invoice er taka session a add kore dia holo
				// $_SESSION['return_amount_'.$order['supplier_id']] = $total_return;
			// }
		}
        
        if(! $this->db->update('purchase_orders', array("order_type"=>"purchase_return", "note"=>$post["return_note"], "return_items"=>serialize($return_items)), array('id'=>$id, 'shop_id'=> SHOP_ID))){
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
	
	public function return_items($data)
	{
		$q = $this->db->query('SELECT MAX(order_id) as order_id FROM purchase_orders WHERE shop_id ='.SHOP_ID);
        $rr = $q->row_array();
        if ($rr['order_id'] == 0)
            $data['order_id'] = 1;
        else
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
        $data['asof'] = $data['supplier_info']->balance;
        $change_amt = 0;
        foreach($data['get_payments'] as $payment){
            if($payment['group'] == 'Due'){
                $data['asof'] -= $payment['payment_amount'];
            }
        }

        $data['products'] = serialize($products_to_order);
        $this->db->trans_begin();
        if (!$this->db->insert('purchase_orders', array(
                    'shop_id'       => SHOP_ID,
                    'order_id'      => $data['order_id'],
                    'order_type'    => $data['mode'],
                    'supplier_id'   => $data['supplier_info']->id,
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
        if (!$this->db->insert('cus_sup_trans', array(
            'shop_id'   => SHOP_ID,
            'type'      => 'supplier',
            'trans_for' => $data['supplier_info']->id,
            'trans_no'  => $lastId,
            'amt'       => $data['total'],
            'note'      => 'Purchase Return',
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
                if(! $this->db->query('UPDATE vendors SET balance = balance - ' . $payment['payment_amount'] . ' WHERE id = ' . $data['supplier_info']->id)) {
                    log_message('error', print_r($this->db->error(), true));
                    show_error(lang('database_error'));
                }
            }
            
            if($payment['payment_amount'] != 0 && $is_valid_acc){
                if($payment['payment_amount']  != 0){
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
        }

		$operator = "-";
		$type = $data['mode'];
        
		if($data['mode'] == 'purchase_return'){
            foreach($data['items'] as $product) {
                if (!$this->db->query('UPDATE products SET quantity=quantity' . $operator . $product['quantity'] . ' WHERE id = ' . $product['id'])) {
                    log_message('error', print_r($this->db->error(), true));
                    show_error(lang('database_error'));
                }
                if (!$this->db->query('UPDATE products_translations SET buy_price='.$product['price'].' WHERE for_id = ' . $product['id'])) {
                    log_message('error', print_r($this->db->error(), true));
                    show_error(lang('database_error'));
                }
                $this->setProductTrans($type, $product['id'], $product['quantity'], $_SESSION['logged_user_id'], '', 1, $lastId, '');
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