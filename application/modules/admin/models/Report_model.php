<?php

class Report_model extends CI_Model
{

    public function __construct()
    {
        parent::__construct();
    }
    
//     public function freeid($lptid){
//         $rr = $this->db->where("id",$lptid)->get("product_trans")->result_array();
//         if(count($rr) == 0) return true;
//         else return false;
//     }
	
// 	public function cus_bug_fix($sid){
// 	   // INSERT INTO `product_trans` (`id`, `shop_id`, `ref_id`, `ref_type`, `location`, `item_id`, `quantity`, `description`, `trans_date`, `created_at`, `updated_at`, `user_id`) VALUES 
// 	   // ('22397', '21', '0', 'in', '1', '3241', '32', 'Initial Stock', '2022-04-16', '2022-04-16 01:08:34', NULL, '33');
	    
//         $all_products = $this->db->where("shop_id", $sid)->get("products")->result_array();
//         $pidq = array();
//         $pid = array();
//         $ptid = array(22397);
//         $lptid = 22200;
//         for($i=0; $i<count($all_products); $i++){
//             $r = $this->db->where(array("shop_id"=>$sid, "item_id"=>$all_products[$i]["id"], "ref_id"=>0 ))->get("product_trans")->result_array();
//             if(count($r) == 0) {
//                 $pq = $all_products[$i]["quantity"];
                
//                 array_push($pid, $all_products[$i]["id"]);
//                 array_push($pidq, array($all_products[$i]["id"], $all_products[$i]["quantity"]));
                
//                 $spq = $this->db->where(array("shop_id"=>$sid, "ref_type"=>'out', "item_id"=>$all_products[$i]["id"]))->get("product_trans")->result_array();
                
//                 if(count($spq) > 0){
//                     for($j=0; $j<count($spq); $j++){
//                         $pq += $spq[$j]["quantity"];
//                     }
//                 }
                
//                 while(1){
//                     $tr = $this->freeid($lptid);
//                     if($tr === false) $lptid++;
//                     elseif($tr === true) break;
//                 }
                
//                 if($this->db->insert("product_trans", array("id"=>$lptid, "shop_id"=>$sid, "ref_id"=>0, "ref_type"=>"in", "location"=>"1", "item_id"=>$all_products[$i]["id"], "quantity"=>$pq, "description"=>"Initial Stock", "trans_date"=>"2022-04-16", "user_id"=>"33" ))) array_push($ptid, $lptid);
                
//                 $lptid++;
//             }
//         }
//         file_put_contents("$sid pid.txt", json_encode($pid));
//         file_put_contents("$sid blank trx point.txt", json_encode($ptid));
//     }
    
	public function getAllProducts($category = null, $vendor = null)
    {
        $this->db->where('visibility', 1);
        $this->db->where('products.shop_id', SHOP_ID);  
        if ($category != null) {
            $this->db->where('shop_categorie', $category);
        }
        if ($vendor != null) {
            $this->db->where('vendor_id', $vendor);
        }
        $this->db->order_by("title", "asc");
        $this->db->join('vendors', 'vendors.id = products.vendor_id', 'left');
        $this->db->join('products_translations', 'products_translations.for_id = products.id', 'left');
        $this->db->where('products_translations.abbr', MY_DEFAULT_LANGUAGE_ABBR);
        $query = $this->db->select('vendors.name as vendor_name, vendors.id as vendor_id, products.*, products_translations.title, products_translations.description, products_translations.price, products_translations.buy_price, products_translations.old_price, products_translations.abbr, products.url, products_translations.for_id, products_translations.basic_description')->get('products');
        return $query->result();
    }

    public function getAllVendors()
	{
		$this->db->order_by('vendors.name', 'asc');
        $this->db->where("is_delete<2");
        $this->db->where('shop_id', SHOP_ID);
		$result = $this->db->get('vendors');
		return $result->result();
    }
    
    public function getAllcustomers()
	{
        $this->db->order_by('name', 'asc');
        $this->db->where('shop_id', SHOP_ID);
		$this->db->where("phone_verify", "Yes");
		return $this->db->get("customer")->result();
    }
    
