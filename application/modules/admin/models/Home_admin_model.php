<?php

class Home_admin_model extends CI_Model
{

    private $shop_id; 
    public function __construct()
    {
        parent::__construct();
        $this->shop_id = SHOP_ID;
    }

    public function loginCheck($values)
    {
        $username = $values['username'];
        $pass = md5($values['password']);

        $str = "SELECT * FROM users WHERE `username` = '". $username ."' AND `password` = '". $pass ."' AND IF(`user_type` = 1, `shop_id` = '". SHOP_ID ."', `shop_id` > 0)";

        $result = $this->db->query($str);

        // $arr = array(
        //     'username' => $values['username'],
        //     'password' => md5($values['password']),
        // );
        // $this->db->where($arr);
        // $result = $this->db->get('users');
        $res = $result->row_array();
        if ($result->num_rows() > 0) {
			if($res['login_attempts_failed'] > 2){
				$end_block_time = 0;
				switch($res['login_attempts_failed']){
					case 3: $end_block_time = 1800; setcookie("display_ft", "30 minutes."); break; // 30min
					case 4: $end_block_time = 3600; setcookie("display_ft", "60 minutes."); break; // 60min
					case 5: $end_block_time = 7200; setcookie("display_ft", "2 hours."); break; // 120min
					case 6: $end_block_time = 86400; setcookie("display_ft", "1 day."); break; // 1 day
					default: $end_block_time = 1314000; setcookie("display_ft", "temporary disabled unblock."); break; // temporary disabled for 1 year
				}
				
				if(time() < $res['failed_time'] + $end_block_time){
					setcookie("user_ip_fa", $res['login_attempts_failed']);
					return array();
				}
			}
			
            $this->db->where('id', $res['id']);
            if($res['user_type'] == 1 && $res['shop_id'] == SHOP_ID){
                $this->db->update('users', array('login_attempts_failed' => 0, 'failed_time' => null, 'last_login' => time()));
            }
            elseif($res['user_type'] == 2){
                $this->shop_id = SHOP_ID;
                $this->db->update('users', array('login_attempts_failed' => 0, 'failed_time' => null, 'shop_id' => SHOP_ID, 'last_login' => time()));
            }
            else{
                return array();
            }
        }
		
        return $res;
    }
	
	public function login_attempts_failed($username)
    {
        $str = "SELECT * FROM users WHERE `username` = '". $username ."' AND `shop_id` = '". SHOP_ID ."'";
        $result = $this->db->query($str);
        $res = $result->row_array();
        if ($result->num_rows() > 0) {
			$s = "UPDATE users SET login_attempts_failed = (login_attempts_failed+1), failed_time = ".time()." WHERE id = ".$res['id'];
			$this->db->query($s);
			return true;
        }
		return false;
    }

    /*
     * Some statistics methods for home page of
     * administration
     * START
     */

    public function countLowQuantityProducts()
    {
        $this->db->where('shop_id', $this->shop_id);
        $this->db->where('quantity <= reorder_level');
        return $this->db->count_all_results('products');
    }

    public function lastSubscribedEmailsCount()
    {
        $yesterday = strtotime('-1 day', time());
        $this->db->where('time > ', $yesterday);
        $this->db->where('shop_id', $this->shop_id);
        return $this->db->count_all_results('subscribed');
    }

    public function getMostSoldProducts($limit = 10)
    {
        $this->db->select('url, procurement');
        $this->db->order_by('procurement', 'desc');
        $this->db->where('procurement >', 0);
        $this->db->where('shop_id', $this->shop_id);
        $this->db->limit($limit);
        $queryResult = $this->db->get('products');
        return $queryResult->result_array();
    }

    public function getReferralOrders()
    {

        $this->db->select('count(id) as num, clean_referrer as referrer');
        $this->db->where('shop_id', $this->shop_id);
        $this->db->group_by('clean_referrer');
        $queryResult = $this->db->get('orders');
        return $queryResult->result_array();
    }

    public function getOrdersByPaymentType($limit = 10)
    {
        $this->db->select('count(id) as num, payment_type');
        $this->db->where('shop_id', $this->shop_id);
        $this->db->group_by('payment_type');
        $this->db->limit($limit);
        $queryResult = $this->db->get('orders');
        return $queryResult->result_array();
    }

    public function getOrdersByMonth()
    {
        $result = $this->db->query("SELECT YEAR(FROM_UNIXTIME(date)) as year, MONTH(FROM_UNIXTIME(date)) as month, COUNT(id) as num FROM orders WHERE shop_id = '". $this->shop_id ."' GROUP BY YEAR(FROM_UNIXTIME(date)), MONTH(FROM_UNIXTIME(date)) ASC");
        $result = $result->result_array();
        $orders = array();
        $years = array();
        foreach ($result as $res) {
            if (!isset($orders[$res['year']])) {
                for ($i = 1; $i <= 12; $i++) {
                    $orders[$res['year']][$i] = 0;
                }
            }
            $years[] = $res['year'];
            $orders[$res['year']][$res['month']] = $res['num'];
        }
        return array(
            'years' => array_unique($years),
            'orders' => $orders
        );
    }

