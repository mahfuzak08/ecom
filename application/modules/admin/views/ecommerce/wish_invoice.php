<?php $tabindex= 1; ?>
<h1><button class="btn btn-default" onclick="javascript:location.href='<?= site_url("admin/sale"); ?>'"><i class="fa fa-arrow-left"></i></button> <?= $description; ?></h1>
<hr>
    <div class="row">
        <div class="col-md-8 col-sm-8 col-xs-12">
            <div id="invoice" class="box box-success table-responsive">
                <table width="100%">
                    <tr>
                        <td>
                            <table width="100%">
                                <tr>
                                    <td style="vertical-align: top;padding:10px;">
                                        <img src="<?= base_url('attachments/'. SHOP_DIR .'/site_logo/' . $sitelogo) ?>" alt="<?= $_SERVER['HTTP_HOST'] ?> Logo" style="max-width:200px;padding: 10px;">
                                        <h4><b><?= $description; ?></b></h4>
                                    </td>
                                    <td style="vertical-align: top;padding:10px;">
                                        <b style="font-size:20px;"><?= $companyName; ?></b><br>
                                        <?= $footerContactPhone; ?><br>
                                        <?= base_url(); ?><br>
                                        <i><b><?= $footerContactAddr; ?></b></i>
                                    </td>
                                    <td style="vertical-align: top;text-align:right;padding:10px;">
                                        <img src="data:image/png;base64,<?= $barcode; ?>">
                                    </td>
                                </tr>
                            </table>
                        </td>
                    </tr>
                    <tr>
                        <td>
                            <table width="100%">
                                <tr>
                                    <td style="border-top: 1px solid;width:50px; padding:5px 10px;">Name:</td>
                                    <td style="border-top: 1px solid;padding:5px;font-weight: 700;"><?= $customer_info->name; ?></td>
                                    <td style="border-top: 1px solid;width:50px; padding:5px;">Date:</td>
                                    <td style="border-top: 1px solid;width:120px; padding:5px 10px;font-weight: 700;text-align:right;"><?= $order['date']; ?></td>
                                </tr>
                                <tr>
                                    <td style="border-top: 1px solid;width:50px; padding:5px 10px;">Address:</td>
                                    <td style="border-top: 1px solid;padding:5px;font-weight: 700;"><?= $customer_info->address; ?></td>
                                    <td style="border-top: 1px solid;width:50px; padding:5px;">Mobile:</td>
                                    <td style="border-top: 1px solid;width:120px; padding:5px 10px;font-weight: 700;text-align:right;"><?= $customer_info->phone; ?></td>
                                </tr>
                            </table>
                        </td>
                    </tr>
                    <tr>
                        <td>
                            <table>
                                <thead>
                                    <tr style="background:#EEE;font-weight:700;">
                                        <td style="border-top: 1px solid;border-bottom: 1px solid;width: 2%;padding: 5px 10px;">#</td>
                                        <td style="border-top: 1px solid;border-bottom: 1px solid;width: 20%;padding: 5px;">Item Name</td>
                                        <td style="border-top: 1px solid;border-bottom: 1px solid;width: 25%;padding: 5px;">Description</td>
                                        <td style="border-top: 1px solid;border-bottom: 1px solid;width: 15%;padding: 5px;text-align:center;">Original Price</td>
                                        <td style="border-top: 1px solid;border-bottom: 1px solid;width: 8%;padding: 5px;text-align:center;">Qty</td>
                                        <td style="border-top: 1px solid;border-bottom: 1px solid;width: 20%;padding: 5px;text-align:center;">Discounted Price</td>
                                        <td style="border-top: 1px solid;border-bottom: 1px solid;width: 10%;padding: 5px 10px;text-align:right;">Total</td>
                                    </tr>
                                </thead>
                                <tbody id="cart_contents">
                                    <?php $i=1; $op=0; $tq=0; $st=0; foreach(unserialize($order['products']) as $line=>$item) { 
                                        $tq += @$item['product_info']['quantity'];
                                        $st += @$item['product_info']['total'];
                                        $op += $item['product_info']['price'];
                                    ?>
                                        <tr>
                                            <td style="border-bottom: 1px dashed;width:2%;padding: 5px 10px;"><?= $i++; ?></td>
                                            <td style="border-bottom: 1px dashed;width:20%;padding: 5px;"><?= @$item['product_info']['name']; ?></td>
                                            <td style="border-bottom: 1px dashed;width:25%;padding: 5px;"><?= @$item['product_info']['description']; ?></td>
                                            <td style="border-bottom: 1px dashed;width:15%;padding: 5px;text-align:center;"><?= number_format(@$item['product_info']['price'], $nf); ?></td>
                                            <td style="border-bottom: 1px dashed;width:8%;padding: 5px;text-align:center;"><?= @$item['product_info']['quantity']; ?></td>
                                            <td style="border-bottom: 1px dashed;width:20%;padding: 5px;text-align:center;"><?= number_format(@$item['product_info']['wish_price'], $nf); ?></td>
                                            <td style="border-bottom: 1px dashed;width:10%;padding: 5px 10px;text-align:right;"><?= number_format(@$item['product_info']['total'], $nf); ?></td>
                                        </tr>
                                    <?php }?>
                                </tbody>
                                <tfoot>
                                        <tr>
                                            <td colspan="3" style="padding: 5px;">&nbsp;</td>
                                            <td style="text-align:center;padding: 5px;text-align:right;">Total Quantity</td>
                                            <td style="border-bottom: 1px dashed;text-align:center;padding: 5px;"><?= $tq; ?></td>
                                            <td style="border-bottom: 1px dashed;text-align:right;padding: 5px;">Sub Total</td>
                                            <td style="border-bottom: 1px dashed;text-align:right;padding: 5px 10px;font-weight:700;"><?= number_format($st, $nf); ?></td>
                                        </tr>
                                        <?php if($order['referrer'] == "Wish2Order"): ?>
                                        <tr>
                                            <td colspan="4" style="padding: 5px;">&nbsp;</td>
                                            <td colspan="2" style="border-bottom: 1px dashed;text-align:right;padding: 5px;">Discount</td>
                                            <td style="border-bottom: 1px dashed;text-align:right;padding: 5px 10px;">(-)<?= number_format($st-$op, $nf); ?></td>
                                        </tr>
                                        <?php endif; ?>
                                        <?php if($order['total']>0): ?>
                                        <tr>
                                            <td colspan="4" style="padding: 5px;">&nbsp;</td>
                                            <td colspan="2" style="border-bottom: 1px dashed;text-align:right;padding: 5px;">Total</td>
                                            <td style="border-bottom: 1px dashed;text-align:right;padding: 5px 10px;font-weight:700;"><?= number_format($order['total'], $nf); ?></td>
                                        </tr>
                                        <?php endif; ?>
                                        <?php if($order['asof_date_due']>0): ?>
                                        <tr>
                                            <td colspan="4" style="padding: 5px;">&nbsp;</td>
                                            <td colspan="2" style="border-bottom: 1px dashed;text-align:right;padding: 5px;font-weight:700;font-size:15px;color:#F00;">Total Dues</td>
                                            <td style="border-bottom: 1px dashed;text-align:right;padding: 5px 10px;font-weight:700;font-size:15px;color:#F00;"><?= number_format($order['asof_date_due'], $nf); ?></td>
                                        </tr>
                                        <?php endif; ?>
                                        <?php if($order['asof_date_due']<0): ?>
                                        <tr>
                                            <td colspan="4" style="padding: 5px;">&nbsp;</td>
                                            <td colspan="2" style="border-bottom: 1px dashed;text-align:right;padding: 5px;font-weight:700;font-size:15px;color:#F00;">Total Advance</td>
                                            <td style="border-bottom: 1px dashed;text-align:right;padding: 5px 10px;font-weight:700;font-size:15px;color:#F00;"><?= number_format($order['asof_date_due'] * -1, $nf); ?></td>
                                        </tr>
                                        <?php endif; ?>
                                </tfoot>
                            </table>
                        </td>
                    </tr>
                    <tr>
                        <td align="center">
                        <table style="width:100%; margin-top:100px; background:#F9F9F9;">
                            <tr>
                                <td style="border-top:1px solid;padding: 5px 10px;"><img src="<?= base_url("assets/imgs/ablogo.png"); ?>" width="70px"></td>
                                <td style="border-top:1px solid;padding: 5px; text-align:center;">This is a software generated invoice.<br>Web: www.absoft-bd.com</td>
                                <td style="border-top:1px solid;padding: 5px 10px; text-align: right;"><b>Powred By ABSoft-BD.COM<br>Mob: 8801719455709</b></td>
                            </tr>
                        </table>
                        </td>
                    </tr>
                </table>
            </div>
        </div>
        <div class="col-md-4 col-sm-4 col-xs-12">
            <button class="btn btn-success btn-block btn-flat" onclick="printDiv('invoice')">Print Invoice</button><br>
            <a class="btn btn-primary btn-block btn-flat" href="<?= site_url("admin/wishs"); ?>">Go to Bit Lists</a><br>
            <!-- <button class="btn btn-info btn-block btn-flat">SMS Invoice</button><br> -->
            <!-- <button class="btn btn-primary btn-block btn-flat">Email Invoice</button><br> -->
            <!-- <button class="btn btn-default btn-block btn-flat" onclick="toggle_div('#inv_settings')">Invoice Settings</button><br>
            <div class="row" style="display:none;" id="inv_settings">
                <div class="col-md-12 col-xs-12">
                    Settings Start
                </div>
            </div> -->
        </div>
    </div>