    public function getAllexpenses()
	{
        $this->db->where('shop_id', SHOP_ID);
		$this->db->order_by("title", "asc");
        return $this->db->get("expenses")->result();
    }

    public function getInventoryDetails($post)
    {
        $this->db->where("product_trans.trans_date >=", $post["start_date"]);
        $this->db->where("product_trans.trans_date <=", $post["end_date"]);
        
        if($post["product_id"]>0)
            $this->db->where("product_trans.item_id", $post["product_id"]);
        $this->db->join('products', 'products.id = product_trans.item_id', 'left');
        $this->db->join('products_translations', 'products_translations.for_id = products.id', 'left');
        $this->db->join('orders', 'product_trans.ref_id = orders.id', 'left');
        $this->db->where('products_translations.abbr', MY_DEFAULT_LANGUAGE_ABBR);
        $this->db->select('product_trans.*, orders.products as sales_price, products_translations.title, products_translations.price, products_translations.buy_price, products.image, products.url');
        $this->db->where('product_trans.shop_id', SHOP_ID);
		if($post["sho_as"] == 1)
			$this->db->order_by('products.id, product_trans.trans_date');
		elseif($post["sho_as"] == 2)
			$this->db->order_by('product_trans.trans_date', 'asc');
        return $this->db->get("product_trans")->result_array();
    }
    
    public function getSaleDetails($post)
    {
        $this->db->where("orders.date >=", $post["start_date"]);
        $this->db->where("orders.date <=", $post["end_date"]);
        $this->db->where("orders.processed", 1);
        // $this->db->where("product_trans.ref_type", 'out');
        
        if($post["product_id"]>0){
            $this->db->where("product_trans.item_id", $post["product_id"]);
        }
        if($post["customer_id"]>0){
            $this->db->where('orders.customer_id', $post["customer_id"]);
        }
        $this->db->join('product_trans', 'product_trans.ref_id = orders.id', 'left');
        $this->db->join('customer', 'customer.id = orders.customer_id', 'left');
        $this->db->select('orders.*, customer.name');
        $this->db->group_by("product_trans.ref_id");
        $this->db->where('orders.shop_id', SHOP_ID);
		$this->db->like("orders.order_type", "sale");
        return $this->db->get("orders")->result_array();
    }
    
    public function getCustomerDetails($post)
    {
        if($post["customer_id"]>0){
            $this->db->select('cus_sup_trans.*, orders.order_id, orders.order_type, orders.payment_type, orders.total, orders.asof_date_due');
            $this->db->join('orders', 'cus_sup_trans.trans_no = orders.id', 'left');
            
            $this->db->where("cus_sup_trans.trans_date >=", $post["start_date"]);
            $this->db->where("cus_sup_trans.trans_date <=", $post["end_date"]);
            $this->db->where("cus_sup_trans.type", 'customer');
            $this->db->where("cus_sup_trans.trans_for", $post["customer_id"]);
            $this->db->where('cus_sup_trans.shop_id', SHOP_ID);
            $this->db->order_by('cus_sup_trans.id', 'asc');
            return $this->db->get("cus_sup_trans")->result_array();
        }else{
            $this->db->where("orders.date >=", $post["start_date"]);
            $this->db->where("orders.date <=", $post["end_date"]);
            $this->db->where("orders.order_type", "sale");
            $this->db->where("orders.processed", 1);

            $this->db->select('customer.*, COUNT(orders.customer_id) as noorder, SUM(orders.total) as toamt');
            $this->db->join('orders', 'customer.id = orders.customer_id', 'left');
            $this->db->group_by('orders.customer_id');
            $this->db->order_by('noorder', 'desc');
            $this->db->where('customer.shop_id', SHOP_ID);
            return $this->db->get("customer")->result_array();
        }
    }
    
