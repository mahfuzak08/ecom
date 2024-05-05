<?php

/*
 * @Author:    Md. Mahfuzur Rahman
 *  Gitgub:    https://github.com/mahfuzak08/
 */
if (!defined('BASEPATH')) {
    exit('No direct script access allowed');
}

class Manage extends ADMIN_Controller
{

    public function __construct()
    {
        parent::__construct();
        $this->load->model(array('Report_model', 'Languages_model', 'Accounts_model', 'Categories_model'));
    }

    public function index()
    {
        $this->login_check();
        $data = array();
        $head = array();
        $head['title'] = 'Administration - Report Lists';
        $head['description'] = 'Report Lists';
        $head['keywords'] = '';

        $data['getAllProducts'] = $this->Report_model->getAllProducts();
        $data['getAllCategory'] = $this->Categories_model->getShopCategories2();
        $data['getAllVendors'] = $this->Report_model->getAllVendors();
        $data['getAllcustomers'] = $this->Report_model->getAllcustomers();
        $data['getAllexpenses'] = $this->Report_model->getAllexpenses();
        $data['getAllAccount'] = $this->Accounts_model->getAccounts(0);
        // $this->Report_model->cus_bug_fix(21);
        
        $this->saveHistory('Go to Report Lists');
        $this->load->view('_parts/header', $head);
        $this->load->view('reports/lists', $data);
        $this->load->view('_parts/footer');
    }

    
    public function search()
    {
        $this->login_check();
        $data = array();
        $head = array();
        $head['title'] = 'Administration - Report Details';
        $head['keywords'] = '';

        if(isset($_POST["report-name"]) && isset($_POST["start_date"]) && $_POST["start_date"] != "" && isset($_POST["end_date"]) && $_POST["end_date"] != ""){
            $head['description'] = $_POST["report-name"] . ' Report';
            $head['report_info'] = $_POST["start_date"] . ' to '. $_POST["end_date"];
            $data['start_date'] = $_POST["start_date"];
            if($_POST["report-name"] == 'Inventory'){
                $head['title'] = ($_POST["stock_type"] == 0 ? 'Stock in and out ' : 'Balance with Amount ') . $_POST["report-name"] . ' Report';
				if($_POST["stock_type"] > 0){
					$_POST["start_date"] = '1970-01-01';
					$head['report_info'] = 'As of '. $_POST["end_date"];
				}
				// echo $_POST["start_date"];
                $data['show_running_balance'] = $_POST["product_id"] > 0 ? true : ( $_POST["stock_type"] > 0 ? true : false);
                $data['stock_type'] = $_POST["stock_type"];
                $data['details'] = $this->Report_model->getInventoryDetails($_POST);
            }
            elseif($_POST["report-name"] == 'Sales'){
                $data['show_running_balance'] = true;
                $data['details'] = $this->Report_model->getSaleDetails($_POST);
            }
            elseif($_POST["report-name"] == 'Customer'){
                $data['show_running_balance'] = $_POST['customer_id'] > 0 ? true : false;
                $data['details'] = $this->Report_model->getCustomerDetails($_POST);
            }
            elseif($_POST["report-name"] == 'Accounts'){
                $head['description'] .= " for " . $_POST["acc_name"];
                $data['show_running_balance'] = false;
                $_POST['id'] = $_POST['cash_acc_id'];
                $_POST['tranx_type'] = '';
                if($_POST['accounts_type'] == 'odc'){
                    $_POST['tranx_type'] = 'customer_payment';
                    $_POST['id'] = $_POST['cash_acc_id'];
                }else{
                    $_POST['id'] = $_POST['accounts_type'];
                }
                $_POST["start_date"] = empty($_POST["start_date"]) ? date('Y-m-d') : $_POST["start_date"];
                $_POST["end_date"] = empty($_POST["end_date"]) ? date('Y-m-d') : $_POST["end_date"];
                // print_r($_POST);
                // exit;
                $data['details'] = $this->Accounts_model->getAccountTrans($_POST['id'], 0, 'trans_date', 'asc', $_POST["start_date"], $_POST["end_date"], $_POST['tranx_type']);
            }
            elseif($_POST["report-name"] == 'Purchase'){
                $data['show_running_balance'] = true;
                $data['details'] = $this->Report_model->getPurchaseDetails($_POST);
            }
            elseif($_POST["report-name"] == 'Supplier'){
                $data['show_running_balance'] = $_POST['supplier_id'] > 0 ? true : false;
                $data['details'] = $this->Report_model->getSupplierDetails($_POST);
            }
            elseif($_POST["report-name"] == 'Expense'){
                $data['show_running_balance'] = $_POST['expense_id'] > 0 ? true : false;
                $data['details'] = $this->Report_model->getExpenseDetails($_POST);
            }
            elseif($_POST["report-name"] == 'Transection'){
                $data['show_details'] = $_POST['transection_type'] > 0 ? true : false;
                $data['details']['sales'] = $this->Report_model->getSaleDetails($_POST);
                $data['details']['purchase'] = $this->Report_model->getPurchaseDetails($_POST);
                $data['details']['expenses'] = $this->Report_model->getExpenseDetails($_POST);
            }
            elseif($_POST["report-name"] == 'Profit and Loss'){
                $head['description'] = $_POST["report-name"];
				$data["start_date"] = $_POST["start_date"];
				$data["end_date"] = $_POST["end_date"];
                // $data['details']['capitals'] = $this->Report_model->getCapitalSum($_POST);
                
                // $data['details']['accountsSum'] = $this->Report_model->getAccountsSum($_POST);
                $getSalesTotal = $this->Report_model->getSalesTotal($_POST);
                $data['details']['salesTotal'] = $getSalesTotal["sales_result"];
                $data['details']['salesTotal_buyinfo'] = $getSalesTotal["p_buy_prices"];
                $data['details']['accountsReceivable'] = $this->Report_model->getAccountsReceivable($_POST);
                $data['details']['salesDiscount'] = $this->Report_model->getSalesDiscount($_POST);
                $data['details']['purchaseTotal'] = $this->Report_model->getPurchaseTotal($_POST);
                $data['details']['stocks'] = $this->Report_model->getStocks($_POST);
                $data['details']['accountsPayable'] = $this->Report_model->getAccountsPayable($_POST);
                $data['details']['expenseSum'] = $this->Report_model->getExpenseSum($_POST);
                
                $data['details']['accountsSum'] = $this->Report_model->getAccountsSum2($_POST);
                // $data['details']['accountsReceivable'] = $this->Report_model->getAccountsReceivable($_POST);
                // $data['details']['purchaseTotal'] = $this->Report_model->getPurchaseTotal($_POST);
                // $data['details']['stocks'] = $this->Report_model->getStocks($_POST);
                // $data['details']['accountsPayable'] = $this->Report_model->getAccountsPayable($_POST);
                // $data['details']['expenseSum'] = $this->Report_model->getExpenseSum($_POST);
            }
            elseif($_POST["report-name"] == 'Sales Revenues'){
                $_POST["start_date"] = empty($_POST["start_date"]) ? date('Y-m-d') : $_POST["start_date"];
                $_POST["end_date"] = empty($_POST["end_date"]) ? date('Y-m-d') : $_POST["end_date"];
                $head['description'] = $_POST["report-name"];
				$data["start_date"] = $_POST["start_date"];
				$data["end_date"] = $_POST["end_date"];
				$data["category_id"] = $_POST["category_id"];
				$data["product_id"] = $_POST["product_id"];
                $data['getAllCategory'] = $this->Categories_model->getShopCategories2();
                $data['details'] = $this->Report_model->getSalesRevenues($_POST);
            }
            // elseif($_POST["report-name"] == 'Trial Balance'){
                // $head['description'] = $_POST["report-name"];
                // // $data['details']['capitals'] = $this->Report_model->getCapitalSum($_POST);
                // $data['details']['accountsSum'] = $this->Report_model->getAccountsSum($_POST);
                // $data['details']['salesTotal'] = $this->Report_model->getSalesTotal($_POST);
                // $data['details']['accountsReceivable'] = $this->Report_model->getAccountsReceivable($_POST);
                // $data['details']['purchaseTotal'] = $this->Report_model->getPurchaseTotal($_POST);
                // $data['details']['stocks'] = $this->Report_model->getStocks($_POST);
                // $data['details']['accountsPayable'] = $this->Report_model->getAccountsPayable($_POST);
                // $data['details']['expenseSum'] = $this->Report_model->getExpenseSum($_POST);
            // }

            $this->saveHistory('Go to '.$head['description']);
            $this->load->view('_parts/header', $head);
            // file_put_contents('a.txt', json_encode($data));
            $this->load->view('reports/details_'. str_replace(" ", "_", lcfirst($_POST["report-name"])), $data);
            $this->load->view('_parts/footer');
        }
        else{
            redirect('admin/reports');
        }
    }


}
