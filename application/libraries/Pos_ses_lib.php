<?php

defined('BASEPATH') OR exit('No direct script access allowed');

class Pos_ses_lib
{

    private $CI;

    public function __construct()
	{
		$this->CI =& get_instance();
		// $this->CI->load->library('tax_lib');
		// $this->CI->load->model('enums/Rounding_mode');
	}

    public function get_mode()
	{
		if(!$this->CI->session->userdata('sales_mode'))
		{
			$this->set_mode('sale');
		}

		return $this->CI->session->userdata('sales_mode');
	}

	public function set_mode($mode)
	{
        $this->CI->session->set_userdata('sales_mode', $mode);
	}

	public function clear_mode()
	{
		$this->CI->session->unset_userdata('sales_mode');
	}
	
	public function get_inv_date()
	{
		if(!$this->CI->session->userdata('inv_date'))
		{
			$this->set_inv_date(date("Y-m-d"));
		}

		return $this->CI->session->userdata('inv_date');
	}

	public function set_inv_date($inv_date)
	{
        $this->CI->session->set_userdata('inv_date', $inv_date);
	}

	public function clear_inv_date()
	{
		$this->CI->session->unset_userdata('inv_date');
	}

	public function get_tab()
	{
		if(!$this->CI->session->userdata('sales_tab'))
		{
			$this->set_tab(1);
		}

		return $this->CI->session->userdata('sales_tab');
	}

	public function set_tab($tab)
	{
		$this->CI->session->set_userdata('sales_tab', $tab);
	}

	public function remove_tab()
	{
		$this->CI->session->unset_userdata('sales_tab');
	}

	public function get_customer()
	{
		if(!$this->CI->session->userdata('sales_customer'))
		{
			$this->set_customer(-1);
		}

		return $this->CI->session->userdata('sales_customer');
	}

	public function set_customer($customer_id)
	{
		$this->CI->session->set_userdata('sales_customer', $customer_id);
	}

	public function remove_customer()
	{
		$this->CI->session->unset_userdata('sales_customer');
	}
	
	public function get_due_collect()
	{
		if(!$this->CI->session->userdata('sales_due_collect'))
		{
			$this->set_due_collect('yes');
		}

		return $this->CI->session->userdata('sales_due_collect');
	}

	public function set_due_collect($due_collect)
	{
		$this->CI->session->set_userdata('sales_due_collect', $due_collect);
	}

	public function remove_due_collect()
	{
		$this->CI->session->unset_userdata('sales_due_collect');
	}
	
	public function get_labour_cost()
	{
		if(!$this->CI->session->userdata('sales_labour_cost'))
		{
			$this->set_labour_cost(0.00);
		}

		return $this->CI->session->userdata('sales_labour_cost');
	}

	public function set_labour_cost($labour_cost)
	{
		$this->CI->session->set_userdata('sales_labour_cost', $labour_cost);
	}

	public function remove_labour_cost()
	{
		$this->CI->session->unset_userdata('sales_labour_cost');
	}
	
	public function get_carrying_cost()
	{
		if(!$this->CI->session->userdata('sales_carrying_cost'))
		{
			$this->set_carrying_cost(0.00);
		}

		return $this->CI->session->userdata('sales_carrying_cost');
	}

	public function set_carrying_cost($carrying_cost)
	{
		$this->CI->session->set_userdata('sales_carrying_cost', $carrying_cost);
	}

	public function remove_carrying_cost()
	{
		$this->CI->session->unset_userdata('sales_carrying_cost');
	}
	
	public function get_other_cost()
	{
		if(!$this->CI->session->userdata('sales_other_cost'))
		{
			$this->set_other_cost(0.00);
		}

		return $this->CI->session->userdata('sales_other_cost');
	}

	public function set_other_cost($other_cost)
	{
		$this->CI->session->set_userdata('sales_other_cost', $other_cost);
	}

	public function remove_other_cost()
	{
		$this->CI->session->unset_userdata('sales_other_cost');
	}
	
	public function get_discount()
	{
		if(!$this->CI->session->userdata('sales_discount'))
		{
			$this->set_discount(0.00);
		}

		return $this->CI->session->userdata('sales_discount');
	}

	public function set_discount($discount)
	{
		$this->CI->session->set_userdata('sales_discount', $discount);
	}

	public function remove_discount()
	{
		$this->CI->session->unset_userdata('sales_discount');
	}
	