    public function getPurchaseDetails($post)
    {
        $this->db->where("purchase_orders.date >=", $post["start_date"]);
        $this->db->where("purchase_orders.date <=", $post["end_date"]);
        $this->db->where("purchase_orders.processed", 1);
        // $this->db->where("product_trans.ref_type", 'in');
        
        if($post["product_id"]>0){
            $this->db->where("product_trans.item_id", $post["product_id"]);
        }
        if($post["supplier_id"]>0){
            $this->db->where('purchase_orders.supplier_id', $post["supplier_id"]);
        }
        $this->db->join('product_trans', 'product_trans.ref_id = purchase_orders.id', 'left');
        $this->db->join('vendors', 'vendors.id = purchase_orders.supplier_id', 'left');
        $this->db->select('purchase_orders.*, vendors.name');
        $this->db->group_by("product_trans.ref_id");
        $this->db->where('purchase_orders.shop_id', SHOP_ID);
        $this->db->like("purchase_orders.order_type", "purchase");
        return $this->db->get("purchase_orders")->result_array();
    }

    public function getSupplierDetails($post)
    {
        if($post["supplier_id"]>0){
            $this->db->select('cus_sup_trans.*, purchase_orders.order_id, purchase_orders.order_type, purchase_orders.payment_type, purchase_orders.total, purchase_orders.asof_date_due');
            $this->db->join('purchase_orders', 'cus_sup_trans.trans_no = purchase_orders.id', 'left');
            
            $this->db->where("cus_sup_trans.trans_date >=", $post["start_date"]);
            $this->db->where("cus_sup_trans.trans_date <=", $post["end_date"]);
            $this->db->where("cus_sup_trans.type", 'supplier');
            $this->db->where("cus_sup_trans.trans_for", $post["supplier_id"]);
            $this->db->where('cus_sup_trans.shop_id', SHOP_ID);
            $this->db->order_by('cus_sup_trans.id', 'asc');
            return $this->db->get("cus_sup_trans")->result_array();
        }else{
            $this->db->where("purchase_orders.date >=", $post["start_date"]);
            $this->db->where("purchase_orders.date <=", $post["end_date"]);
            $this->db->where("purchase_orders.order_type", "purchase");
            $this->db->where("purchase_orders.processed", 1);

            $this->db->select('vendors.*, COUNT(purchase_orders.supplier_id) as noorder, SUM(purchase_orders.total) as toamt');
            $this->db->join('purchase_orders', 'vendors.id = purchase_orders.supplier_id', 'left');
            $this->db->group_by('purchase_orders.supplier_id');
            $this->db->order_by('noorder', 'desc');
            $this->db->where('vendors.shop_id', SHOP_ID);
            return $this->db->get("vendors")->result_array();
        }
    }
    
    public function getExpenseDetails($post)
    {
        $this->db->select("expense_trans.*, expenses.title as group");
        $this->db->where("expense_trans.date >=", $post["start_date"]);
        $this->db->where("expense_trans.date <=", $post["end_date"]);
        
        $this->db->join('expenses', 'expenses.id = expense_trans.expense_id', 'left');

        if($post["expense_id"]>0){
            $this->db->where("expense_trans.expense_id", $post["expense_id"]);
        }
        $this->db->where('expense_trans.shop_id', SHOP_ID);
        $this->db->order_by('expense_trans.id', 'asc');
        return $this->db->get("expense_trans")->result_array();
    }
    
    public function getCapitalSum($post)
    {
        $this->db->select("SUM(amount) as amount");
        $this->db->where("account_trans.type", 'Direct');
        $this->db->where("account_trans.details", 'Opening Balance');
        $this->db->or_where("account_trans.details", 'Invest');
        $this->db->where('account_trans.shop_id', SHOP_ID);
        return $this->db->get("account_trans")->row("amount");
    }
    
    public function getAccountsSum($post)
    {
		// SELECT accounts.name, SUM(amount) AS amount 
		// FROM account_trans 
		// JOIN accounts ON accounts.id = account_trans.bank_act 
		// WHERE account_trans.trans_date <= '2021-03-20' 
		// 		AND account_trans.shop_id = 1 
		// GROUP BY account_trans.bank_act;
        $this->db->select("accounts.name, SUM(amount) as amount");
        $this->db->where("account_trans.trans_date <=", $post["end_date"]);
        
        $this->db->join('accounts', 'accounts.id = account_trans.bank_act');
        $this->db->group_by('account_trans.bank_act');
        $this->db->where('account_trans.shop_id', SHOP_ID);
        return $this->db->get("account_trans")->result_array();
    }

