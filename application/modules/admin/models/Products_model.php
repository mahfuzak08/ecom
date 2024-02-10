<?php

class Products_model extends CI_Model
{

    public function __construct()
    {
        parent::__construct();
    }

    public function deleteProduct($id)
    {
        // delete_files($path);
        $this->db->where('shop_id', SHOP_ID);
        $pinfo = $this->db->get_where('products', array('id'=>$id))->row_array();
        
        $this->db->trans_begin();
        $this->db->where('for_id', $id);
        if (!$this->db->delete('products_translations')) {
            log_message('error', print_r($this->db->error(), true));
        }

        $this->db->where('id', $id);
        $this->db->where('shop_id', SHOP_ID);
        if (!$this->db->delete('products')) {
            log_message('error', print_r($this->db->error(), true));
        }
        if ($this->db->trans_status() === FALSE) {
            $this->db->trans_rollback();
            show_error(lang('database_error'));
        } else {
            $this->db->trans_commit();
            if($pinfo['image'] != ''){
                $path = './attachments/'. SHOP_DIR .'/shop_images/'; 
                unlink('./attachments/'. SHOP_DIR .'/shop_images/'.$pinfo['image']);
                $path .= $pinfo['folder'];
                $this->deleteDir($path);
            }
        }
    }

    private function deleteDir($path) {
        if (empty($path)) { 
            return false;
        }
        array_map('unlink', glob("$path/*.*"));
        return rmdir($path);
    }

    public function productsCount($search_title = null, $category = null)
    {
        if ($search_title != null) {
            $search_title = trim($this->db->escape_like_str($search_title));
            $this->db->where("(products_translations.title LIKE '%$search_title%')");
        }
        if ($category != null) {
            $this->db->where('shop_categorie', $category);
        }
        $this->db->join('products_translations', 'products_translations.for_id = products.id', 'left');
        $this->db->where('products_translations.abbr', MY_DEFAULT_LANGUAGE_ABBR);
        $this->db->where('products.shop_id', SHOP_ID);
        return $this->db->count_all_results('products');
    }

    public function getProducts($limit, $page, $search_title = null, $orderby = null, $category = null, $vendor = null, $reorder_level=null)
    {
        if ($search_title != null) {
            $search_title = trim($this->db->escape_like_str($search_title));
            $this->db->where("(products_translations.title LIKE '%$search_title%')");
        }
        if ($orderby !== null) {
            $ord = explode('=', $orderby);
            if (isset($ord[0]) && isset($ord[1])) {
				$this->db->order_by('products.' . $ord[0], $ord[1]);
            }
        } else {
			$this->db->order_by('products.position', 'asc');
        }
        if ($category != null) {
            $this->db->where('shop_categorie', $category);
        }
		if ($reorder_level != null) {
            $this->db->where('quantity <= reorder_level');
        }
        if ($vendor != null) {
            $this->db->where('vendor_id', $vendor);
        }
        $this->db->join('vendors', 'vendors.id = products.vendor_id', 'left');
        $this->db->join('products_translations', 'products_translations.for_id = products.id', 'left');
        $this->db->where('products_translations.abbr', MY_DEFAULT_LANGUAGE_ABBR);
        $this->db->where('products.shop_id', SHOP_ID);
        $query = $this->db->select('vendors.name as vendor_name, vendors.id as vendor_id, products.*, products_translations.title, products_translations.description, products_translations.price, products_translations.buy_price, products_translations.old_price, products_translations.abbr, products.url, products_translations.for_id, products_translations.basic_description')->get('products', $limit, $page);
        return $query->result();
    }

    public function numShopProducts()
    {
        $this->db->where('shop_id', SHOP_ID);
        return $this->db->count_all_results('products');
    }

    public function getOneProduct($id)
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
    
