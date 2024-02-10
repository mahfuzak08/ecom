<?php
function convert_number($number) {
	if (($number < 0) || ($number > 999999999)) {
		throw new Exception("Number is out of range");
	}
	$Gn = floor($number / 1000000);
	/* Millions (giga) */
	$number -= $Gn * 1000000;
	$kn = floor($number / 1000);
	/* Thousands (kilo) */
	$number -= $kn * 1000;
	$Hn = floor($number / 100);
	/* Hundreds (hecto) */
	$number -= $Hn * 100;
	$Dn = floor($number / 10);
	/* Tens (deca) */
	$n = $number % 10;
	/* Ones */
	$res = "";
	if ($Gn) {
		$res .= convert_number($Gn) .  "Million";
	}
	if ($kn) {
		$res .= (empty($res) ? "" : " ") . convert_number($kn) . " Thousand";
	}
	if ($Hn) {
		$res .= (empty($res) ? "" : " ") . convert_number($Hn) . " Hundred";
	}
	$ones = array("", "One", "Two", "Three", "Four", "Five", "Six", "Seven", "Eight", "Nine", "Ten", "Eleven", "Twelve", "Thirteen", "Fourteen", "Fifteen", "Sixteen", "Seventeen", "Eightteen", "Nineteen");
	$tens = array("", "", "Twenty", "Thirty", "Fourty", "Fifty", "Sixty", "Seventy", "Eigthy", "Ninety");
	if ($Dn || $n) {
		if (!empty($res)) {
			$res .= " and ";
		}
		if ($Dn < 2) {
			$res .= $ones[$Dn * 10 + $n];
		} else {
			$res .= $tens[$Dn];
			if ($n) {
				$res .= "-" . $ones[$n];
			}
		}
	}
	if (empty($res)) {
		$res = "Zero";
	}
	return $res;
}
?>
<link href="<?= base_url('assets/css/bootstrap-toggle.min.css') ?>" rel="stylesheet">
<link href="<?= base_url('assets/css/bootstrap-datepicker.min.css') ?>" rel="stylesheet">
<div>
    <h1><img src="<?= base_url('assets/imgs/orders.png') ?>" class="header-img" style="margin-top:-2px;"> Orders <?= isset($_GET['settings']) ? ' / Settings' : '' ?></h1>
    <?php if (!isset($_GET['settings'])) { ?>
        <a href="?settings" class="pull-right orders-settings"><i class="fa fa-cog" aria-hidden="true"></i> <span>Settings</span></a>
    <?php } else { ?>
        <a href="<?= base_url('admin/orders') ?>" class="pull-right orders-settings"><i class="fa fa-angle-left" aria-hidden="true"></i> <span>Back</span></a>
    <?php } ?>
</div>
<hr>