    public function getSalesTotal($post)
    {
        $this->db->where("orders.date >=", $post["start_date"]);
        $this->db->where("orders.date <=", $post["end_date"]);
        $this->db->where("orders.order_type", "sale");
        $this->db->where("orders.processed", 1);
        $this->db->where('shop_id', SHOP_ID);
        // $this->db->select('*, SUM(total) as total');
        $result = array();
        $result['sales_result'] = $this->db->get("orders")->result_array();
        $result['p_buy_prices'] = array();
        $pids = array();
        for($i=0; $i<count($result['sales_result']); $i++){
			foreach(unserialize($result['sales_result'][$i]['products']) as $line=>$item) {
			    if(array_search($item['product_info']['id'], $pids) === false)
			        $pids[] = $item['product_info']['id'];
			}
		}
// 		SELECT products_translations.buy_price, products_translations.for_id as pid FROM products_translations WHERE products_translations.for_id IN (4008, 4024, 4320)
        if(count($pids)>0){
            $this->db->select("buy_price, for_id as pid");
            $this->db->where_in("for_id", $pids);
            $result['p_buy_prices'] = $this->db->get('products_translations')->result_array();
        }
        return $result;
    }

    public function getSalesRevenues($post)
    {
        // $this->db->select('products.shop_categorie, products_translations.*');
        // $this->db->join('products_translations', 'products.id = products_translations.for_id');
        // if($post["category_id"]>0)
        //     $this->db->where("products.shop_categorie", $post["category_id"]);
        // elseif($post["product_id"]>0)
        //     $this->db->where("products.id", $post["product_id"]);
        // $this->db->where('products_translations.abbr', MY_DEFAULT_LANGUAGE_ABBR);
        // $this->db->where('products.shop_id', SHOP_ID);
        // $result['products'] = $this->db->get('products')->result_array();

        $this->db->where("orders.date >=", $post["start_date"]);
        $this->db->where("orders.date <=", $post["end_date"]);
        $this->db->where("orders.order_type", "sale");
        $this->db->where("orders.processed", 1);
        $this->db->where('shop_id', SHOP_ID);
        $result = array();
        $result['sales_result'] = $this->db->get("orders")->result_array();
        
        
        
        $result['p_buy_prices'] = array();
        $pids = array();
        $catids = array();
        for($i=0; $i<count($result['sales_result']); $i++){
			foreach(unserialize($result['sales_result'][$i]['products']) as $line=>$item) {
			    if(array_search($item['product_info']['id'], $pids) === false){
                    if($post["category_id"]>0 && $item['product_info']['shop_categorie'] == $post["category_id"])
			            $pids[] = $item['product_info']['id'];
                    elseif($post["product_id"]>0 && $item['product_info']['id'] == $post["product_id"])
			            $pids[] = $item['product_info']['id'];
                    else{
                        $pids[] = $item['product_info']['id'];
                        if(array_search($item['product_info']['shop_categorie'], $catids) === false)
                            $catids[] = $item['product_info']['shop_categorie'];
                    }
                }
			}
		}
        // SELECT products_translations.buy_price, products_translations.for_id as pid FROM products_translations WHERE products_translations.for_id IN (4008, 4024, 4320)
        if(count($pids)>0){
            $this->db->where('abbr', MY_DEFAULT_LANGUAGE_ABBR);
            $this->db->where_in("for_id", $pids);
            $result['p_buy_prices'] = $this->db->get('products_translations')->result_array();
        }
        return $result;
    }
    
    public function getAccountsReceivable($post)
    {
        $this->db->where('shop_id', SHOP_ID);
        $this->db->select("SUM(balance) as amount");
        return $this->db->get("customer")->row("amount");
    }
        