    /*
     * Some statistics methods for home page of
     * administration
     * END
     */

    public function setValueStore($key, $value)
    {
        $this->db->where('thekey', $key);
        $this->db->where('shop_id', $this->shop_id);
        $query = $this->db->get('value_store');
        if ($query->num_rows() > 0) {
            $this->db->where('shop_id', $this->shop_id);
            $this->db->where('thekey', $key);
            if (!$this->db->update('value_store', array('value' => $value))) {
                log_message('error', print_r($this->db->error(), true));
                show_error(lang('database_error'));
            }
        } else {
            if (!$this->db->insert('value_store', array('shop_id' => $this->shop_id, 'value' => $value, 'thekey' => $key))) {
                log_message('error', print_r($this->db->error(), true));
                show_error(lang('database_error'));
            }
        }
    }

    public function changePass($new_pass, $username)
    {
        $this->db->where('username', $username);
        $this->db->where('shop_id', $this->shop_id);
        $result = $this->db->update('users', array('password' => md5($new_pass)));
        return $result;
    }

    public function getValueStore($key)
    {
        $query = $this->db->query("SELECT value FROM value_store WHERE shop_id = '". $this->shop_id ."' AND  thekey = '$key'");
        $row = $query->row_array();
        return is_array($row) ? $row['value'] : '';
    }

    public function newOrdersCheck()
    {
        $result = $this->db->query("SELECT count(id) as num FROM `orders` WHERE shop_id = '". $this->shop_id ."' AND viewed = 0");
        $row = $result->row_array();
        return $row['num'];
    }

    public function setCookieLaw($post)
    {
        $query = $this->db->query('SELECT id FROM cookie_law WHERE shop_id = '. $this->shop_id);
        if ($query->num_rows() == 0) {
            $update = false;
        } else {
            $result = $query->row_array();
            $update = $result['id'];
        }

        if ($update === false) {
            $this->db->trans_begin();
            if (!$this->db->insert('cookie_law', array(
                        'shop_id' => $this->shop_id,
                        'link' => $post['link'],
                        'theme' => $post['theme'],
                        'visibility' => $post['visibility']
                    ))) {
                log_message('error', print_r($this->db->error(), true));
            }
            $for_id = $this->db->insert_id();
            $i = 0;
            foreach ($post['translations'] as $translate) {
                if (!$this->db->insert('cookie_law_translations', array(
                            'message' => htmlspecialchars($post['message'][$i]),
                            'button_text' => htmlspecialchars($post['button_text'][$i]),
                            'learn_more' => htmlspecialchars($post['learn_more'][$i]),
                            'abbr' => $translate,
                            'for_id' => $for_id
                        ))) {
                    log_message('error', print_r($this->db->error(), true));
                }
                $i++;
            }
            if ($this->db->trans_status() === FALSE) {
                $this->db->trans_rollback();
                show_error(lang('database_error'));
            } else {
                $this->db->trans_commit();
            }
        } else {
            $this->db->trans_begin();
            $this->db->where('id', $update);
            $this->db->where('shop_id', $this->shop_id);
            if (!$this->db->update('cookie_law', array(
                        'link' => $post['link'],
                        'theme' => $post['theme'],
                        'visibility' => $post['visibility']
                    ))) {
                log_message('error', print_r($this->db->error(), true));
            }
            $i = 0;
            foreach ($post['translations'] as $translate) {
                $this->db->where('for_id', $update);
                $this->db->where('abbr', $translate);
                if (!$this->db->update('cookie_law_translations', array(
                            'message' => htmlspecialchars($post['message'][$i]),
                            'button_text' => htmlspecialchars($post['button_text'][$i]),
                            'learn_more' => htmlspecialchars($post['learn_more'][$i])
                        ))) {
                    log_message('error', print_r($this->db->error(), true));
                }
                $i++;
            }
            if ($this->db->trans_status() === FALSE) {
                $this->db->trans_rollback();
                show_error(lang('database_error'));
            } else {
                $this->db->trans_commit();
            }
        }
    }

    public function getCookieLaw()
    {
        $arr = array('cookieInfo' => null, 'cookieTranslate' => null);
        $query = $this->db->query('SELECT * FROM cookie_law WHERE shop_id = '. $this->shop_id);
        if ($query->num_rows() > 0) {
            $arr['cookieInfo'] = $query->row_array();
            $query = $this->db->query('SELECT * FROM cookie_law_translations');
            $arrTrans = $query->result_array();
            foreach ($arrTrans as $trans) {
                $arr['cookieTranslate'][$trans['abbr']] = array(
                    'message' => $trans['message'],
                    'button_text' => $trans['button_text'],
                    'learn_more' => $trans['learn_more']
                );
            }
        }
        return $arr;
    }
    
    public function get_user_access($user = null)
    {
        // $this->db->where('shop_id', $this->shop_id);
        if ($user != null && is_numeric($user)) {
            $this->db->where('id', $user);
        } else if ($user != null && is_string($user)) {
            $this->db->where('username', $user);
        } else {
            return false;
        }
        $query = $this->db->select('access')->get('users');
        return $query->result_array();
    }

}