    public function getProductPriceGivenBarcode($code)
    {
        $this->db->where('products.barcode', $code);
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
    
    public function getCurrentQty($id)
    {
        $this->db->where('shop_id', SHOP_ID);
        $this->db->where('products.id', $id);
        $query = $this->db->get('products');
        if ($query->num_rows() == 1) {
            $res = $query->row_array();
            return $res['quantity'];
        } else {
            return 0;
        }
    }

    public function productStatusChange($id, $to_status)
    {
        $this->db->where('shop_id', SHOP_ID);
        $this->db->where('id', $id);
        $result = $this->db->update('products', array('visibility' => $to_status));
        return $result;
    }

    public function setProduct($post, $id = 0)
    {
        if (!isset($post['barcode'])) {
            $post['barcode'] = null;
        }
		if (!isset($post['vendor_id'])) {
            $post['vendor_id'] = null;
        }
		if (!isset($post['brand_id'])) {
            $post['brand_id'] = null;
        }
        if (!isset($post['virtual_products'])) {
            $post['virtual_products'] = null;
        }
        $size = "N";
        if(isset($post['size'][0])){
            $size = "";
            for($n=0; $n<count($post['size']); $n++){
                if($n>0) $size .= ';';
                if($post['size'][$n] != '' && $post['qty'][$n] != '')
                    $size .= $post['size'][$n].'x'.$post['qty'][$n];
            }
        }
        $this->db->trans_begin();
        $is_update = false;
        if ($id > 0) {
            $is_update = true;
            if (!$this->db->where('id', $id)->update('products', array(
                        'barcode' => @$post['barcode'],
                        'image' => $post['image'] != null ? $_POST['image'] : $_POST['old_image'],
                        'shop_categorie' => $post['shop_categorie'],
                        'quantity' => $post['quantity'],
                        'reorder_level' => @$post['reorder_level'],
                        'size' => $size,
                        'in_slider' => $post['in_slider'],
                        'position' => $post['position'],
                        'virtual_products' => $post['virtual_products'],
                        'brand_id' => $post['brand_id'],
                        'vendor_id' => $post['vendor_id'],
                        'time_update' => time()
                    ))) {
                log_message('error', print_r($this->db->error(), true));
            }
            if($post['old_quantity'] !== $post['quantity']){
                if (!$this->db
                    ->where(array('item_id'=>$id, 'shop_id' => SHOP_ID, 'ref_id' => '0', 'ref_type' => 'in'))
                    ->update('product_trans', array('quantity' => $post['quantity'], 'description' => 'Update Initial Stock'.$post['old_quantity'].' to '.$post['quantity'], 'user_id' => $_SESSION['logged_user_id'])) ) 
                {
                    return false;
                    log_message('error', print_r($this->db->error(), true));
                }
            }
        } else {
            /*
             * Lets get what is default tranlsation number
             * in titles and convert it to url
             * We want our plaform public ulrs to be in default 
             * language that we use
             */
            $i = 0;
            foreach ($_POST['translations'] as $translation) {
                if ($translation == MY_DEFAULT_LANGUAGE_ABBR) {
                    $myTranslationNum = $i;
                }
                $i++;
            }
            if (!$this->db->insert('products', array(
                        'shop_id' => SHOP_ID,
                        'image' => $post['image'] === null ? "" : $post['image'],
                        'shop_categorie' => $post['shop_categorie'],
                        'quantity' => $post['quantity'],
                        'reorder_level' => @$post['reorder_level'],
                        'size' => $size,
                        'in_slider' => $post['in_slider'],
                        'position' => $post['position'],
                        'virtual_products' => $post['virtual_products'],
                        'folder' => $post['folder'],
                        'brand_id' => $post['brand_id'],
						'vendor_id' => $post['vendor_id'] > 0 ? $post['vendor_id'] : 0,
                        'time' => time()
                    ))) {
                log_message('error', print_r($this->db->error(), true));
            }
            $id = $this->db->insert_id();

            $this->setProductTrans('in', $id, $post['quantity'], $_SESSION['logged_user_id'], '', 1, '', 'Initial Stock');

            $this->db->where('id', $id);
            if (!$this->db->update('products', array(
						'barcode' => isset($post['barcode']) ? $post['barcode'] : $id,
                        'url' => except_letters($_POST['title'][$myTranslationNum]) . '_' . $id
                    ))) {
                log_message('error', print_r($this->db->error(), true));
            }
        }
        $this->setProductTranslation($post, $id, $is_update);
        if ($this->db->trans_status() === FALSE) {
            $this->db->trans_rollback();
            show_error(lang('database_error'));
        } else {
            $this->db->trans_commit();
        }
    }

    private function setProductTranslation($post, $id, $is_update)
    {
        $i = 0;
        $current_trans = $this->getTranslations($id);
        $post['price'] = str_replace(' ', '', $post['price']);
        $post['price'] = str_replace(',', '.', $post['price']);
        // $post['price'][$i] = preg_replace("/[^0-9০-৯]/", "", $post['price'][$i]);
        
        $post['buy_price'] = str_replace(' ', '', $post['buy_price']);
        $post['buy_price'] = str_replace(',', '.', $post['buy_price']);
        // $post['buy_price'][$i] = preg_replace("/[^0-9০-৯]/", "", $post['buy_price'][$i]);

        $post['old_price'] = str_replace(' ', '', $post['old_price']);
        $post['old_price'] = str_replace(',', '.', $post['old_price']);
        // $post['old_price'][$i] = preg_replace("/[^0-9০-৯]/", "", $post['old_price'][$i]);
        foreach ($post['translations'] as $abbr) {
            $arr = array();
            $emergency_insert = false;
            if (!isset($current_trans[$abbr])) {
                $emergency_insert = true;
            }
            $post['title'][$i] = str_replace('"', "'", $post['title'][$i]);
            
            $arr = array(
                'title' => $post['title'][$i],
                'basic_description' => $post['basic_description'][$i],
                'description' => $post['description'][$i],
                'price' => $post['price'],
                'old_price' => $post['old_price'],
                'buy_price' => $post['buy_price'],
                'abbr' => $abbr,
                'for_id' => $id
            );
            if ($is_update === true && $emergency_insert === false) {
                $abbr = $arr['abbr'];
                unset($arr['for_id'], $arr['abbr'], $arr['url']);
                if (!$this->db->where('abbr', $abbr)->where('for_id', $id)->update('products_translations', $arr)) {
                    log_message('error', print_r($this->db->error(), true));
                }
            } else {
                if (!$this->db->insert('products_translations', $arr)) {
                    log_message('error', print_r($this->db->error(), true));
                }
            }
            $i++;
        }
    }

    public function getTranslations($id)
    {
        $this->db->where('for_id', $id);
        $query = $this->db->get('products_translations');
        $arr = array();
        foreach ($query->result() as $row) {
            $arr[$row->abbr]['title'] = $row->title;
            $arr[$row->abbr]['basic_description'] = $row->basic_description;
            $arr[$row->abbr]['description'] = $row->description;
            $arr[$row->abbr]['price'] = $row->price;
            $arr[$row->abbr]['old_price'] = $row->old_price;
            $arr[$row->abbr]['buy_price'] = $row->buy_price;
        }
        return $arr;
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
    
    public function updateProduct($post, $id = 0)
    {
        $size = "N";
        if(isset($post['size'][0])){
            $size = "";
            for($n=0; $n<count($post['size']); $n++){
                if($n>0) $size .= ';';
                $qty = ((float)$post['qty'][$n] + (float)$post['oldqty'][$n]) > 0 ? ((float)$post['qty'][$n] + (float)$post['oldqty'][$n]) : "";
                if($post['size'][$n] != '' && $qty != ''){
                    $size .= $post['size'][$n].'x'. $qty;
                }
            }
        }
        $this->db->trans_begin();
        
        if ($id > 0) {
            if (!$this->db->where('id', $id)->update('products', array(
                        'quantity' => $post['quantity'] + $post['old_quantity'],
                        'size' => $size,
                        'time_update' => time()
                    ))) {
                log_message('error', print_r($this->db->error(), true));
            }
            if (!$this->db->insert("product_trans", array('item_id'=>$id, 'shop_id' => SHOP_ID, 'ref_type'=>'in', 'ref_id' => '0', 'quantity' => $post['quantity'], 'description' => 'Add into Stock', 'trans_date'=> $post['date'], 'user_id' => $_SESSION['logged_user_id']) ) ) 
            {
                return false;
                log_message('error', print_r($this->db->error(), true));
            }
            $this->setProductPrice($post, $id);
        } 
        if ($this->db->trans_status() === FALSE) {
            $this->db->trans_rollback();
            show_error(lang('database_error'));
        } else {
            $this->db->trans_commit();
        }
    }
    
    public function getLastSupplierName($item_id)
    {
        // SELECT * FROM product_trans WHERE product_trans.shop_id = 11 AND product_trans.item_id = 2277 AND product_trans.ref_type = 'in' AND product_trans.ref_id > 0 ORDER BY product_trans.id DESC
        $this->db->where(array('shop_id' => SHOP_ID, 'item_id' => $item_id, 'ref_type' => 'in', 'ref_id >' => 0));
        $this->db->order_by('id', 'desc');
        $last_parchase = $this->db->get('product_trans')->result();
        if(! empty($last_parchase[0]->ref_id)){
            // SELECT * FROM vendors JOIN purchase_orders ON vendors.id = purchase_orders.supplier_id WHERE purchase_orders.id = 699 AND purchase_orders.shop_id = 11
            // $last_parchase[0]->ref_id
            $this->db->select("v.*");
            $this->db->join('purchase_orders as p', 'v.id = p.supplier_id');
            $this->db->where(array('p.id' => $last_parchase[0]->ref_id, 'p.shop_id' => SHOP_ID));
            $sup_info = $this->db->get('vendors as v')->result();
            return !empty($sup_info[0]->id) ? $sup_info[0] : 0;
        }
        else return 0;
    }

    private function setProductPrice($post, $id)
    {
        $post['price'] = str_replace(' ', '', $post['price']);
        $post['price'] = str_replace(',', '.', $post['price']);
        
        $post['buy_price'] = str_replace(' ', '', $post['buy_price']);
        $post['buy_price'] = str_replace(',', '.', $post['buy_price']);
            
        $arr = array(
            'price' => $post['price'],
            'buy_price' => $post['buy_price'],
            'for_id' => $id
        );
        if (!$this->db->where('for_id', $id)->update('products_translations', $arr)) {
            log_message('error', print_r($this->db->error(), true));
        }
    }
    
}