    public function getStocks($post)
    {
        // $this->db->select("item_id, SUM(CASE WHEN ref_type = 'in' THEN quantity ELSE -quantity END) as qty, products_translations.buy_price");
        // $this->db->join("products_translations", "products_translations.for_id = product_trans.item_id");
        // $this->db->where("products_translations.abbr", MY_DEFAULT_LANGUAGE_ABBR);
        // $this->db->group_by("item_id");
        // $this->db->where('shop_id', SHOP_ID);
        // $result = $this->db->get("product_trans")->result_array();
        
        $total = 0;
        // foreach($result as $row){
        //     $total += (float) $row['qty'] * (float) $row['buy_price'];
        // }
        
        $this->db->select("SUM(p.quantity * pt.buy_price) as total");
        $this->db->join("products_translations as pt", "pt.for_id = p.id");
        $this->db->where("pt.abbr", MY_DEFAULT_LANGUAGE_ABBR);
        $this->db->where('p.shop_id', SHOP_ID);
        $total = $this->db->get("products as p")->row('total');
        
        return $total;
    }

    public function getPurchaseTotal($post)
    {
        $this->db->where("purchase_orders.date >=", $post["start_date"]);
        $this->db->where("purchase_orders.date <=", $post["end_date"]);
        $this->db->where("purchase_orders.order_type", "purchase");
        $this->db->where("purchase_orders.processed", 1);
        $this->db->where('shop_id', SHOP_ID);
        $this->db->select('SUM(total) as total');
        $total = $this->db->get("purchase_orders")->row("total");
        if($total <= 0){
            // SELECT `product_trans`.*, `products_translations`.buy_price, (`products_translations`.buy_price * product_trans.quantity) as pp FROM `product_trans` JOIN products_translations ON product_trans.item_id = products_translations.for_id AND products_translations.abbr = 'en' WHERE `shop_id` = 22 AND `ref_type` LIKE 'in' AND `trans_date` = '2023-02-26'
            $this->db->select('product_trans`.*, `products_translations`.buy_price, SUM(`products_translations`.buy_price * product_trans.quantity) as total');
            $this->db->join("products_translations", "products_translations.for_id = product_trans.item_id");
            $this->db->where("product_trans.trans_date >=", $post["start_date"]);
            $this->db->where("product_trans.trans_date <=", $post["end_date"]);
            $this->db->where("product_trans.ref_type", "in");
            $this->db->where("products_translations.abbr", MY_DEFAULT_LANGUAGE_ABBR);
            $this->db->where('product_trans.shop_id', SHOP_ID);
            $total = $this->db->get("product_trans")->row("total");
        }
        return $total;
    }
    
    public function getAccountsPayable($post)
    {
        $this->db->where('shop_id', SHOP_ID);
        $this->db->where('is_delete', 0);
        $this->db->select("SUM(balance) as amount");
        return $this->db->get("vendors")->row("amount");
    }

    public function getExpenseSum($post)
    {
        $this->db->select("expenses.title, SUM(amount) as amount");
        $this->db->where("expense_trans.date >=", $post["start_date"]);
        $this->db->where("expense_trans.date <=", $post["end_date"]);
        
        $this->db->join('expenses', 'expenses.id = expense_trans.expense_id');
        $this->db->group_by('expense_trans.expense_id');
        $this->db->where('expense_trans.shop_id', SHOP_ID);
        return $this->db->get("expense_trans")->result_array();
    }
    
    
    
    
    
    
    
    
    
    
    
    
    
    public function getAccountsSum2($post)
    {
        $this->db->select("accounts.name, SUM(amount) as amount");
        $this->db->where("account_trans.trans_date >=", $post["start_date"]);
        $this->db->where("account_trans.trans_date <=", $post["end_date"]);
        
        $this->db->join('accounts', 'accounts.id = account_trans.bank_act');
        $this->db->group_by('account_trans.bank_act');
        $this->db->where('account_trans.shop_id', SHOP_ID);
        return $this->db->get("account_trans")->result_array();
    }
    
    public function getSalesDiscount($post)
    {
        $this->db->select("SUM(orders.discount_code) as discount");
        $this->db->where("orders.date >=", $post["start_date"]);
        $this->db->where("orders.date <=", $post["end_date"]);
        $this->db->where('orders.shop_id', SHOP_ID);
        return $this->db->get("orders")->result_array();
    }
    
}