<?php
if (!isset($_GET['settings'])) {
    if (!empty($orders)) {
        ?>
        <div style="margin-bottom:10px;" class="row">
            <form action="" method="POST">
                <div class="col-xs-12 col-md-3">
                    <label>Filter By</label>
                    <select class="selectpicker changeOrder">
                        <option <?= isset($_GET['order_by']) && $_GET['order_by'] == 'id' ? 'selected' : '' ?> value="id">Order by new</option>
                        <option <?= (isset($_GET['order_by']) && $_GET['order_by'] == 'processed') || !isset($_GET['order_by']) ? 'selected' : '' ?> value="processed">Order by not processed</option>
                    </select>
                </div>
                <div class="form-group col-xs-6 col-md-2">
                    <label>Order from date</label>
                    <input class="form-control datepicker" name="valid_from_date" value="<?= @$fd ?>" autocomplete="off" type="text">
                </div>
                <div class="form-group col-xs-6 col-md-2">
                    <label>Order to date</label>
                    <input class="form-control datepicker" name="valid_to_date" value="<?= @$td ?>" autocomplete="off" type="text">
                </div>
                <div class="form-group col-xs-12 col-md-5">
                    <input class="btn btn-success" name="filter_by_date" value="Search" type="submit" style="margin-top:20px;">
                    <a href="<?= site_url("admin/orders"); ?>" class="btn btn-default" style="margin-top:20px;">View All</a>
                    <?php if(isset($fd)) {?>
                    <a href="<?= site_url("admin/orders/print_order_lists/"); ?><?= @$fd.'/'.@$td ?>" class="btn btn-success" target="_blank" style="margin-top:20px;">Print</a>
                    <?php } else { ?>
                    <!--<a class="btn btn-warning" data-toggle="modal" data-target="#modalMakeOrder" style="margin-top:20px;">Get Order</a>-->
                    <a href="javascript:void(0);" class="btn btn-info" data-toggle="modal" data-target="#modalBlankBill" data-more-info="000" style="margin-top:20px;">Blank Bill</a>
                    <?php } ?>
                </div>
            </form>
        </div>
        <div class="table-responsive" id="print-order">
            <table class="table table-condensed table-bordered table-striped">
                <thead>
                    <tr>
                        <th>Order ID</th>
                        <th>Date</th>
                        <th>Name</th>
                        <th>Phone</th>
                        <th class="text-center">Status</th>
                        <th class="text-center">Action</th>
                        <th class="text-center">Preview</th>
                        <th class="text-center">Value</th>
                        <th class="text-center">Cost</th>
                        <th class="text-center">Total</th>
                    </tr>
                </thead>
                <tbody>
                    <?php
                    $page_total = 0;
                    $page_delivery_cost = 0;
                    foreach ($orders as $tr) {
                        $status_no_change = "";
                        if ($tr['processed'] == 0) {
                            $type = 'No processed';
                        }
                        if ($tr['processed'] == 1) {
                            $type = 'Processed';
                            $status_no_change = "hidden";
                        }
                        if ($tr['processed'] == 2) {
                            $type = 'Rejected';
                            $status_no_change = "hidden";
                        }
                        if ($tr['processed'] == 3) {
                            $type = 'Processing';
                        }
                        ?>
                        <tr <?= ($tr['viewed'] == 0) ? "style='background:#f9c4c4'" : ""; ?>>
                            <td class="relative" id="order_id-id-<?= $tr['order_id'] ?>">
                                # <?= $tr['order_id'] ?>
                            </td>
                            <!-- <td><?php //echo date('d.M.Y h:iA', $tr['date']); ?></td> -->
                            <td><?= $tr['date']; ?></td>
                            <td>
                                <i class="fa fa-user" aria-hidden="true"></i> 
                                <?= $tr['first_name']; ?>
                            </td>
                            <td><i class="fa fa-phone" aria-hidden="true"></i> <?= $tr['phone'] ?></td>
                            <td class="text-center" data-action-id="<?= $tr['id'] ?>">
                                <b><?= $type ?></b>
                            </td>
                            <td class="text-center">
                                <table style="width:100%;" class="actiontbl<?= $tr['id'] ?> <?= $status_no_change ?>">
                                    <tr>
                                        <td style="height:35px;"><a href="javascript:void(0);" onclick="changeOrdersOrderStatus(<?= $tr['id'] ?>, 3)" class="btn btn-default btn-xs">Processing</a></td>
                                        <td style="height:35px;"><a href="javascript:void(0);" onclick="changeOrdersOrderStatus(<?= $tr['id'] ?>, 2)" class="btn btn-warning btn-xs">Rejected</a></td>
                                        <td style="height:35px;"><a href="javascript:void(0);" onclick="changeOrdersOrderStatus(<?= $tr['id'] ?>, 1)" class="btn btn-success btn-xs">Processed</a></td>
                                    </tr>
                                </table>
                            </td>
                            <td class="hidden" id="order-id-<?= $tr['order_id'] ?>">
                                <?php $total_amt = $tr['post_code']; ?>
                                <div class="table-responsive">
                                    <table class="table more-info-purchase">
                                        <tbody>
                                            <tr>
                                                <td><b>Email</b></td>
                                                <td><a href="mailto:<?= $tr['email'] ?>"><?= $tr['email'] ?></a></td>
                                            </tr>
                                            <tr>
                                                <td><b>City</b></td>
                                                <td><?= $tr['city'] ?></td>
                                            </tr>
                                            <tr>
                                                <td><b>Address</b></td>
                                                <td><?= $tr['address'] ?></td>
                                            </tr>
                                            <tr>
                                                <td><b>Delivery Zone</b></td>
                                                <td><?= $tr['city'] ?></td>
                                            </tr>
                                            <tr>
                                                <td><b>Delivery Cost</b></td>
                                                <td><?= $tr['post_code'] . CURRENCY ?></td>
                                            </tr>
                                            <tr>
                                                <td><b>Notes</b></td>
                                                <td><?= $tr['notes'] ?></td>
                                            </tr>
                                            <tr>
                                                <td><b>Come from site</b></td>
                                                <td>
                                                    <?php if ($tr['referrer'] != 'Direct') { ?>
                                                        <a target="_blank" href="<?= $tr['referrer'] ?>" class="orders-referral">
                                                            <?= $tr['referrer'] ?>
                                                        </a>
                                                    <?php } else { ?>
                                                        Direct traffic or referrer is not visible                       
                                                    <?php } ?>
                                                </td>
                                            </tr>
                                            <tr>
                                                <td><b>Payment Type</b></td>
                                                <td><?php 
                                                    $paylist = @unserialize($tr['payment_type']); 
                                                    if($paylist !== false){
                                                        foreach ($paylist as $value) {
                                                            echo $value['payment_title'] . " = " . $value['payment_amount'] . "<br>";
                                                        }
                                                    }else{
                                                        echo $tr['payment_type'];
                                                    }
                                                    ?>
                                                </td>
                                            </tr>
                                            <tr>
                                                <td><b>Discount</b></td>
                                                <td><?= $tr['discount_type'] == 'float' ? '-' . $tr['discount_amount'] : '-' . $tr['discount_amount'] . '%' ?></td>
                                            </tr>
                                            <?php if ($tr['payment_type'] == 'PayPal') { ?>
                                                <tr>
                                                    <td><b>PayPal Status</b></td>
                                                    <td><?= $tr['paypal_status'] ?></td>
                                                </tr>
                                            <?php } ?>
                                            <tr>
                                                <td colspan="2"><b>Products</b></td>
                                            </tr>
                                            <tr>
                                                <td colspan="2">
                                                    <?php
                                                    $wish_price = 0;
                                                    $arr_products = unserialize($tr['products']);
                                                    foreach ($arr_products as $product) {
                                                        $product_amt = (float) $product['product_info']['price'];
                                                        $size = @$product['product_info']['size'];
                                                        $row_amt = $product_amt * (float) $product['product_quantity'];
                                                        if(isset($product['product_info']['wish_price'])){
                                                            if($product['product_info']['wish_price']>0){
                                                                $wish_price += $product['product_info']['wish_price'];
                                                            }
                                                        }
                                                        ?>
                                                        <div style="word-break: break-all;">
                                                            <div>
                                                                <img src="<?= base_url('attachments/'. SHOP_DIR .'/shop_images/' . $product['product_info']['image']) ?>" alt="Product" style="width:100px; margin-right:10px;" class="img-responsive">
                                                            </div>
                                                            <a data-toggle="tooltip" data-placement="top" title="Click to preview" target="_blank" href="<?= base_url($product['product_info']['url']) ?>">
                                                                <?= base_url($product['product_info']['url']) ?>
                                                                <div style=" background-color: #f1f1f1; border-radius: 2px; padding: 2px 5px;">
                                                                    <b>Quantity:</b> <?= $product['product_quantity'] ?> / 
                                                                    <?php if(isset($size) && $size > 0) {?>
                                                                        <b>Size:</b> <?= $size; ?> /
                                                                    <?php }  ?>
                                                                    <b>Price: <?= $product_amt.' '.$this->config->item('currency') ?></b>
                                                                </div>
                                                            </a>
                                                            <div class="clearfix"></div>
                                                        </div>
                                                        <div style="padding-top:10px; font-size:16px;">Total amount of products: <?= $row_amt.' '.$this->config->item('currency') ?></div>
                                                        <?php $total_amt += $row_amt; ?>
                                                        <hr>
                                                    <?php }
                                                    ?>
                                                </td>
                                            </tr>
                                            <tr>
                                                <td><b>Total Order Amount</b></td>
                                                <td>
                                                    <?php 
                                                    $total_discount_amt = 0;
                                                    if($tr['discount_type'] == 'float'){
                                                        $total_discount_amt = $tr['discount_amount'];
                                                    }elseif($wish_price>0){
                                                        $total_discount_amt = $total_amt - $wish_price;
                                                    } else{
                                                        $total_discount_amt = ($total_amt - $tr['post_code']) * $tr['discount_amount'] / 100;
                                                    }
                                                    $total_amt = $total_amt - $total_discount_amt;
                                                    echo $total_amt;
                                                    ?>
                                                </td>
                                            </tr>
                                        </tbody>
                                    </table>
                                </div>
                            </td>
                            <td class="hidden" id="order-id-bill-<?= $tr['order_id'] ?>">
                                <div class="table-responsive" style="font-family: 'Courier New', Courier, monospace; font-weight: bold;">
                                    <table style="width:100%; border-collapse: collapse;">
                                        <tbody>
                                            <tr>
                                                <!-- Office Copy -->
                                                <td style="width:33.3%;padding: 0px 10px;">
                                                    <table style="width:100%; border-collapse: collapse;">
                                                        <tr>
                                                            <td style="width:33.3%">&nbsp;</td>
                                                            <td style="width:33.3%" align="center"><span style="background:#333;color:#FFF;padding: 2px 5px;border-radius: 5px;">BILL</span></td>
                                                            <td style="width:33.3%" align="right">Office Copy</td>
                                                        </tr>
                                                        <tr>
                                                            <td colspan="3" align="center">
                                                                <img src="<?= base_url('attachments/'. SHOP_DIR .'/site_logo/' . $sitelogo) ?>" style="max-width: 300px;max-height:100px;" alt="<?= $_SERVER['HTTP_HOST'] ?>">
                                                            </td>
                                                        </tr>
                                                        <tr>
                                                            <td colspan="3" align="center" style="border-bottom: 1px solid #000;">
                                                                <?= $footerContactPhone; ?><br>
                                                                <?= base_url(); ?><br>
                                                                <i><b><?= $footerContactAddr; ?></b></i>
                                                            </td>
                                                        </tr>
                                                        <tr>
                                                            <td style="border-bottom: 1px solid #000;font-size: 12px;" colspan="2"><b>Name:</b> <?= $tr['first_name'] ?></td>
                                                            <td style="border-bottom: 1px solid #000;font-size: 12px;">Date: <?= $tr['date'] ?></td>
                                                        </tr>
                                                        <tr>
                                                            <td style="border-bottom: 1px solid #000;font-size: 12px;" colspan="2"><b>Address:</b> <?= $tr['address'] ?></td>
                                                            <td style="border-bottom: 1px solid #000;font-size: 12px;"><b>Order No:</b> <?= $tr['order_id'] ?></td>
                                                        </tr>
                                                        <tr>
                                                            <td style="border-bottom: 1px solid #000;font-size: 12px;" colspan="2"><b>Delivery Zone:</b> <?= $tr['city'] ?></td>
                                                            <td style="border-bottom: 1px solid #000;font-size: 12px;"><b>Mob:</b> <?= $tr['phone'] ?></td>
                                                        </tr>
                                                    </table>
                                                    <table style="width:100%;padding:0px 2px 0px 2px; margin: auto;">
                                                        <tr>
                                                            <td style="height: 250px;vertical-align: top;border-left: 1px solid #000;border-right: 1px solid #000;">
                                                                <table style="width:100%;padding:0px 2px 0px 2px; margin: auto; border-collapse: collapse;">
                                                                    <tr>
                                                                        <td style="width:8%;padding: 0px 0px 0px 5px;background: #CCC;font-weight: 700;font-size: 12px;">SL</td>
                                                                        <td style="width: 42%;background: #CCC;font-weight: 700;font-size: 12px;text-align: center;">Description</td>
                                                                        <td style="width: 10%;background: #CCC;font-weight: 700;font-size: 12px;text-align: center;">Qty</td>
                                                                        <td style="width:16%;background: #CCC;font-weight: 700;font-size: 12px;text-align: right;">Price</td>
                                                                        <td style="width:24%;background: #CCC;font-weight: 700;font-size: 12px;padding-right: 10px;text-align: right;">Total</td>
                                                                    </tr>
                                                                    <?php
                                                                    $i = 0;
                                                                    $arr_products = unserialize($tr['products']);
                                                                    $subtotal = 0;
                                                                    foreach ($arr_products as $product) {
                                                                        $product_amt = (float) $product['product_info']['price'];
                                                                        $row_amt = $product_amt * (float) $product['product_quantity'];
                                                                        $subtotal += $row_amt;
                                                                    ?>
                                                                    <tr>
                                                                        <td style="width:8%;border-bottom: 1px solid #000;padding: 0px 0px 0px 5px;font-size: 12px;"><?= ++$i; ?></td>
                                                                        <td style="width: 42%;border-bottom: 1px solid #000;font-size: 12px;">
                                                                            <?php 
                                                                            echo $product['product_info']['url']; 
                                                                            if(isset($product['product_info']['size']) && $product['product_info']['size'] != '' && $product['product_info']['size'] != 'N' && $product['product_info']['size'] != '0')
                                                                                echo "<br>Size: ".$product['product_info']['size'];
                                                                            ?>
                                                                        </td>
                                                                        <td style="width: 10%;border-bottom: 1px solid #000;font-size: 12px;text-align: center;"><?= $product['product_quantity'] ?></td>
                                                                        <td style="width:16%;border-bottom: 1px solid #000;font-size: 12px;text-align: right;"><?= $product_amt.' '.CURRENCY ?></td>
                                                                        <td style="width:24%;border-bottom: 1px solid #000;font-size: 12px;padding-right: 10px;text-align: right;"><?= $row_amt.' '.CURRENCY ?></td>
                                                                    </tr>
                                                                    <?php } ?>
                                                                </table>
                                                            </td>
                                                        </tr>
                                                        <tr>
                                                            <td>
                                                                <table style="width:100%;padding:0px 2px 0px 2px;border-left: 1px solid #000;border-right: 1px solid #000; margin: auto; border-collapse: collapse;">
                                                                    <tr>
                                                                        <td style="width:76%; border-bottom: 1px solid #000;border-top: 1px solid #000;font-size: 12px;text-align: right;"><b>Subtotal</b></td>
                                                                        <td style="width:24%; border-bottom: 1px solid #000;border-top: 1px solid #000;font-size: 12px;padding-right:10px;text-align: right;"><b><?= $subtotal .' '.  CURRENCY ?></b></td>
                                                                    </tr>
                                                                    <tr>
                                                                        <td style="width:76%; border-bottom: 1px solid #000;border-top: 1px solid #000;font-size: 12px;text-align: right;"><b>Total Discount</b></td>
                                                                        <td style="width:24%; border-bottom: 1px solid #000;border-top: 1px solid #000;font-size: 12px;padding-right:10px;text-align: right;"><b>- <?= $total_discount_amt  .' '. CURRENCY ?></b></td>
                                                                    </tr>
                                                                    <tr>
                                                                        <td style="width:76%; border-bottom: 1px solid #000;border-top: 1px solid #000;font-size: 12px;text-align: right;"><b>Delivery Cost</b></td>
                                                                        <td style="width:24%; border-bottom: 1px solid #000;border-top: 1px solid #000;font-size: 12px;padding-right:10px;text-align: right;"><b><?= $tr['post_code'] .' '.  CURRENCY ?></b></td>
                                                                    </tr>
                                                                    <tr>
                                                                        <td style="width:76%; border-bottom: 1px solid #000;border-top: 1px solid #000;font-size: 12px;text-align: right;"><b>Total</b></td>
                                                                        <td style="width:24%; border-bottom: 1px solid #000;border-top: 1px solid #000;font-size: 12px;padding-right:10px;text-align: right;"><b><?= $total_amt.' '.CURRENCY ?></b></td>
                                                                    </tr>
                                                                    <tr>
                                                                        <td colspan="2" style="font-size: 12px;border-bottom: 1px solid #000;">
                                                                            <b>Amount In Word</b>: <?= convert_number($total_amt) ?> Taka Only.
                                                                        </td>
                                                                    </tr>
                                                                </table>
                                                            </td>
                                                        </tr>
                                                    </table>
                                                    <p style="text-align:center">
                                                        <br>
                                                        This is a system genereted invoice.<br>Powered by ABSoft-BD.com
                                                    </p>
                                                </td>
                                                <!-- Vendor Copy -->
                                                <td style="width:33.3%;padding: 0px 10px;border-left:1px solid #DDD;border-right:1px solid #DDD;">
                                                    <table style="width:100%; border-collapse: collapse;">
                                                        <tr>
                                                            <td style="width:33.3%">&nbsp;</td>
                                                            <td style="width:33.3%" align="center"><span style="background:#333;color:#FFF;padding: 2px 5px;border-radius: 5px;">BILL</span></td>
                                                            <td style="width:33.3%" align="right">Vendor Copy</td>
                                                        </tr>
                                                        <tr>
                                                            <td colspan="3" align="center">
                                                                <img src="<?= base_url('attachments/'. SHOP_DIR .'/site_logo/' . $sitelogo) ?>" style="max-width: 300px;max-height:100px;" alt="<?= $_SERVER['HTTP_HOST'] ?>">
                                                            </td>
                                                        </tr>
                                                        <tr>
                                                            <td colspan="3" align="center" style="border-bottom: 1px solid #000;">
                                                                <?= $footerContactPhone; ?><br>
                                                                <?= base_url(); ?><br>
                                                                <i><b><?= $footerContactAddr; ?></b></i>
                                                            </td>
                                                        </tr>
                                                        <tr>
                                                            <td style="border-bottom: 1px solid #000;font-size: 12px;" colspan="2"><b>Name:</b> <?= base_url(); ?></td>
                                                            <td style="border-bottom: 1px solid #000;font-size: 12px;">Date: <?= $tr['date'] ?></td>
                                                        </tr>
                                                        <tr>
                                                            <td style="border-bottom: 1px solid #000;font-size: 12px;" colspan="2"><b>Address:</b> <?= $footerContactAddr ?></td>
                                                            <td style="border-bottom: 1px solid #000;font-size: 12px;"><b>Order No:</b> <?= $tr['order_id'] ?></td>
                                                        </tr>
                                                        <tr>
                                                            <td style="border-bottom: 1px solid #000;font-size: 12px;" colspan="3"><b>Mob:</b> <?= $footerContactPhone; ?></td>
                                                        </tr>
                                                    </table>
                                                    <table style="width:100%;padding:0px 2px 0px 2px; margin: auto;">
                                                        <tr>
                                                            <td style="height: 250px;vertical-align: top;border-left: 1px solid #000;border-right: 1px solid #000;">
                                                                <table style="width:100%;padding:0px 2px 0px 2px; margin: auto; border-collapse: collapse;">
                                                                    <tr>
                                                                        <td style="width:8%;padding: 0px 0px 0px 5px;background: #CCC;font-weight: 700;font-size: 12px;">SL</td>
                                                                        <td style="width: 42%;background: #CCC;font-weight: 700;font-size: 12px;text-align: center;">Description</td>
                                                                        <td style="width: 10%;background: #CCC;font-weight: 700;font-size: 12px;text-align: center;">Qty</td>
                                                                        <td style="width:16%;background: #CCC;font-weight: 700;font-size: 12px;text-align: right;">Price</td>
                                                                        <td style="width:24%;background: #CCC;font-weight: 700;font-size: 12px;padding-right: 10px;text-align: right;">Total</td>
                                                                    </tr>
                                                                    <?php
                                                                    $i = 0;
                                                                    $arr_products = unserialize($tr['products']);
                                                                    $subtotal = 0;
                                                                    foreach ($arr_products as $product) {
                                                                        $product_amt = (float) $product['product_info']['price'];
                                                                        $row_amt = $product_amt * (float) $product['product_quantity'];
                                                                        $subtotal += $row_amt;
                                                                    ?>
                                                                    <tr>
                                                                        <td style="width:8%;border-bottom: 1px solid #000;padding: 0px 0px 0px 5px;font-size: 12px;"><?= ++$i; ?></td>
                                                                        <td style="width: 42%;border-bottom: 1px solid #000;font-size: 12px;">
                                                                            <?php 
                                                                            echo $product['product_info']['url']; 
                                                                            if(isset($product['product_info']['size']) && $product['product_info']['size'] != '' && $product['product_info']['size'] != 'N' && $product['product_info']['size'] != '0')
                                                                                echo "<br>Size: ".$product['product_info']['size'];
                                                                            ?>
                                                                        </td>
                                                                        <td style="width: 10%;border-bottom: 1px solid #000;font-size: 12px;text-align: center;"><?= $product['product_quantity'] ?></td>
                                                                        <td style="width:16%;border-bottom: 1px solid #000;font-size: 12px;text-align: right;"><?= $product_amt.' '.CURRENCY ?></td>
                                                                        <td style="width:24%;border-bottom: 1px solid #000;font-size: 12px;padding-right: 10px;text-align: right;"><?= $row_amt.' '.CURRENCY ?></td>
                                                                    </tr>
                                                                    <?php } ?>
                                                                </table>
                                                            </td>
                                                        </tr>
                                                        <tr>
                                                            <td>
                                                                <table style="width:100%;padding:0px 2px 0px 2px;border-left: 1px solid #000;border-right: 1px solid #000; margin: auto; border-collapse: collapse;">
                                                                    <tr>
                                                                        <td style="width:76%; border-bottom: 1px solid #000;border-top: 1px solid #000;font-size: 12px;text-align: right;"><b>Subtotal</b></td>
                                                                        <td style="width:24%; border-bottom: 1px solid #000;border-top: 1px solid #000;font-size: 12px;padding-right:10px;text-align: right;"><b><?= $subtotal.' '. CURRENCY ?></b></td>
                                                                    </tr>
                                                                    <tr>
                                                                        <td style="width:76%; border-bottom: 1px solid #000;border-top: 1px solid #000;font-size: 12px;text-align: right;"><b>Total Discount</b></td>
                                                                        <td style="width:24%; border-bottom: 1px solid #000;border-top: 1px solid #000;font-size: 12px;padding-right:10px;text-align: right;"><b>- <?= $total_discount_amt  .' '. CURRENCY ?></b></td>
                                                                    </tr>
                                                                    <tr>
                                                                        <td style="width:76%; border-bottom: 1px solid #000;border-top: 1px solid #000;font-size: 12px;text-align: right;"><b>Total</b></td>
                                                                        <td style="width:24%; border-bottom: 1px solid #000;border-top: 1px solid #000;font-size: 12px;padding-right:10px;text-align: right;"><b><?= ($total_amt - $tr['post_code']) .' '.CURRENCY ?></b></td>
                                                                    </tr>
                                                                    <tr>
                                                                        <td colspan="2" style="font-size: 12px;border-bottom: 1px solid #000;">
                                                                            <b>Amount In Word</b>: <?= convert_number($total_amt - $tr['post_code']) ?> Taka Only.
                                                                        </td>
                                                                    </tr>
                                                                </table>
                                                            </td>
                                                        </tr>
                                                    </table>
                                                    <p style="text-align:center">
                                                        <br>
                                                        This is a system genereted invoice.<br>Powered by ABSoft-BD.com
                                                    </p>
                                                </td>
                                                <!-- Customer Copy -->
												<td style="width:33.3%;padding: 0px 10px;vertical-align:top;">
                                                    <table style="width:100%; border-collapse: collapse;">
                                                        <tr>
                                                            <td style="width:33.3%">&nbsp;</td>
                                                            <td style="width:33.3%" align="center"><span style="background:#333;color:#FFF;padding: 2px 5px;border-radius: 5px;">BILL</span></td>
                                                            <td style="width:33.3%" align="right">Customer Copy</td>
                                                        </tr>
                                                        <tr>
                                                            <td colspan="3" align="center">
                                                                <img src="<?= base_url('attachments/'. SHOP_DIR .'/site_logo/' . $sitelogo) ?>" style="max-width: 300px;max-height:100px;" alt="<?= $_SERVER['HTTP_HOST'] ?>">
                                                            </td>
                                                        </tr>
                                                        <tr>
                                                            <td colspan="3" align="center" style="border-bottom: 1px solid #000;">
                                                                <?= $footerContactPhone; ?><br>
                                                                <?= base_url(); ?><br>
                                                                <i><b><?= $footerContactAddr; ?></b></i>
                                                            </td>
                                                        </tr>
                                                        <tr>
                                                            <td style="border-bottom: 1px solid #000;font-size: 12px;" colspan="2"><b>Name:</b> <?= $tr['first_name'] ?></td>
                                                            <td style="border-bottom: 1px solid #000;font-size: 12px;">Date: <?= $tr['date'] ?></td>
                                                        </tr>
                                                        <tr>
                                                            <td style="border-bottom: 1px solid #000;font-size: 12px;" colspan="2"><b>Address:</b> <?= $tr['address'] ?></td>
                                                            <td style="border-bottom: 1px solid #000;font-size: 12px;"><b>Order No:</b> <?= $tr['order_id'] ?></td>
                                                        </tr>
                                                        <tr>
                                                            <td style="border-bottom: 1px solid #000;font-size: 12px;" colspan="2"><b>Delivery Zone:</b> <?= $tr['city'] ?></td>
                                                            <td style="border-bottom: 1px solid #000;font-size: 12px;"><b>Mob:</b> <?= $tr['phone'] ?></td>
                                                        </tr>
                                                    </table>
                                                    <table style="width:100%;padding:0px 2px 0px 2px; margin: auto;">
                                                        <tr>
                                                            <td style="height: 250px;vertical-align: top;border-left: 1px solid #000;border-right: 1px solid #000;">
                                                                <table style="width:100%;padding:0px 2px 0px 2px; margin: auto; border-collapse: collapse;">
                                                                    <tr>
                                                                        <td style="width:8%;padding: 0px 0px 0px 5px;background: #CCC;font-weight: 700;font-size: 12px;">SL</td>
                                                                        <td style="width: 42%;background: #CCC;font-weight: 700;font-size: 12px;text-align: center;">Description</td>
                                                                        <td style="width: 10%;background: #CCC;font-weight: 700;font-size: 12px;text-align: center;">Qty</td>
                                                                        <td style="width:16%;background: #CCC;font-weight: 700;font-size: 12px;text-align: right;">Price</td>
                                                                        <td style="width:24%;background: #CCC;font-weight: 700;font-size: 12px;padding-right: 10px;text-align: right;">Total</td>
                                                                    </tr>
                                                                    <?php
                                                                    $i = 0;
                                                                    $arr_products = unserialize($tr['products']);
                                                                    $subtotal = 0;
                                                                    foreach ($arr_products as $product) {
                                                                        $product_amt = (float) $product['product_info']['price'];
                                                                        $row_amt = $product_amt * (float) $product['product_quantity'];
                                                                        $subtotal += $row_amt;
                                                                    ?>
                                                                    <tr>
                                                                        <td style="width:8%;border-bottom: 1px solid #000;padding: 0px 0px 0px 5px;font-size: 12px;"><?= ++$i; ?></td>
                                                                        <td style="width: 42%;border-bottom: 1px solid #000;font-size: 12px;">
                                                                            <?php 
                                                                            echo $product['product_info']['url']; 
                                                                            if(isset($product['product_info']['size']) && $product['product_info']['size'] != '' && $product['product_info']['size'] != 'N' && $product['product_info']['size'] != '0')
                                                                                echo "<br>Size: ".$product['product_info']['size'];
                                                                            ?>
                                                                        </td>
                                                                        <td style="width: 10%;border-bottom: 1px solid #000;font-size: 12px;text-align: center;"><?= $product['product_quantity'] ?></td>
                                                                        <td style="width:16%;border-bottom: 1px solid #000;font-size: 12px;text-align: right;"><?= $product_amt.' '.CURRENCY ?></td>
                                                                        <td style="width:24%;border-bottom: 1px solid #000;font-size: 12px;padding-right: 10px;text-align: right;"><?= $row_amt.' '.CURRENCY ?></td>
                                                                    </tr>
                                                                    <?php } ?>
                                                                </table>
                                                            </td>
                                                        </tr>
                                                        <tr>
                                                            <td>
                                                                <table style="width:100%;padding:0px 2px 0px 2px;border-left: 1px solid #000;border-right: 1px solid #000; margin: auto; border-collapse: collapse;">
                                                                    <tr>
                                                                        <td style="width:76%; border-bottom: 1px solid #000;border-top: 1px solid #000;font-size: 12px;text-align: right;"><b>Subtotal</b></td>
                                                                        <td style="width:24%; border-bottom: 1px solid #000;border-top: 1px solid #000;font-size: 12px;padding-right:10px;text-align: right;"><b><?= $subtotal.' '. CURRENCY ?></b></td>
                                                                    </tr>
                                                                    <tr>
                                                                        <td style="width:76%; border-bottom: 1px solid #000;border-top: 1px solid #000;font-size: 12px;text-align: right;"><b>Total Discount</b></td>
                                                                        <td style="width:24%; border-bottom: 1px solid #000;border-top: 1px solid #000;font-size: 12px;padding-right:10px;text-align: right;"><b>- <?= $total_discount_amt .' '. CURRENCY ?></b></td>
                                                                    </tr>
                                                                    <tr>
                                                                        <td style="width:76%; border-bottom: 1px solid #000;border-top: 1px solid #000;font-size: 12px;text-align: right;"><b>Delivery Cost</b></td>
                                                                        <td style="width:24%; border-bottom: 1px solid #000;border-top: 1px solid #000;font-size: 12px;padding-right:10px;text-align: right;"><b><?= $tr['post_code'].' '. CURRENCY ?></b></td>
                                                                    </tr>
                                                                    <tr>
                                                                        <td style="width:76%; border-bottom: 1px solid #000;border-top: 1px solid #000;font-size: 12px;text-align: right;"><b>Total</b></td>
                                                                        <td style="width:24%; border-bottom: 1px solid #000;border-top: 1px solid #000;font-size: 12px;padding-right:10px;text-align: right;"><b><?= $total_amt.' '.CURRENCY ?></b></td>
                                                                    </tr>
                                                                    <tr>
                                                                        <td colspan="2" style="font-size: 12px;border-bottom: 1px solid #000;">
                                                                            <b>Amount In Word</b>: <?= convert_number($total_amt) ?> Taka Only.
                                                                        </td>
                                                                    </tr>
                                                                </table>
                                                            </td>
                                                        </tr>
                                                    </table>
                                                    <p style="text-align:center">
                                                        <br>
                                                        This is a system genereted invoice.<br>Powered by ABSoft-BD.com
                                                    </p>
                                                </td>
                                            </tr>
                                        </tbody>
                                    </table>
                                </div>
                            </td>
                            <td class="text-center">
                                <a href="javascript:void(0);" class="btn btn-default bill-info" data-toggle="modal" data-target="#modalPreviewBillInfo" data-more-info="<?= $tr['order_id'] ?>">
                                    Bill <i class="fa fa-info-circle" aria-hidden="true"></i>
                                </a>&nbsp;
                                <a href="javascript:void(0);" class="btn btn-default more-info" data-toggle="modal" data-target="#modalPreviewMoreInfo" data-more-info="<?= $tr['order_id'] ?>">
                                    <i class="fa fa-eye" aria-hidden="true"></i>
                                </a>
                            </td>
                            <td class="text-center"><?= $total_amt - $tr['post_code']; ?></td>
                            <td class="text-center"><?= $tr['post_code']; ?></td>
                            <td class="text-center text-bold"><?= $total_amt.' '.CURRENCY; ?></td>
                        </tr>
                        <?php $page_total+= $total_amt; $page_delivery_cost += $tr['post_code']; ?>
                    <?php } ?>
                </tbody>
                <tfoot>
                    <td align=right colspan=7>Total</td>
                    <td align=right><b><?= ($page_total - $page_delivery_cost); ?></b></td>
                    <td align=right><b><?= $page_delivery_cost; ?></b></td>
                    <td align=right><b><?= $page_total.' '.CURRENCY; ?></b></td>
                </tfoot>
            </table>
        </div>
        <?= $links_pagination ?>
    <?php } else { ?>
        <div class="alert alert-info">No orders to the moment!</div>
    <?php }
    ?>        
    <hr>
    <?php
}
if (isset($_GET['settings'])) {
    ?>
    <h3>Cash On Delivery</h3>
    <div class="row">
        <div class="col-sm-4">
            <div class="panel panel-default">
                <div class="panel-heading">Change visibility of this purchase option</div>
                <div class="panel-body">
                    <?php if ($this->session->flashdata('cashondelivery_visibility')) { ?>
                        <div class="alert alert-info"><?= $this->session->flashdata('cashondelivery_visibility') ?></div>
                    <?php } ?>
                    <form method="POST" action="">
                        <input type="hidden" name="cashondelivery_visibility" value="<?= $cashondelivery_visibility ?>">
                        <input <?= $cashondelivery_visibility == 1 ? 'checked' : '' ?> data-toggle="toggle" data-for-field="cashondelivery_visibility" class="toggle-changer" type="checkbox">
                        <button class="btn btn-default" value="" type="submit">
                            Save
                        </button>
                    </form>
                </div>
            </div>
        </div>
    </div>
    <hr>
    <h3>Paypal Account Settings</h3>
    <div class="row">
        <div class="col-sm-6">
            <div class="panel panel-default">
                <div class="panel-heading">Paypal sandbox mode (use for paypal account tests)</div>
                <div class="panel-body">
                    <?php if ($this->session->flashdata('paypal_sandbox')) { ?>
                        <div class="alert alert-info"><?= $this->session->flashdata('paypal_sandbox') ?></div>
                    <?php } ?>
                    <form method="POST" action="">
                        <input type="hidden" name="paypal_sandbox" value="<?= $paypal_sandbox ?>">
                        <input <?= $paypal_sandbox == 1 ? 'checked' : '' ?> data-toggle="toggle" data-for-field="paypal_sandbox" class="toggle-changer" type="checkbox">
                        <button class="btn btn-default" value="" type="submit">
                            Save
                        </button>
                    </form>
                </div>
            </div>
        </div>
        <div class="col-sm-6">
            <div class="panel panel-default">
                <div class="panel-heading">Paypal business email</div>
                <div class="panel-body">
                    <?php if ($this->session->flashdata('paypal_email')) { ?>
                        <div class="alert alert-info"><?= $this->session->flashdata('paypal_email') ?></div>
                    <?php } ?>
                    <form method="POST" action="">
                        <div class="input-group">
                            <input class="form-control" placeholder="Leave empty for no paypal available method" name="paypal_email" value="<?= $paypal_email ?>" type="text">
                            <span class="input-group-btn">
                                <button class="btn btn-default" value="" type="submit">
                                    <i class="fa fa-pencil-square-o" aria-hidden="true"></i>
                                </button>
                            </span>
                        </div>
                    </form>
                </div>
            </div>
        </div> 
    </div>
    <hr>
    <h3>Bank Account Settings</h3>
    <div class="row">
        <div class="col-sm-6">
            <?php if ($this->session->flashdata('bank_account')) { ?>
                <div class="alert alert-info"><?= $this->session->flashdata('bank_account') ?></div>
            <?php } ?>
            <form method="POST" action="">
                <div class="table-responsive">
                    <table class="table table-bordered">
                        <tbody>
                            <tr>
                                <td colspan="2"><b>Pay to - Recipient name/ltd</b></td>
                            </tr>
                            <tr>
                                <td colspan="2"><input type="text" name="name" value="<?= $bank_account != null ? $bank_account['name'] : '' ?>" class="form-control" placeholder="Example: BoxingTeam Ltd."></td>
                            </tr>
                            <tr>
                                <td><b>IBAN</b></td>
                                <td><b>BIC</b></td>
                            </tr>
                            <tr>
                                <td><input type="text" class="form-control" value="<?= $bank_account != null ? $bank_account['iban'] : '' ?>" name="iban" placeholder="Example: BG11FIBB329291923912301230"></td>
                                <td><input type="text" class="form-control" value="<?= $bank_account != null ? $bank_account['bic'] : '' ?>" name="bic" placeholder="Example: FIBBGSF"></td>
                            </tr>
                            <tr>
                                <td colspan="2"><b>Bank</b></td>
                            </tr>
                            <tr>
                                <td colspan="2"><input type="text" value="<?= $bank_account != null ? $bank_account['bank'] : '' ?>" name="bank" class="form-control" placeholder="Example: First Investment Bank"></td>
                            </tr>
                        </tbody>
                    </table>
                </div>
                <input type="submit" class="form-control" value="Save Bank Account Settings">
            </form>
        </div>
    </div>
<?php } ?>
<!-- Modal for more info buttons in orders -->
<div class="modal fade" id="modalPreviewMoreInfo" tabindex="-1" role="dialog" aria-labelledby="myModalLabel">
    <div class="modal-dialog" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
                <h4 class="modal-title" id="myModalLabel">Preview <b id="client-name"></b></h4>
            </div>
            <div class="modal-body" id="preview-info-body">

            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-default" data-dismiss="modal">Close</button>
            </div>
        </div>
    </div>