	public function add_item(&$item_id, $quantity = 1, $size="", $item_location = 1, $discount = 0, $price_mode = 0, $description = NULL, $serialnumber = NULL, $include_deleted = FALSE, $print_option = NULL )
	{
		$item_info = $this->CI->Sales_model->get_info_by_id_or_number($item_id);

		//make sure item exists
		if(empty($item_info))
		{
			$item_id = -1;
			return FALSE;
		}
		else{
			$item_info->is_serialized = 0;
		}

		$item_id = $item_info->id;
		$in_stock = $item_info->quantity;
		if($in_stock < $quantity && !$this->CI->load->get_var('hasStock') && $this->get_mode() == 'sale'){
			$this->CI->session->set_flashdata('error', 'Insufficient quantity in stock.');
			$item_id = -1;
			return FALSE;
		}
		if($item_info->price > 0){
			if(strpos($this->get_mode(), 'sale') !== false) 
				$price = $item_info->price;
			else 
				$price = $item_info->buy_price;
			$cost_price = $item_info->buy_price;
			// $cost_price = $item_info->cost_price;
		}
		else
		{
			$price= 0.00;
			$cost_price = 0.00;
		}

		// Serialization and Description

		//Get all items in the cart so far...
		$items = $this->get_cart();

		//We need to loop through all items in the cart.
		//If the item is already there, get it's key($updatekey).
		//We also need to get the next key that we are going to use in case we need to add the
		//item to the cart. Since items can be deleted, we can't use a count. we use the highest key + 1.

		$maxkey = 0;                       //Highest key so far
		$itemalreadyinsale = FALSE;        //We did not find the item yet.
		$insertkey = 0;                    //Key to use for new entry.
		$updatekey = 0;                    //Key to use to update(quantity)

		foreach($items as $item)
		{
			//We primed the loop so maxkey is 0 the first time.
			//Also, we have stored the key in the element itself so we can compare.

			if($maxkey <= $item['line'])
			{
				$maxkey = $item['line'];
			}

			if($item['id'] == $item_id)
			{
				$itemalreadyinsale = TRUE;
				$updatekey = $item['line'];
				if(!$item_info->is_serialized)
				{
					$quantity = bcadd($quantity, $items[$updatekey]['quantity']);
				}
			}
		}

		$insertkey = $maxkey + 1;
		//array/cart records are identified by $insertkey and item_id is just another field.

		$total = $this->get_item_total($quantity, $price);
		$discounted_total = $this->get_item_total($quantity, $price);
		//Item already exists and is not serialized, add to quantity
		if(!$itemalreadyinsale || $item_info->is_serialized)
		{
			$item = array($insertkey => array(
					'id' => $item_id,
					'line' => $insertkey,
					'name' => $item_info->title,
					'description' => $description != NULL ? $description : strip_tags($item_info->description),
					'size' => $size,
					'serialnumber' => $serialnumber != NULL ? $serialnumber : '',
					'is_serialized' => $item_info->is_serialized,
					'quantity' => $quantity,
					'in_stock' => $in_stock,
					'wholesale_price' => $item_info->wholesale_price,
					'stock_size' => $item_info->size,
					'price' => $price,
					'cost_price' => $cost_price,
					'total' => $total,
					'shop_categorie' => $item_info->shop_categorie,
					'procurement' => $item_info->procurement,
					'url' => $item_info->url,
					'image' => $item_info->image,
					'brand_id' => $item_info->brand_id,
					'vendor_id' => $item_info->vendor_id,
					'discounted_total' => $discounted_total
				)
			);
			//add to existing array
			$items += $item;
		}
		else
		{
			$line = &$items[$updatekey];
			$line['quantity'] = $quantity;
			$line['total'] = $total;
			$line['discounted_total'] = $discounted_total;
		}

		$this->set_cart($items);

		return TRUE;
	}

	public function out_of_stock($item_id, $item_location)
	{
		//make sure item exists
		if($item_id != -1)
		{
			$item_info = $this->CI->Item->get_info_by_id_or_number($item_id);

			if($item_info->stock_type == HAS_STOCK)
			{
				$item_quantity = $this->CI->Item_quantity->get_item_quantity($item_id, $item_location)->quantity;
				$quantity_added = $this->get_quantity_already_added($item_id, $item_location);

				if($item_quantity - $quantity_added < 0)
				{
					return $this->CI->lang->line('sales_quantity_less_than_zero');
				}
				elseif($item_quantity - $quantity_added < $item_info->reorder_level)
				{
					return $this->CI->lang->line('sales_quantity_less_than_reorder_level');
				}
			}
		}

		return '';
	}

	public function get_quantity_already_added($item_id, $item_location)
	{
		$items = $this->get_cart();
		$quanity_already_added = 0;
		foreach($items as $item)
		{
			if($item['item_id'] == $item_id && $item['item_location'] == $item_location)
			{
				$quanity_already_added+=$item['quantity'];
			}
		}

		return $quanity_already_added;
	}

