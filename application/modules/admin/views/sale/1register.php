<?php $tabindex= 1; ?>
<link href="<?= base_url('assets/css/bootstrap-datepicker.min.css') ?>" rel="stylesheet">
<h1><img src="<?= base_url('assets/imgs/brands.jpg') ?>" class="header-img" style="margin-top:-3px;"> <?= $description; ?> </h1>
<hr>
    <?php
    if ($this->session->flashdata('error')) {
        ?>
        <hr>
        <div class="alert alert-warning"><?= $this->session->flashdata('error') ?></div>
        <hr>
        <?php
    } 
    ?>
    <div class="row">
        <div class="col-md-8 col-sm-8 col-xs-12">
            <div class="box box-success">
                <div class="box-header with-border">
                    <div class="form-group">
                        <div class="col-xs-12 col-sm-4">
                            <?= form_open('admin/sale/change_mode', array('id'=>'regmode')); ?>
                                <select class="form-control" name="mode" id="register_mode_change" onchange="$('#regmode').submit()">
                                    <!-- <option value="sales_quotation" <?= $mode == 'sales_quotation' ? 'selected': '' ?>>Sales Quotation</option> -->
                                    <!-- <option value="sales_order" <?= $mode == 'sales_order' ? 'selected': '' ?>>Sales Order</option> -->
                                    <option value="sale" <?= $mode == 'sale' ? 'selected': '' ?>>Sales Invoice</option>
                                    <option value="sale_print_invoice" <?= $mode == 'sale_print_invoice' ? 'selected': '' ?>>Print Invoice</option>
                                    <option value="sale_return" <?= $mode == 'sale_return' ? 'selected': '' ?>>Sales Return</option>
                                </select>
                            <?= form_close(); ?>
                        </div>
                        <div class="col-xs-6 col-sm-6">
                            <?= form_open('admin/sale/add', array('id'=>'register_form')); ?>
                                <input autofocus="true" type="text" class="form-control" tabindex="<?= $tabindex++; ?>" name="product_code" id="barcode" placeholder="Start typing item name or scan barcode...">
                            <?= form_close(); ?>
                        </div>
                        <div class="col-xs-6 col-sm-2">
                            <a href="<?= site_url("admin/publish"); ?>" class="btn btn-block btn-primary">Add Item</a>
                        </div>
                    </div>
                </div>
                <div class="box-body table-responsive">
                    <table class="table table-striped">
                        <thead>
                            <tr>
                                <th>&nbsp;</th>
                                <th style="width: 10px">#</th>
                                <th>Item ID</th>
                                <th>Item Name</th>
                                <th>Description</th>
                                <th>Qty</th>
                                <th>Price</th>
                                <th class="text-right">Total</th>
                            </tr>
                        </thead>
                        <tbody id="cart_contents">
                            <?php $i=1; foreach(array_reverse($items, TRUE) as $line=>$item) { ?>
                                <?= form_open('admin/sale/edit_item/'.$line, array('class'=>'edit_item_form')); ?>
                                <input type="hidden" name="tab" class="tab">
                                <tr>
                                    <td><a href="<?= site_url('admin/sale/remove_item/'.$line); ?>"><i class="fa fa-trash"></i></a></td>
                                    <td><?= $i++; ?></td>
                                    <td><?= $item['id']; ?></td>
                                    <td><?= $item['name']; ?><?= form_hidden("item_name", $item['name']); ?></td>
                                    <td><input type="text" name="description" class="md_tb_input input-125" value="<?= $item['description']; ?>" data-val="<?= $item['description']; ?>" tabindex="<?= $tabindex++; ?>"></td>
                                    <td><input type="number" step=".01" name="quantity" class="md_tb_input" value="<?= $item['quantity']; ?>" data-val="<?= $item['quantity']; ?>" tabindex="<?= $tabindex++; ?>"></td>
                                    <td><input type="number" step=".01" name="price" class="md_tb_input" value="<?= $item['price']; ?>" data-val="<?= $item['price']; ?>" tabindex="<?= $tabindex++; ?>"></td>
                                    <td class="text-right">
                                        <?= $item['total']; ?>
                                        <?php
                                        if($item['is_serialized']==1)
                                        {
                                            echo form_hidden(array('name'=>'serialnumber', 'value'=>$item['serialnumber']));
                                        }
                                        else
                                        {
                                            echo form_hidden('serialnumber', '');
                                        }
                                        ?>
                                    </td>
                                </tr>
                                <?= form_close(); ?>
                            <?php }?>
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
        <div class="col-md-4 col-sm-4 col-xs-12">
            <div class="box box-success">
                <div class="box-header with-border">
                    <h3 class="box-title">Customer Information</h3>
                    <div class="form-group">
                        <?php if($customer_id == -1) { ?>
                        <div class="col-xs-10">
                        <?= form_open('admin/sale/add_customer', array('id'=>'add_customer')); ?>
                            <input type="text" name="customer_id" id="customer_id" tabindex="<?= $tabindex++; ?>" class="form-control" placeholder="Scan or type customer info">
                        <?= form_close(); ?>
                        </div>
                        <div class="col-xs-2 fa fa-plus btn btn-info" title="Add new customer" onclick="toggle_div('#add_new_customer_div')"></div>
                        <div id="add_new_customer_div" style="display:none;">
                            <div class="col-md-12 col-xs-12">
                                <br>
                                <?= form_open('admin/sale/add_new_customer', array('id'=>'add_new_customer')); ?>
                                    <div class="form-group">
                                        <input type="text" name="customer_name" tabindex="<?= $tabindex++; ?>" class="form-control" placeholder="Enter Customer Name">
                                    </div>
                                    <div class="form-group">
                                        <input type="text" name="customer_mobile" tabindex="<?= $tabindex++; ?>" class="form-control" placeholder="Enter Customer Mobile">
                                    </div>
                                    <div class="form-group">
                                        <button class="btn btn-success" name="addnewcus">Add Customer</button>
                                    </div>
                                <?= form_close(); ?>
                            </div>
                        </div>
                        <?php } else { ?>
                        <div class="row">
                            <div class="col-xs-3">Name</div>
                            <div class="col-xs-9 text-right"><?= $customer_info->name; ?></div>
                            <div class="col-xs-3">Mobile</div>
                            <div class="col-xs-9 text-right"><?= $customer_info->phone; ?></div>
                            <?php if($customer_info->email != ""){ ?>
                            <div class="col-xs-3">Email</div>
                            <div class="col-xs-9 text-right"><?= $customer_info->email; ?></div>
                            <?php } ?>
                            <?php if($customer_info->balance != 0) { ?>
                            <div class="col-xs-6">Previous<?= $customer_info->balance > 0 ? " Due" : " Advance"; ?></div>
                            <div class="col-xs-6 text-right text-bold text-red"><?= number_format($customer_info->balance, 2); ?></div>
                            <?php } ?>
                            <div class="col-xs-3"></div>
                            <div class="col-xs-9 text-right"><a href="<?= site_url("admin/sale/remove_customer"); ?>" class="btn btn-sm btn-warning">Remove customer</a></div>
                        </div>
                        <?php } ?>
                    </div>
                </div>
                <div class="box-body clearfix">
                <div class="row">
                    <div class="col-xs-6">Invoice Date</div>
                        <div class="col-xs-6 text-right">
                            <?= form_open("admin/sale/inv_date", array("id"=>"inv_date_form")); ?>
                                <input type="text" name="inv_date" value="<?= $inv_date; ?>" tabindex="<?= $tabindex++; ?>" class="form-control inv-reg-date text-right datepicker">
                            <?= form_close(); ?>
                        </div>
                    </div>
                    <div class="row">
                        <div class="col-xs-6">Subtotal</div>
                        <div class="col-xs-6 text-right text-bold"><?= number_format($subtotal, 2); ?></div>
                    </div>
                    <?php if($other_cost>0){ ?>
                    <div class="row">
                        <div class="col-xs-6">Other Cost</div>
                        <div class="col-xs-6 text-right"><?= number_format($other_cost, 2); ?></div>
                    </div>
                    <?php } ?>
                    <?php if($discount>0) { ?>
                    <div class="row">
                        <div class="col-xs-6">Discount</div>
                        <div class="col-xs-6 text-right">(-)<?= number_format($discount, 2); ?></div>
                    </div>
                    <?php } ?>
                    <div class="row">
                        <hr>
                        <div class="col-xs-6">Total Amount</div>
                        <div class="col-xs-6 text-right text-bold"><?= number_format($total, 2); ?></div>
                    </div>
                    <?php $camt = 0; 
                    if($payments_total>0) { ?>
                    <div class="row">
                        <div class="col-xs-6">Payment Receive</div>
                        <div class="col-xs-6 text-right"><?= number_format($payments_total, 2); ?></div>
                        <div class="col-xs-10">
                            <?php foreach ($get_payments as $payment){
                                if($payment['payment_type'] == "Change Amount") { $camt=$payment['payment_amount']; continue; } ?>
                                <div class="col-xs-6">
                                    <a href="<?= site_url('admin/sale/delete_payment/'.$payment['payment_type']); ?>"><i class="fa fa-trash"></i></a><?= $payment['payment_title']; ?>
                                </div>
                                <div class="col-xs-6 text-right"><?= number_format($payment['payment_amount'], 2); ?></div>
                            <?php } ?>
                        </div>
                    </div>
                    <?php } ?>
                    <div class="row">
                        <div class="col-xs-6"><?= $camt >= 0 ? "Amount Due" : "Change Amount"; ?></div>
                        <div class="col-xs-6 text-right text-bold text-red"><?= number_format($camt == 0 ? ($total - $payments_total) : $camt, 2); ?></div>
                    </div>
                    <div class="row">
                        <div class="col-xs-12">
                            <?= form_open("admin/sale/due_collect", array("id"=>"due_collect_form")); ?>
                                <div class="checkbox"><label><input type="checkbox" name="due_collect" value="yes" <?= $due_collect == 'yes' ? 'checked' : ''; ?> tabindex="<?= $tabindex++; ?>"> Automatic Due Collect</label></div>
                            <?= form_close(); ?>
                        </div>
                    </div>
                    <div class="row">
                        <hr>
                        <div class="col-xs-6">Other Cost</div>
                        <div class="col-xs-6 text-right">
                            <?= form_open("admin/sale/other_cost", array("id"=>"other_cost")); ?>
                                <input type="hidden" name="tab" class="tab">
                                <input type="number" step=".01" name="other_cost" value="<?= $other_cost; ?>" data-val="<?= $other_cost; ?>" tabindex="<?= $tabindex++; ?>" class="form-control inv_input">
                            <?= form_close(); ?>
                        </div>
                        <br><br>
                        <div class="col-xs-6">Discount</div>
                        <div class="col-xs-6 text-right">
                            <?= form_open("admin/sale/discount", array("id"=>"discount")); ?>
                                <input type="hidden" name="tab" class="tab">
                                <input type="number" step=".01" name="discount" value="<?= $discount; ?>" data-val="<?= $discount; ?>" tabindex="<?= $tabindex++; ?>" class="form-control inv_input">
                            <?= form_close(); ?>
                        </div>
                        <br><br>
                        <?php // if($total - $payments_total > 0){ ?>
                        <?php // } ?>
                        <?= form_open("admin/sale/add_payment", array("id"=>"add_payment")); ?>
                            <div class="col-xs-6">Payment Type</div>
                            <div class="col-xs-6 text-right">
                                <select name="payment_type" class="form-control payment_type" tabindex="<?= $tabindex++; ?>">
                                    <?php foreach($payment_type as $pt) { ?>
                                    <option value="<?= $pt->id; ?>"><?= $pt->name; ?></option>
                                    <?php } ?>
                                </select>
                            </div>
                            <div class="col-xs-12 payment_details" style="display: none;">
                                <input type="text" name="payment_details" placeholder="Payment details here" class="form-control" style="margin: 5px 0px;">
                            </div>
                            <br><br>
                            <div class="col-xs-6">Amount Tendered</div>
                            <div class="col-xs-6 text-right">
                                <input type="number" step=".01" name="amount_tendered" class="form-control" tabindex="<?= $tabindex; ?>">
                            </div>
                            <br><br>
                            <div class="col-xs-6 pull-right">
                                <button type="submit" class="btn btn-success" tabindex="<?= $tabindex; ?>">Add Payment</button>
                            </div>
                        <?= form_close(); ?>
                    </div>
                    <div class="row">
                        <hr>
                        <div class="col-xs-6"><a href="<?= base_url('admin/sale/cancel'); ?>" tabindex="<?= $tabindex++; ?>"  class="btn btn-danger confirm-delete">Cancel</a></div>
                        <div class="col-xs-6 text-right"><a href="<?= base_url('admin/sale/completed'); ?>" class="btn btn-info" tabindex="<?= $tabindex++; ?>">Make Invoice</a></div>
                    </div>
                </div>
            </div>
        </div>
    </div>