</div>
<div class="modal fade" id="modalPreviewBillInfo" tabindex="-1" role="dialog" aria-labelledby="myModalLabel">
    <div class="modal-dialog" role="document">
        <div class="modal-content">
            <div class="modal-body" id="bill-info-body">

            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-success" onclick="printDiv('bill-info-body')">Print</button>
                <button type="button" class="btn btn-default" data-dismiss="modal">Close</button>
            </div>
        </div>
    </div>
</div>
<div class="modal fade" id="modalBlankBill" tabindex="-1" role="dialog" aria-labelledby="myModalLabel">
    <div class="modal-dialog" role="document" style="width: 100%;">
        <div class="modal-content">
            <div class="modal-body" id="blank-bill">
                <div class="table-responsive" style="font-family: 'Courier New', Courier, monospace; font-weight: bold;">
                    <table style="width:100%; border-collapse: collapse;">
                        <tbody>
                            <tr>
                                <td style="width:33.3%;padding: 0px 10px;">
                                    <table style="width:100%; border-collapse: collapse;">
                                        <tr>
                                            <td style="width:33.3%">&nbsp;</td>
                                            <td style="width:33.3%" align="center"><span style="background:#333;color:#FFF;padding: 2px 5px;border-radius: 5px;">BILL</span></td>
                                            <td style="width:33.3%" align="right">Customer Copy</td>
                                        </tr>
                                        <tr>
                                            <td colspan="3" align="center">
                                                <img src="<?= base_url('attachments/'. SHOP_DIR .'/site_logo/' . $sitelogo) ?>" style="max-width: 300px;max-height:100px;" alt="<?= $_SERVER['HTTP_HOST'] ?>">
                                            </td>
                                        </tr>
                                        <tr>
                                            <td colspan="3" align="center" style="border-bottom: 1px solid #000;">
                                                <?= $footerContactPhone; ?><br>
                                                <?= base_url(); ?><br>
                                                <i><b><?= $footerContactAddr; ?></b></i>
                                            </td>
                                        </tr>
                                        <tr>
                                            <td style="border-bottom: 1px solid #000;font-size: 12px;" colspan="2"><b>Name:</b> </td>
                                            <td style="border-bottom: 1px solid #000;font-size: 12px;"></td>
                                        </tr>
                                        <tr>
                                            <td style="border-bottom: 1px solid #000;font-size: 12px;" colspan="2"><b>Address:</b> </td>
                                            <td style="border-bottom: 1px solid #000;font-size: 12px;"><b>Order No:</b> </td>
                                        </tr>
                                        <tr>
                                            <td style="border-bottom: 1px solid #000;font-size: 12px;" colspan="2"><b>Delivery Zone:</b> </td>
                                            <td style="border-bottom: 1px solid #000;font-size: 12px;"><b>Mob:</b> </td>
                                        </tr>
                                    </table>
                                    <table style="width:100%;padding:0px 2px 0px 2px; margin: auto;">
                                        <tr>
                                            <td style="height: 250px;vertical-align: top;border-left: 1px solid #000;border-right: 1px solid #000;">
                                                <table style="width:100%;padding:0px 2px 0px 2px; margin: auto; border-collapse: collapse;">
                                                    <tr>
                                                        <td style="width:8%;padding: 0px 0px 0px 5px;background: #CCC;font-weight: 700;font-size: 12px;">SL</td>
                                                        <td style="width: 42%;background: #CCC;font-weight: 700;font-size: 12px;text-align: center;">Description</td>
                                                        <td style="width: 10%;background: #CCC;font-weight: 700;font-size: 12px;text-align: center;">Qty</td>
                                                        <td style="width:16%;background: #CCC;font-weight: 700;font-size: 12px;text-align: right;">Price</td>
                                                        <td style="width:24%;background: #CCC;font-weight: 700;font-size: 12px;padding-right: 10px;text-align: right;">Total</td>
                                                    </tr>
                                                    <tr>
                                                        <td style="width:8%;border-bottom: 1px solid #000;padding: 0px 0px 0px 5px;font-size: 12px;"></td>
                                                        <td style="width: 42%;border-bottom: 1px solid #000;font-size: 12px;"></td>
                                                        <td style="width: 10%;border-bottom: 1px solid #000;font-size: 12px;text-align: center;"></td>
                                                        <td style="width:16%;border-bottom: 1px solid #000;font-size: 12px;text-align: right;"></td>
                                                        <td style="width:24%;border-bottom: 1px solid #000;font-size: 12px;padding-right: 10px;text-align: right;"></td>
                                                    </tr>
                                                </table>
                                            </td>
                                        </tr>
                                        <tr>
                                            <td>
                                                <table style="width:100%;padding:0px 2px 0px 2px;border-left: 1px solid #000;border-right: 1px solid #000; margin: auto; border-collapse: collapse;">
                                                    <tr>
                                                        <td style="width:76%; border-bottom: 1px solid #000;border-top: 1px solid #000;font-size: 12px;text-align: right;"><b>Subtotal</b></td>
                                                        <td style="width:24%; border-bottom: 1px solid #000;border-top: 1px solid #000;font-size: 12px;padding-right:10px;text-align: right;"><b></b></td>
                                                    </tr>
                                                    <tr>
                                                        <td style="width:76%; border-bottom: 1px solid #000;border-top: 1px solid #000;font-size: 12px;text-align: right;"><b>Total Discount</b></td>
                                                        <td style="width:24%; border-bottom: 1px solid #000;border-top: 1px solid #000;font-size: 12px;padding-right:10px;text-align: right;"><b></b></td>
                                                    </tr>
                                                    <tr>
                                                        <td style="width:76%; border-bottom: 1px solid #000;border-top: 1px solid #000;font-size: 12px;text-align: right;"><b>Delivery Cost</b></td>
                                                        <td style="width:24%; border-bottom: 1px solid #000;border-top: 1px solid #000;font-size: 12px;padding-right:10px;text-align: right;"><b></b></td>
                                                    </tr>
                                                    <tr>
                                                        <td style="width:76%; border-bottom: 1px solid #000;border-top: 1px solid #000;font-size: 12px;text-align: right;"><b>Total</b></td>
                                                        <td style="width:24%; border-bottom: 1px solid #000;border-top: 1px solid #000;font-size: 12px;padding-right:10px;text-align: right;"><b></b></td>
                                                    </tr>
                                                    <tr>
                                                        <td colspan="2" style="font-size: 12px;border-bottom: 1px solid #000;">
                                                            <b>Amount In Word</b>
                                                        </td>
                                                    </tr>
                                                </table>
                                            </td>
                                        </tr>
                                    </table>
                                    <p style="text-align:center">
                                        <br>
                                        This is a system genereted invoice.<br>Powered by ABSoft-BD.com
                                    </p>
                                </td>
                                <td style="width:33.3%;padding: 0px 10px;border-left:1px solid #DDD;border-right:1px solid #DDD;">
                                    <table style="width:100%; border-collapse: collapse;">
                                        <tr>
                                            <td style="width:33.3%">&nbsp;</td>
                                            <td style="width:33.3%" align="center"><span style="background:#333;color:#FFF;padding: 2px 5px;border-radius: 5px;">BILL</span></td>
                                            <td style="width:33.3%" align="right">Customer Copy</td>
                                        </tr>
                                        <tr>
                                            <td colspan="3" align="center">
                                                <img src="<?= base_url('attachments/'. SHOP_DIR .'/site_logo/' . $sitelogo) ?>" style="max-width: 300px;max-height:100px;" alt="<?= $_SERVER['HTTP_HOST'] ?>">
                                            </td>
                                        </tr>
                                        <tr>
                                            <td colspan="3" align="center" style="border-bottom: 1px solid #000;">
                                                <?= $footerContactPhone; ?><br>
                                                <?= base_url(); ?><br>
                                                <i><b><?= $footerContactAddr; ?></b></i>
                                            </td>
                                        </tr>
                                        <tr>
                                            <td style="border-bottom: 1px solid #000;font-size: 12px;" colspan="2"><b>Name:</b> </td>
                                            <td style="border-bottom: 1px solid #000;font-size: 12px;"></td>
                                        </tr>
                                        <tr>
                                            <td style="border-bottom: 1px solid #000;font-size: 12px;" colspan="2"><b>Address:</b> </td>
                                            <td style="border-bottom: 1px solid #000;font-size: 12px;"><b>Order No:</b> </td>
                                        </tr>
                                        <tr>
                                            <td style="border-bottom: 1px solid #000;font-size: 12px;" colspan="2"><b>Delivery Zone:</b> </td>
                                            <td style="border-bottom: 1px solid #000;font-size: 12px;"><b>Mob:</b> </td>
                                        </tr>
                                    </table>
                                    <table style="width:100%;padding:0px 2px 0px 2px; margin: auto;">
                                        <tr>
                                            <td style="height: 250px;vertical-align: top;border-left: 1px solid #000;border-right: 1px solid #000;">
                                                <table style="width:100%;padding:0px 2px 0px 2px; margin: auto; border-collapse: collapse;">
                                                    <tr>
                                                        <td style="width:8%;padding: 0px 0px 0px 5px;background: #CCC;font-weight: 700;font-size: 12px;">SL</td>
                                                        <td style="width: 42%;background: #CCC;font-weight: 700;font-size: 12px;text-align: center;">Description</td>
                                                        <td style="width: 10%;background: #CCC;font-weight: 700;font-size: 12px;text-align: center;">Qty</td>
                                                        <td style="width:16%;background: #CCC;font-weight: 700;font-size: 12px;text-align: right;">Price</td>
                                                        <td style="width:24%;background: #CCC;font-weight: 700;font-size: 12px;padding-right: 10px;text-align: right;">Total</td>
                                                    </tr>
                                                    <tr>
                                                        <td style="width:8%;border-bottom: 1px solid #000;padding: 0px 0px 0px 5px;font-size: 12px;"></td>
                                                        <td style="width: 42%;border-bottom: 1px solid #000;font-size: 12px;"></td>
                                                        <td style="width: 10%;border-bottom: 1px solid #000;font-size: 12px;text-align: center;"></td>
                                                        <td style="width:16%;border-bottom: 1px solid #000;font-size: 12px;text-align: right;"></td>
                                                        <td style="width:24%;border-bottom: 1px solid #000;font-size: 12px;padding-right: 10px;text-align: right;"></td>
                                                    </tr>
                                                </table>
                                            </td>
                                        </tr>
                                        <tr>
                                            <td>
                                                <table style="width:100%;padding:0px 2px 0px 2px;border-left: 1px solid #000;border-right: 1px solid #000; margin: auto; border-collapse: collapse;">
                                                    <tr>
                                                        <td style="width:76%; border-bottom: 1px solid #000;border-top: 1px solid #000;font-size: 12px;text-align: right;"><b>Subtotal</b></td>
                                                        <td style="width:24%; border-bottom: 1px solid #000;border-top: 1px solid #000;font-size: 12px;padding-right:10px;text-align: right;"><b></b></td>
                                                    </tr>
                                                    <tr>
                                                        <td style="width:76%; border-bottom: 1px solid #000;border-top: 1px solid #000;font-size: 12px;text-align: right;"><b>Total Discount</b></td>
                                                        <td style="width:24%; border-bottom: 1px solid #000;border-top: 1px solid #000;font-size: 12px;padding-right:10px;text-align: right;"><b></b></td>
                                                    </tr>
                                                    <tr>
                                                        <td style="width:76%; border-bottom: 1px solid #000;border-top: 1px solid #000;font-size: 12px;text-align: right;"><b>Delivery Cost</b></td>
                                                        <td style="width:24%; border-bottom: 1px solid #000;border-top: 1px solid #000;font-size: 12px;padding-right:10px;text-align: right;"><b></b></td>
                                                    </tr>
                                                    <tr>
                                                        <td style="width:76%; border-bottom: 1px solid #000;border-top: 1px solid #000;font-size: 12px;text-align: right;"><b>Total</b></td>
                                                        <td style="width:24%; border-bottom: 1px solid #000;border-top: 1px solid #000;font-size: 12px;padding-right:10px;text-align: right;"><b></b></td>
                                                    </tr>
                                                    <tr>
                                                        <td colspan="2" style="font-size: 12px;border-bottom: 1px solid #000;">
                                                            <b>Amount In Word</b>: 
                                                        </td>
                                                    </tr>
                                                </table>
                                            </td>
                                        </tr>
                                    </table>
                                    <p style="text-align:center">
                                        <br>
                                        This is a system genereted invoice.<br>Powered by ABSoft-BD.com
                                    </p>
                                </td>
								<td style="width:33.3%;padding: 0px 10px;vertical-align:top;">
                                    <table style="width:100%; border-collapse: collapse;">
                                        <tr>
                                            <td style="width:33.3%">&nbsp;</td>
                                            <td style="width:33.3%" align="center"><span style="background:#333;color:#FFF;padding: 2px 5px;border-radius: 5px;">BILL</span></td>
                                            <td style="width:33.3%" align="right">Customer Copy</td>
                                        </tr>
                                        <tr>
                                            <td colspan="3" align="center">
                                                <img src="<?= base_url('attachments/'. SHOP_DIR .'/site_logo/' . $sitelogo) ?>" style="max-width: 300px;max-height:100px;" alt="<?= $_SERVER['HTTP_HOST'] ?>">
                                            </td>
                                        </tr>
                                        <tr>
                                            <td colspan="3" align="center" style="border-bottom: 1px solid #000;">
                                                <?= $footerContactPhone; ?><br>
                                                <?= base_url(); ?><br>
                                                <i><b><?= $footerContactAddr; ?></b></i>
                                            </td>
                                        </tr>
                                        <tr>
                                            <td style="border-bottom: 1px solid #000;font-size: 12px;" colspan="2"><b>Name:</b> </td>
                                            <td style="border-bottom: 1px solid #000;font-size: 12px;"></td>
                                        </tr>
                                        <tr>
                                            <td style="border-bottom: 1px solid #000;font-size: 12px;" colspan="2"><b>Address:</b> </td>
                                            <td style="border-bottom: 1px solid #000;font-size: 12px;"><b>Order No:</b> </td>
                                        </tr>
                                        <tr>
                                            <td style="border-bottom: 1px solid #000;font-size: 12px;" colspan="2"><b>Delivery Zone:</b> </td>
                                            <td style="border-bottom: 1px solid #000;font-size: 12px;"><b>Mob:</b> </td>
                                        </tr>
                                    </table>
                                    <table style="width:100%;padding:0px 2px 0px 2px; margin: auto;">
                                        <tr>
                                            <td style="height: 250px;vertical-align: top;border-left: 1px solid #000;border-right: 1px solid #000;">
                                                <table style="width:100%;padding:0px 2px 0px 2px; margin: auto; border-collapse: collapse;">
                                                    <tr>
                                                        <td style="width:8%;padding: 0px 0px 0px 5px;background: #CCC;font-weight: 700;font-size: 12px;">SL</td>
                                                        <td style="width: 42%;background: #CCC;font-weight: 700;font-size: 12px;text-align: center;">Description</td>
                                                        <td style="width: 10%;background: #CCC;font-weight: 700;font-size: 12px;text-align: center;">Qty</td>
                                                        <td style="width:16%;background: #CCC;font-weight: 700;font-size: 12px;text-align: right;">Price</td>
                                                        <td style="width:24%;background: #CCC;font-weight: 700;font-size: 12px;padding-right: 10px;text-align: right;">Total</td>
                                                    </tr>
                                                    <tr>
                                                        <td style="width:8%;border-bottom: 1px solid #000;padding: 0px 0px 0px 5px;font-size: 12px;"></td>
                                                        <td style="width: 42%;border-bottom: 1px solid #000;font-size: 12px;"></td>
                                                        <td style="width: 10%;border-bottom: 1px solid #000;font-size: 12px;text-align: center;"></td>
                                                        <td style="width:16%;border-bottom: 1px solid #000;font-size: 12px;text-align: right;"></td>
                                                        <td style="width:24%;border-bottom: 1px solid #000;font-size: 12px;padding-right: 10px;text-align: right;"></td>
                                                    </tr>
                                                </table>
                                            </td>
                                        </tr>
                                        <tr>
                                            <td>
                                                <table style="width:100%;padding:0px 2px 0px 2px;border-left: 1px solid #000;border-right: 1px solid #000; margin: auto; border-collapse: collapse;">
                                                    <tr>
                                                        <td style="width:76%; border-bottom: 1px solid #000;border-top: 1px solid #000;font-size: 12px;text-align: right;"><b>Subtotal</b></td>
                                                        <td style="width:24%; border-bottom: 1px solid #000;border-top: 1px solid #000;font-size: 12px;padding-right:10px;text-align: right;"><b></b></td>
                                                    </tr>
                                                    <tr>
                                                        <td style="width:76%; border-bottom: 1px solid #000;border-top: 1px solid #000;font-size: 12px;text-align: right;"><b>Total Discount</b></td>
                                                        <td style="width:24%; border-bottom: 1px solid #000;border-top: 1px solid #000;font-size: 12px;padding-right:10px;text-align: right;"><b></b></td>
                                                    </tr>
                                                    <tr>
                                                        <td style="width:76%; border-bottom: 1px solid #000;border-top: 1px solid #000;font-size: 12px;text-align: right;"><b>Delivery Cost</b></td>
                                                        <td style="width:24%; border-bottom: 1px solid #000;border-top: 1px solid #000;font-size: 12px;padding-right:10px;text-align: right;"><b></b></td>
                                                    </tr>
                                                    <tr>
                                                        <td style="width:76%; border-bottom: 1px solid #000;border-top: 1px solid #000;font-size: 12px;text-align: right;"><b>Total</b></td>
                                                        <td style="width:24%; border-bottom: 1px solid #000;border-top: 1px solid #000;font-size: 12px;padding-right:10px;text-align: right;"><b></b></td>
                                                    </tr>
                                                    <tr>
                                                        <td colspan="2" style="font-size: 12px;border-bottom: 1px solid #000;">
                                                            <b>Amount In Word</b>
                                                        </td>
                                                    </tr>
                                                </table>
                                            </td>
                                        </tr>
                                    </table>
                                    <p style="text-align:center">
                                        <br>
                                        This is a system genereted invoice.<br>Powered by ABSoft-BD.com
                                    </p>
                                </td>
                            </tr>
                        </tbody>
                    </table>
                </div>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-success" onclick="printDiv('blank-bill')">Print</button>
                <button type="button" class="btn btn-default" data-dismiss="modal">Close</button>
            </div>
        </div>
    </div>
</div>
<script src="<?= base_url('assets/js/bootstrap-toggle.min.js') ?>"></script>
<script src="<?= base_url('assets/js/bootstrap-datepicker.min.js') ?>"></script>
<script>
    $('.datepicker').datepicker({
        format: "yyyy-mm-dd",
        todayHighlight: true
    });
</script>