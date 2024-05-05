<link href="<?= base_url('assets/select2/select2.min.css'); ?>" rel="stylesheet" />
<link href="<?= base_url('assets/css/bootstrap-datepicker.min.css') ?>" rel="stylesheet">
<div id="products">
    <?php
    if ($this->session->flashdata('result_delete')) {
        ?>
        <hr>
        <div class="alert alert-success"><?= $this->session->flashdata('result_delete') ?></div>
        <hr>
        <?php
    }
    if ($this->session->flashdata('result_publish')) {
        ?>
        <hr>
        <div class="alert alert-success"><?= $this->session->flashdata('result_publish') ?></div>
        <hr>
        <?php
    } 
    ?>
    <h1><i class="fa fa-bar-chart" aria-hidden="true"></i> <?= $description; ?></h1>
    <hr>
    <div class="row">
        <div class="col-xs-12 col-md-4">
            <ul class="report-menu">
                <li class="selected">Inventory</li>
                <li>Sales</li>
                <li>Sales Revenue</li>
                <li>Customer</li>
                <li>Accounts</li>
                <li>Purchase</li>
                <li>Supplier</li>
                <li>Expense</li>
                <li>Transection</li>
                <!--<li>Trial Balance</li>-->
                <li>Profit and Loss</li>
            </ul>
		</div>
        <div class="col-xs-12 col-md-8" id="report-criteria">
            <form method="POST" action="<?= site_url("admin/reports/search"); ?>">
                <div class="box box-info">
                    <div class="box-header with-border">
                        <h3 class="box-title" id="report-title">Inventory Report</h3>
                    </div>
                    <div class="box-body">
                        <div class="col-xs-12 col-md-6 stock critarea">
                            <div class="form-group">
                                <label>Select Type</label>
                                <select id="stock_type" name="stock_type" class="form-control">
                                    <option value="0">Stock in and out</option>
                                    <!--<option value="1">Stock Balance</option>-->
                                    <option value="2">Balance with Amount</option>
                                </select>
                            </div>
                        </div>
                        <div class="col-xs-12 col-md-6 products critarea">
                            <div class="form-group">
                                <label>Select Product</label>
                                <select id="product-list" name="product_id" class="form-control">
                                    <option value="0">All</option>
                                    <?php foreach($getAllProducts as $row){?>
                                        <option value="<?= $row->id; ?>"><?= $row->title; ?></option>
                                    <?php } ?>
                                </select>
                            </div>
                        </div>
                        <div class="col-xs-12 col-md-6 category critarea" style="display: none;">
                            <div class="form-group">
                                <label>Select Product Category</label>
                                <select id="category-list" name="category_id" class="form-control">
                                    <option value="0">All</option>
                                    <?php foreach($getAllCategory as $row){?>
                                        <option value="<?= $row['id']; ?>"><?= $row['name']; ?></option>
                                    <?php } ?>
                                </select>
                            </div>
                        </div>
                        <div class="col-xs-12 col-md-6 customer critarea" style="display: none;">
                            <div class="form-group">
                                <label>Select Customer</label>
                                <select name="customer_id" class="form-control">
                                    <option value="0">All</option>
                                    <?php foreach($getAllcustomers as $row){?>
                                        <option value="<?= $row->id; ?>"><?= $row->name; ?></option>
                                    <?php } ?>
                                </select>
                            </div>
                        </div>
                        <div class="col-xs-12 col-md-6 supplier critarea" style="display: none;">
                            <div class="form-group">
                                <label>Select Supplier</label>
                                <select id="supplier-list" name="supplier_id" class="form-control">
                                    <option value="0">All</option>
                                    <?php foreach($getAllVendors as $row){?>
                                        <option value="<?= $row->id; ?>"><?= $row->name; ?></option>
                                    <?php } ?>
                                </select>
                            </div>
                        </div>
                        <div class="col-xs-12 col-md-6 expense critarea" style="display: none;">
                            <div class="form-group">
                                <label>Select Expense Category</label>
                                <select id="expense-list" name="expense_id" class="form-control">
                                    <option value="0">All</option>
                                    <?php foreach($getAllexpenses as $row){?>
                                        <option value="<?= $row->id; ?>"><?= $row->title; ?></option>
                                    <?php } ?>
                                </select>
                            </div>
                        </div>
                        <div class="col-xs-12 col-md-6 transection critarea" style="display: none;">
                            <div class="form-group">
                                <label>Select Type</label>
                                <select id="transection-type" name="transection_type" class="form-control">
                                    <option value="0">Summary</option>
                                    <option value="1">Details</option>
                                </select>
                            </div>
                        </div>
                        <div class="col-xs-12 col-md-6 start_date critarea">
                            <label>Start Date</label>
                            <div class="input-group">
                                <input type="text" name="start_date" value="<?= date("Y-m-d"); ?>" required class="form-control datepicker">
                                <span class="input-group-addon"><i class="fa fa-calendar"></i></span>
                            </div>
                        </div>
                        <div class="col-xs-12 col-md-6 end_date critarea">
                            <label>End Date</label>
                            <div class="input-group">
                                <input type="text" name="end_date" value="<?= date("Y-m-d"); ?>" required class="form-control datepicker">
                                <span class="input-group-addon"><i class="fa fa-calendar"></i></span>
                            </div>
                        </div>
                        <div class="col-xs-12 col-md-6 accounts critarea" style="display: none;">
                            <div class="form-group">
                                <label>Select Type</label>
                                <select id="accounts-type" name="accounts_type" class="form-control">
                                    <option>Select account type</option>
                                    <?php for($i=0; $i<count($getAllAccount); $i++){?>
                                    <option value="<?= $getAllAccount[$i]->id; ?>"><?= $getAllAccount[$i]->name; ?></option>
                                    <?php } ?>
                                    <option value="odc">Old Due Collection</option>
                                </select>
                                <?php for($i=0; $i<count($getAllAccount); $i++){
                                    if($getAllAccount[$i]->name == 'Cash' && $getAllAccount[$i]->type == 'Cash'){ ?>
                                    <input type='hidden' name='cash_acc_id' value="<?= $getAllAccount[$i]->id; ?>">
                                <?php } 
                                } ?>
                                <input type='hidden' id='acc_name' name='acc_name'>
                            </div>
                        </div>
						<div class="col-xs-12 col-md-6 sho_as critarea">
                            <label>Report Show As</label>
                            <select id="sho_as" name="sho_as" class="form-control">
                                <option value="1">Product Groupwise</option>
                                <option value="2">Datewise</option>
                            </select>
                        </div>
                    </div>
                    <div class="box-footer">
                        <input type="hidden" name="report-name" id="report-name" value="Inventory">
                        <button class="btn btn-success btn-flat pull-right">Submit</button>
                    </div>
                </div>
            </form>
        </div>
    </div>
    <script src="<?= base_url('assets/select2/select2.min.js'); ?>"></script>
    <script src="<?= base_url('assets/js/bootstrap-datepicker.min.js') ?>"></script>
    <script>
        $('.datepicker').datepicker({
            format: "yyyy-mm-dd",
            todayHighlight: true,
            autoclose: true,
            endDate: new Date()
        });

        $('select').select2();

        // calander icon open the datepicker
        $(".input-group-addon").click(function(){
            $(this).closest(".input-group").find("input").focus();
        });

        $("#accounts-type").change(function(){
			if($(this).val())
				$("#acc_name").val($('#accounts-type').find(":selected").text());
		});
		// when click on report name
        $(".report-menu li").click(function(){
            $(".report-menu li").removeClass("selected");
            $(this).addClass("selected");
            var report_name = $(this).text();
            $("#report-name").val(report_name);
            $("#report-title").text(report_name + " Report");
            $(".critarea").hide();
            if(report_name == "Inventory"){
                $(".start_date, .end_date").show();
                $(".stock").show();
                $(".products").show();
                $(".sho_as").show();
            }
            else if(report_name == "Sales"){
				$(".start_date, .end_date").show();
                $(".products").show();
                $(".customer").show();
            }
            else if(report_name == "Customer"){
				$(".start_date, .end_date").show();
                $(".customer").show();
            }
            else if(report_name == "Accounts"){
				$(".start_date, .end_date, .accounts").show();
            }
            else if(report_name == "Purchase"){
				$(".start_date, .end_date").show();
                $(".products").show();
                $(".supplier").show();
            }
            else if(report_name == "Supplier"){
				$(".start_date, .end_date").show();
                $(".supplier").show();
            }
            else if(report_name == "Expense"){
				$(".start_date, .end_date").show();
                $(".expense").show();
            }
            else if(report_name == "Transection"){
				$(".start_date, .end_date").show();
                $(".transection").show();
            }
            // else if(report_name == "Trial Balance"){
				// $(".start_date, .end_date").show();
            // }
            else if(report_name == "Profit and Loss"){
                $(".start_date, .end_date").show();
            }
            else if(report_name == "Sales Revenue"){
                $(".start_date, .end_date").show();
                $(".products").show();
                $(".category").show();
            }
        });
    </script>