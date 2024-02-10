<?php $tabindex= 1; ?>
<h1><button class="btn btn-default" onclick="javascript:location.href='<?= site_url("admin/purchase"); ?>'"><i class="fa fa-arrow-left"></i></button> <?= $description; ?></h1>
<hr>
    <div class="row">
        <div class="col-md-8 col-sm-8 col-xs-12">
            <div id="invoice" class="box box-success table-responsive">
                <?php if($order['order_type'] == 'purchase'){ ?>
                <?= form_open('admin/purchase/return_item/'.$order['id'], array('id'=>'return_item_form')); ?>
                <?php } ?>
                    <table width="100%">
                        <tr>
                            <td>
                                <table width="100%">
                                    <tr>
                                        <td style="vertical-align: top;padding:10px;">
                                            <img src="<?= base_url('attachments/'. SHOP_DIR .'/site_logo/' . $sitelogo) ?>" alt="<?= $_SERVER['HTTP_HOST'] ?> Logo" style="max-width:200px;padding: 10px;">
                                            <h4 style="color:#F00"><b><?php
                                            switch($order['order_type']){
                                                case 'purchase':
                                                    echo 'Purchase Invoice'; break; 
                                                case 'purchase_void' :
                                                    echo 'Invoice Deleted'; break;
                                                case 'purchase_return':
                                                    echo 'Purchase Return'; break;
                                                default:
                                                    $order['order_type']; break;
                                            } ?></b></h4>
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
                                        <td style="border-top: 1px solid;padding:5px;font-weight: 700;"><?= $supplier_info->name; ?></td>
                                        <td style="border-top: 1px solid;width:50px; padding:5px;">Date:</td>
                                        <td style="border-top: 1px solid;width:120px; padding:5px 10px;font-weight: 700;text-align:right;"><?= $order['date']; ?></td>
                                    </tr>
                                    <tr>
                                        <td style="border-top: 1px solid;width:50px; padding:5px 10px;">Address:</td>
                                        <td style="border-top: 1px solid;padding:5px;font-weight: 700;"><?= $supplier_info->url; ?></td>
                                        <td style="border-top: 1px solid;width:50px; padding:5px;">Mobile:</td>
                                        <td style="border-top: 1px solid;width:120px; padding:5px 10px;font-weight: 700;text-align:right;"><?= $supplier_info->mobile; ?></td>
                                    </tr>
                                </table>
                            </td>
                        </tr>
                        <tr>
                            <td>
                                <table>
                                    <thead>
                                        <tr style="background:#EEE;font-weight:700;">
                                            <td style="border-top: 1px solid;border-bottom: 1px solid;width: 2%;padding: 5px 10px;"><input type="checkbox" checked id="checkedall"></td>
                                            <td style="border-top: 1px solid;border-bottom: 1px solid;width: 2%;padding: 5px 10px;">#</td>
                                            <td style="border-top: 1px solid;border-bottom: 1px solid;width: 23%;padding: 5px;">Item Name</td>
                                            <td style="border-top: 1px solid;border-bottom: 1px solid;width: 40%;padding: 5px;">Description</td>
                                            <td style="border-top: 1px solid;border-bottom: 1px solid;width: 8%;padding: 5px;text-align:center;">Qty</td>
                                            <td style="border-top: 1px solid;border-bottom: 1px solid;width: 10%;padding: 5px;text-align:center;">Price</td>
                                            <td style="border-top: 1px solid;border-bottom: 1px solid;width: 12%;padding: 5px 10px;text-align:right;">Total</td>
                                        </tr>
                                    </thead>
                                    <tbody id="cart_contents">
                                        <?php $i=1; $tq=0; $st=0; foreach(unserialize($order['products']) as $line=>$item) { 
                                            $tq += $item['product_info']['quantity'];
                                            $st += $item['product_info']['total'];
                                        ?>
                                            <tr>
                                                <td style="border-bottom: 1px dashed;width:2%;text-align:center"><input type="checkbox" class="item_select" name="item_select<?= $line; ?>" value="<?= $item['product_info']['id']; ?>" checked></td>
                                                <td style="border-bottom: 1px dashed;width:2%;padding: 5px 10px;"><?= $i++; ?></td>
                                                <td style="border-bottom: 1px dashed;width:23%;padding: 5px;"><?= $item['product_info']['name']; ?></td>
                                                <td style="border-bottom: 1px dashed;width:40%;padding: 5px;"><?= $item['product_info']['description']; ?></td>
                                                <td style="border-bottom: 1px dashed;width:8%;padding: 5px;text-align:center;"><input type="text" <?= $order['order_type'] != 'purchase' ? 'disabled' : '' ?> name="qty<?= $line; ?>" value="<?= $item['product_info']['quantity']; ?>" size="5" style="text-align: center"><?= form_hidden('old_qty'.$line, $item['product_info']['quantity']); ?></td>
                                                <td style="border-bottom: 1px dashed;width:10%;padding: 5px;text-align:center;"><?= number_format($item['product_info']['price'], $nf); ?></td>
                                                <td style="border-bottom: 1px dashed;width:12%;padding: 5px 10px;text-align:right;"><?= number_format($item['product_info']['total'], $nf); ?></td>
                                            </tr>
                                        <?php }?>
                                        <?= form_hidden('total_line', ($i-1)); ?>
                                    </tbody>
                                    <tfoot>
                                            <tr>
                                                <td colspan="3" style="padding: 5px;">&nbsp;</td>
                                                <td style="text-align:center;padding: 5px;text-align:right;">Total Quantity</td>
                                                <td style="border-bottom: 1px dashed;text-align:center;padding: 5px;"><?= $tq; ?></td>
                                                <td style="border-bottom: 1px dashed;text-align:right;padding: 5px;">Sub Total</td>
                                                <td style="border-bottom: 1px dashed;text-align:right;padding: 5px 10px;font-weight:700;"><?= number_format($st, $nf); ?></td>
                                            </tr>
                                            <?php if($order['shipping_cost']>0): ?>
                                            <tr>
                                                <td colspan="4" style="padding: 5px;">&nbsp;</td>
                                                <td colspan="2" style="border-bottom: 1px dashed;text-align:right;padding: 5px;">Shipping Cost</td>
                                                <td style="border-bottom: 1px dashed;text-align:right;padding: 5px 10px;">(+)<?= number_format($order['shipping_cost'], $nf); ?></td>
                                            </tr>
                                            <?php endif; ?>
                                            <?php if($order['labour_cost']>0): ?>
                                            <tr>
                                                <td colspan="4" style="padding: 5px;">&nbsp;</td>
                                                <td colspan="2" style="border-bottom: 1px dashed;text-align:right;padding: 5px;">Labour Cost</td>
                                                <td style="border-bottom: 1px dashed;text-align:right;padding: 5px 10px;">(+)<?= number_format($order['labour_cost'], $nf); ?></td>
                                            </tr>
                                            <?php endif; ?>
											<?php if($order['carrying_cost']>0): ?>
                                            <tr>
                                                <td colspan="4" style="padding: 5px;">&nbsp;</td>
                                                <td colspan="2" style="border-bottom: 1px dashed;text-align:right;padding: 5px;">Carrying Cost</td>
                                                <td style="border-bottom: 1px dashed;text-align:right;padding: 5px 10px;">(+)<?= number_format($order['carrying_cost'], $nf); ?></td>
                                            </tr>
                                            <?php endif; ?>
											<?php if($order['other_cost']>0): ?>
                                            <tr>
                                                <td colspan="4" style="padding: 5px;">&nbsp;</td>
                                                <td colspan="2" style="border-bottom: 1px dashed;text-align:right;padding: 5px;">Other Cost</td>
                                                <td style="border-bottom: 1px dashed;text-align:right;padding: 5px 10px;">(+)<?= number_format($order['other_cost'], $nf); ?></td>
                                            </tr>
                                            <?php endif; ?>
                                            <?php if($order['referrer'] == "POS" && $order['discount_code']>0): ?>
                                            <tr>
                                                <td colspan="4" style="padding: 5px;">&nbsp;</td>
                                                <td colspan="2" style="border-bottom: 1px dashed;text-align:right;padding: 5px;">Discount</td>
                                                <td style="border-bottom: 1px dashed;text-align:right;padding: 5px 10px;">(-)<?= number_format($order['discount_code'], $nf); ?></td>
                                            </tr>
                                            <?php endif; ?>
                                            <?php if($order['referrer'] == "POS"): 
                                                foreach(unserialize($order['payment_type']) as $pay){
                                                    if($pay['payment_title'] == 'Advance' || $pay['payment_title'] == 'Collection'): ?>
                                                    <tr>
                                                        <td colspan="4" style="padding: 5px;">&nbsp;</td>
                                                        <td colspan="2" style="border-bottom: 1px dashed;text-align:right;padding: 5px;"><?= $pay['payment_title']; ?> Receive</td>
                                                        <td style="border-bottom: 1px dashed;text-align:right;padding: 5px 10px;font-weight:700;">(+)<?= number_format($pay['payment_amount'], $nf); ?></td>
                                                    </tr><?php
                                                    $order['total'] += $pay['payment_amount'];
                                                    endif;
                                                }
                                            endif; ?>
                                            <?php if($order['total']>0): ?>
                                            <tr>
                                                <td colspan="4" style="padding: 5px;">&nbsp;</td>
                                                <td colspan="2" style="border-bottom: 1px dashed;text-align:right;padding: 5px;">Total</td>
                                                <td style="border-bottom: 1px dashed;text-align:right;padding: 5px 10px;font-weight:700;"><?= number_format($order['total'], $nf); ?></td>
                                            </tr>
                                            <?php endif; ?>
                                            <?php if($order['referrer'] == "POS"): 
                                                foreach(unserialize($order['payment_type']) as $pay){
                                                    if($pay['payment_title'] == 'Advance' || $pay['payment_title'] == 'Collection') continue; ?>
                                                    <tr>
                                                        <td colspan="4" style="padding: 5px;">&nbsp;</td>
                                                        <td colspan="2" style="border-bottom: 1px dashed;text-align:right;padding: 5px;"><?= $pay['payment_title']; ?><?= $pay['payment_title'] != "Change Amount" ? " Payment" : ""; ?></td>
                                                        <td style="border-bottom: 1px dashed;text-align:right;padding: 5px 10px;font-weight:700;"><?= number_format($pay['payment_amount'], $nf); ?></td>
                                                    </tr><?php
                                                }
                                            endif; ?>
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
                                <table style="width:100%; margin-top:10px;">
                                    <tr>
                                        <td>
                                            <input name="return_note" value="<?= $order['order_type'] == 'purchase_return' ? @$order['note'] : ''; ?>" type="text" <?= $order['order_type'] != 'purchase' ? 'disabled' : '' ?> class="form-control" placeholder="Purchase Return Note">
                                        </td>
                                        <td>
                                            <button type="submit" <?= $order['order_type'] != 'purchase' ? 'disabled' : '' ?> name="return_btn" class="btn btn-info btn-block btn-flat" data-form-id="return_item_form" data-msg="Are you sure want to return? This action cannot be undone.">Return</button>
                                        </td>
                                    </tr>
                                    <tr><td colspan="2">&nbsp;</td></tr>
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
                <?php if($order['order_type'] == 'purchase'){ ?>
                <?= form_close(); ?>
                <?php } ?>
            </div>
        </div>
        <div class="col-md-4 col-sm-4 col-xs-12">
            <button class="btn btn-success btn-block btn-flat" onclick="printDiv('invoice')">Print Invoice</button><br>
            <a class="btn btn-primary btn-block btn-flat" href="<?= site_url("admin/purchase"); ?>">Go to Purchase Register</a><br>
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
<link rel="stylesheet" href="<?= base_url('assets/js/jquery-ui.min.css') ?>">
<script src="<?= base_url('assets/js/jquery-ui.min.js') ?>"></script>
<script>
    $(document).ready(function() {
        $('#checkedall').change(function() {
            console.log($(this).is(":checked"));
            $(".item_select").attr("checked", $(this).is(":checked"));
            $(".item_select").prop("checked", $(this).is(":checked"));
        });
        $(".item_select").click(function() {
            var all = true;
            $.each($(".item_select"), function(){
                if(! $(this).is(":checked")) {
                    $('#checkedall').attr("checked", false);
                    $('#checkedall').prop("checked", false);
                    all = false;
                    return false;
                }
            })
            $('#checkedall').attr("checked", all);
            $('#checkedall').prop("checked", all);
        });
    });
</script>