	public function get_item_id($line_to_get)
	{
		$items = $this->get_cart();

		foreach($items as $line=>$item)
		{
			if($line == $line_to_get)
			{
				return $item['item_id'];
			}
		}

		return -1;
	}

	public function edit_item($line, $item_name, $serialnumber, $quantity, $price, $description, $size="")
	{
		$items = $this->get_cart();
		if(isset($items[$line]))
		{
			$line = &$items[$line];
			if($line['in_stock'] < $quantity && !$this->CI->load->get_var('hasStock') && $this->get_mode() == 'sale'){
				$this->CI->session->set_flashdata('error', 'Insufficient quantity in stock.');
				return FALSE;
			}
			if($size != "" && ! $this->CI->load->get_var('hasStock') && $this->get_mode() == 'sale'){
				$ss = explode(";", $line['stock_size']);
				for($i=0; $i<count($ss); $i++){
					$s = explode("x", $ss[$i]);
					if($s[0] == $size && $s[1]  < $quantity){
						$this->CI->session->set_flashdata('error', 'Insufficient size in stock.');
						return FALSE;
					}
				}
			}
			$line['name'] = $item_name;
			$line['description'] = $description;
			$line['serialnumber'] = $serialnumber;
			$line['quantity'] = $quantity;
			$line['size'] = $size;
			$line['price'] = $price;
			$line['total'] = $this->get_item_total($quantity, $price);
			$this->set_cart($items);
		}

		return FALSE;
	}

	public function delete_item($line)
	{
		$items = $this->get_cart();
		unset($items[$line]);
		$this->set_cart($items);
	}

	public function copy_entire_sale($sale_id)
	{
		$this->empty_cart();
		$this->remove_customer();

		foreach($this->CI->Sale->get_sale_items_ordered($sale_id)->result() as $row)
		{
			$this->add_item($row->item_id, $row->quantity_purchased, $row->item_location, $row->discount_percent, PRICE_MODE_STANDARD, NULL, NULL, $row->item_unit_price, $row->description, $row->serialnumber, TRUE, $row->print_option);
		}

		foreach($this->CI->Sale->get_sale_payments($sale_id)->result() as $row)
		{
			$this->add_payment($row->payment_type, $row->payment_amount);
		}

		$this->set_customer($this->CI->Sale->get_customer($sale_id)->person_id);
		$this->set_employee($this->CI->Sale->get_employee($sale_id)->person_id);
		$this->set_quote_number($this->CI->Sale->get_quote_number($sale_id));
		$this->set_work_order_number($this->CI->Sale->get_work_order_number($sale_id));
		$this->set_sale_type($this->CI->Sale->get_sale_type($sale_id));
		$this->set_comment($this->CI->Sale->get_comment($sale_id));
		$this->set_dinner_table($this->CI->Sale->get_dinner_table($sale_id));
		$this->CI->session->set_userdata('sale_id', $sale_id);
	}

	public function get_sale_id()
	{
		return $this->CI->session->userdata('sale_id');
	}

	public function clear_all()
	{
		$this->CI->session->set_userdata('sale_id', -1);
		$this->empty_cart();
		$this->clear_comment();
		$this->empty_account_list();
		$this->empty_payments();
		$this->remove_customer();
		$this->clear_mode();
		$this->clear_inv_date();
		$this->remove_tab();
		$this->remove_due_collect();
		$this->remove_labour_cost();
		$this->remove_carrying_cost();
		$this->remove_other_cost();
		$this->remove_discount();
		$this->remove_supplier();
	}

	public function get_item_total($quantity, $price)
	{
		return bcmul($quantity, $price, 2);
	}

	public function get_subtotal($exclude_tax = FALSE)
	{
		$subtotal = 0;
		foreach($this->get_cart() as $item)
		{
			$subtotal = bcadd($subtotal, $this->get_item_total($item['quantity'], $item['price']), 2);
		}

		return $subtotal;
	}

	public function get_total()
	{
		$total = $this->get_subtotal();
		$total += $this->get_labour_cost();
		$total += $this->get_carrying_cost();
		$total += $this->get_other_cost();
		$total -= $this->get_discount();
		return $total;
	}

	public function get_empty_tables()
	{
		return $this->CI->Dinner_table->get_empty_tables();
	}

	public function cash_rounding($total, $type)
	{
		if($type == 'up')
		{
			$fig = pow(10,2);
			$rounded_total = (ceil($fig*$total) + ceil($fig*$total - ceil($fig*$total)))/$fig;
		}
		elseif($type == 'down')
		{
			$fig = pow(10,2);
			$rounded_total = (floor($fig*$total) + floor($fig*$total - floor($fig*$total)))/$fig;
		}
		else
		{
			$rounded_total = round ( $total, 2, $rounding_mode);
		}

		return $rounded_total;
	}