<link rel="stylesheet" href="<?= base_url('assets/js/jquery-ui.min.css') ?>">
<script src="<?= base_url('assets/js/jquery-ui.min.js') ?>"></script>
<script src="<?= base_url('assets/js/bootstrap-datepicker.min.js') ?>"></script>
<script type="text/javascript">
$(document).ready(function()
{   
    <?php if($tab > 1) {?>
    $("input[tabindex="+<?= ($tab+1); ?>+"]").focus();
    <?php } ?>

    $('.datepicker').datepicker({ 
        format: "yyyy-mm-dd", 
        todayHighlight: true
    }).on("changeDate", function (e) { 
        $(this).parent('form').submit();
    });

    $("#barcode").autocomplete(
	{
		source: '<?php echo site_url("admin/sale/item_search"); ?>',
		minChars: 0,
		autoFocus: false,
		delay: 500,
		select: function (a, ui) {
            // console.log(ui.item.value);
			$(this).val(ui.item.value);
			$("#register_form").submit();
			return false;
		}
	});

    $("#cart_contents input").on("keypress blur", function(event)
	{
		if((event.type == "keypress" && event.which == 13) || 
		    (event.type == "blur" && $(this).val() != $(this).attr('data-val')))
		{
            $(".tab").val($(this).attr('tabindex'));
            $(this).parents("tr").prevAll("form:first").submit();
		}
	});
    

    $("#customer_id").autocomplete(
	{
		source: '<?php echo site_url("admin/sale/customer_search"); ?>',
		minChars: 0,
		autoFocus: false,
		delay: 500,
		select: function (a, ui) {
            $(".tab").val($(this).attr('tabindex'));
            $(this).val(ui.item.value);
			$("#add_customer").submit();
			return false;
		}
	});

    $(".inv_input").on("keypress blur", function(event)
    {
        if((event.type == "keypress" && event.which == 13) ||
            (event.type == "blur" && $(this).val() != $(this).attr('data-val')))
		{
            $(".tab").val($(this).attr('tabindex'));
			$(this).closest("form").submit();
		}
    });
    
    $("[type=checkbox]").on("change", function(event)
    {
        $(this).closest("form").submit();
    });

    $(".payment_type").on("change", function(event){
        if(($(event.target).find("option:selected").text()).toLotoLowerCase().indexOf("cash") > -1) $(".payment_details").hide();
        else $(".payment_details").show();
    });
});
</script>