	public function get_cart()
	{
		if(!$this->CI->session->userdata('cart'))
		{
			$this->set_cart(array());
		}

		return $this->CI->session->userdata('cart');
	}

	public function set_cart($cart_data)
	{
		$this->CI->session->set_userdata('cart', $cart_data);
	}

	public function empty_cart()
	{
		$this->CI->session->unset_userdata('cart');
	}

	public function get_comment()
	{
		// avoid returning a NULL that results in a 0 in the comment if nothing is set/available
		$comment = $this->CI->session->userdata('sales_comment');

		return empty($comment) ? '' : $comment;
	}

	public function set_comment($comment)
	{
		$this->CI->session->set_userdata('sales_comment', $comment);
	}

	public function clear_comment()
	{
		$this->CI->session->unset_userdata('sales_comment');
	}

	// Multiple Payments
	public function get_payments()
	{
		if(!$this->CI->session->userdata('sales_payments'))
		{
			$this->set_payments(array());
		}

		return $this->CI->session->userdata('sales_payments');
	}

	// Multiple Payments
	public function set_payments($payments_data)
	{
		$this->CI->session->set_userdata('sales_payments', $payments_data);
	}

	// Multiple Payments
	public function add_payment($payment_id, $payment_amount)
	{
		$payments = $this->get_payments();
		if($payment_id == 'Return'){
			$payment = array($payment_id => array('payment_type' => $payment_id, 'payment_title'=> 'Return Amount', 'group'=>'Return', 'payment_amount' => $payment_amount));

			$payments += $payment;
		}
		elseif(isset($payments[$payment_id]))
		{
			//payment_method already exists, add to payment_amount
			$payments[$payment_id]['payment_amount'] = bcadd($payments[$payment_id]['payment_amount'], $payment_amount, 2);
		}
		else
		{
			//add to existing array
			$acc_details = $this->get_account_details($payment_id);
			$payment = array($payment_id => array('payment_type' => $payment_id, 'payment_title'=> $acc_details[0], 'group'=>$acc_details[1], 'payment_amount' => $payment_amount));

			$payments += $payment;
		}

		$this->set_payments($payments);
	}

	// Multiple Payments
	public function edit_payment($payment_id, $payment_amount)
	{
		$payments = $this->get_payments();
		if(isset($payments[$payment_id]))
		{
			$payments[$payment_id]['payment_type'] = $payment_id;
			$payments[$payment_id]['payment_amount'] = $payment_amount;
			$this->set_payments($payments);

			return TRUE;
		}

		return FALSE;
	}

	// Multiple Payments
	public function delete_payment($payment_id)
	{
		$payments = $this->get_payments();
		unset($payments[urldecode($payment_id)]);
		$this->set_payments($payments);
	}

	// Multiple Payments
	public function empty_payments()
	{
		$this->CI->session->unset_userdata('sales_payments');
	}
	
	public function add_temp_payments($str, $amt)
    {
        return array('payment_title' => $str, 'payment_type' => $str, 'group'=>$str, 'payment_amount' => $amt);
	}
	
	public function get_account_details($id)
	{
		$acc = $this->get_account_list();
		foreach($acc as $v){
			if($v->id == $id) return array($v->name, $v->type);
		}
		return array($id, $id);
	}
	
	public function get_account_list()
	{
		if(!$this->CI->session->userdata('account_lists'))
		{
			$this->set_account_list(array());
		}

		return $this->CI->session->userdata('account_lists');
	}

	public function set_account_list($account_lists)
	{
		$this->CI->session->set_userdata('account_lists', $account_lists);
	}

	// Multiple Payments
	public function empty_account_list()
	{
		$this->CI->session->unset_userdata('account_lists');
	}

	// Multiple Payments
	public function get_payments_total()
	{
		$subtotal = 0;
		foreach($this->get_payments() as $payments)
		{
			$subtotal = bcadd($payments['payment_amount'], $subtotal, 2);
		}

		return $subtotal;
	}

	public function get_supplier()
	{
		if(!$this->CI->session->userdata('supplier'))
		{
			$this->set_supplier(-1);
		}

		return $this->CI->session->userdata('supplier');
	}

	public function set_supplier($supplier_id)
	{
		$this->CI->session->set_userdata('supplier', $supplier_id);
	}

	public function remove_supplier()
	{
		$this->CI->session->unset_userdata('supplier');
	}
}
