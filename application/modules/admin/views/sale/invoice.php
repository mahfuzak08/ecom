<?php $tabindex= 1; ?>
<h1><button class="btn btn-default" onclick="javascript:location.href='<?= site_url("admin/sale"); ?>'"><i class="fa fa-arrow-left"></i></button> <?= $description; ?></h1>
<hr>
    <div class="row">
        <div class="col-md-8 col-sm-8 col-xs-12">
            <?php if($page_width == "100"){ ?>
            <div id="invoice" class="box box-success table-responsive">
                <table width="100%">
                    <tr>
                        <td>
                            <table width="100%">
                                <tr>
                                    <td style="vertical-align: top;padding:10px;">
                                        <img src="<?= base_url('attachments/'. SHOP_DIR .'/site_logo/' . $sitelogo) ?>" alt="<?= $_SERVER['HTTP_HOST'] ?> Logo" style="max-width:200px;padding: 5px;">
                                    </td>
                                    <td style="vertical-align: top;padding:10px; text-align: center;">
                                        <b style="font-size:30px;"><?= $companyName; ?></b><br>
                                        <b style="font-size:14px;">
                                            <?php if(! empty($inv_addLine_1)) { 
                                            echo $inv_addLine_1;
                                            } else { ?>
                                            Mobile: <?= $footerContactPhone; ?>
                                            <?php } ?>
                                        </b>
                                        <br>
                                        <i><b>
                                        <?php if(! empty($inv_addLine_2)) {
                                            echo $inv_addLine_2;
                                            echo "<br>".$inv_addLine_3;
                                            echo "<br>".$inv_addLine_4;
                                        } else {
                                            echo $footerContactAddr;
                                        }?>
                                        </b></i>
                                        <?= $order['order_type'] == 'sale_return' ? '<h4>Sales Return</h4>' : ''; ?>
                                    </td>
                                    <td style="vertical-align: top;text-align:right;padding:10px;">
                                        <img src="data:image/png;base64,<?= $barcode; ?>">
                                        <div style="text-align:center">SL No: <?= $order['order_id']; ?></div>
                                        <p style="font-size: 24px;"><b><?= $description; ?></b></p>
                                        <?php if($order['note']): ?>
                                        <div style="text-align:center">Note: <?= $order['note']; ?></div>
                                        <?php endif; ?>
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
                                        <td style="border-top: 1px solid;border-bottom: 1px solid;width: 2%;padding: 5px 10px;">SL</td>
                                        <td style="border-top: 1px solid;border-bottom: 1px solid;width: <?= $invDesShow != 1 ? 23 : 63; ?>%;padding: 5px;">Item Name</td>
                                        <?php if($invDesShow != 1){ ?>
                                        <td style="border-top: 1px solid;border-bottom: 1px solid;width: 40%;padding: 5px;">Description</td>
                                        <?php } ?>
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
                                            <td style="border-bottom: 1px dashed;width:2%;padding: 5px 10px;"><?= $i++; ?></td>
                                            <td style="border-bottom: 1px dashed;width:<?= $invDesShow != 1 ? 23 : 63; ?>%;padding: 5px;">
                                                <?php 
                                                echo $item['product_info']['name'];
                                                if(isset($item['product_info']['size']) && $item['product_info']['size'] != '' && $item['product_info']['size'] != 'N' && $item['product_info']['size'] != '0')
                                                    echo "<br>Size: ".$item['product_info']['size'];
                                                ?>
                                            </td>
                                            <?php if($invDesShow != 1){?>
                                            <td style="border-bottom: 1px dashed;width:40%;padding: 5px;">
                                                <?php if($details_img == 'img'){ ?>
                                                    <img src="<?= base_url('attachments/'. SHOP_DIR .'/shop_images/' . $item['product_info']['image']) ?>" alt="Product" style="width:100px; margin-right:10px;" class="img-responsive">
                                                <?php } else { ?>
                                                    <?= $item['product_info']['description']; ?>
                                                <?php } ?>
                                            </td>
                                            <?php } ?>
                                            <td style="border-bottom: 1px dashed;width:8%;padding: 5px;text-align:center;"><?= $item['product_info']['quantity']; ?></td>
                                            <td style="border-bottom: 1px dashed;width:10%;padding: 5px;text-align:center;"><?= number_format($item['product_info']['price'], $nf); ?></td>
                                            <td style="border-bottom: 1px dashed;width:12%;padding: 5px 10px;text-align:right;"><?= number_format($item['product_info']['total'], $nf); ?></td>
                                        </tr>
                                    <?php }?>
                                </tbody>
                                <!--<tfoot>-->
                                        <tr>
                                            <td colspan="<?= $invDesShow != 1 ? 2 : 1; ?>" style="padding: 5px;">&nbsp;</td>
                                            <td style="text-align:center;padding: 5px;text-align:right;">Total Quantity</td>
                                            <td style="border-bottom: 1px dashed;text-align:center;padding: 5px;"><?= $tq; ?></td>
                                            <td style="border-bottom: 1px dashed;text-align:right;padding: 5px;">Sub Total</td>
                                            <td style="border-bottom: 1px dashed;text-align:right;padding: 5px 10px;font-weight:700;"><?= number_format($st, $nf); ?></td>
                                        </tr>
                                        <?php if($order['shipping_cost']>0): ?>
                                        <tr>
                                            <td colspan="<?= $invDesShow != 1 ? 3 : 2; ?>" style="padding: 5px;">&nbsp;</td>
                                            <td colspan="2" style="border-bottom: 1px dashed;text-align:right;padding: 5px;">Shipping Cost</td>
                                            <td style="border-bottom: 1px dashed;text-align:right;padding: 5px 10px;">(+)<?= number_format($order['shipping_cost'], $nf); ?></td>
                                        </tr>
                                        <?php endif; ?>
                                        <?php if($order['labour_cost']>0): ?>
                                        <tr>
                                            <td colspan="<?= $invDesShow != 1 ? 3 : 2; ?>" style="padding: 5px;">&nbsp;</td>
                                            <td colspan="2" style="border-bottom: 1px dashed;text-align:right;padding: 5px;">Labour Cost</td>
                                            <td style="border-bottom: 1px dashed;text-align:right;padding: 5px 10px;">(+)<?= number_format($order['labour_cost'], $nf); ?></td>
                                        </tr>
                                        <?php endif; ?>
										<?php if($order['carrying_cost']>0): ?>
                                        <tr>
                                            <td colspan="<?= $invDesShow != 1 ? 3 : 2; ?>" style="padding: 5px;">&nbsp;</td>
                                            <td colspan="2" style="border-bottom: 1px dashed;text-align:right;padding: 5px;">Carrying Cost</td>
                                            <td style="border-bottom: 1px dashed;text-align:right;padding: 5px 10px;">(+)<?= number_format($order['carrying_cost'], $nf); ?></td>
                                        </tr>
                                        <?php endif; ?>
										<?php if($order['other_cost']>0): ?>
                                        <tr>
                                            <td colspan="<?= $invDesShow != 1 ? 3 : 2; ?>" style="padding: 5px;">&nbsp;</td>
                                            <td colspan="2" style="border-bottom: 1px dashed;text-align:right;padding: 5px;">Other Cost</td>
                                            <td style="border-bottom: 1px dashed;text-align:right;padding: 5px 10px;">(+)<?= number_format($order['other_cost'], $nf); ?></td>
                                        </tr>
                                        <?php endif; ?>
                                        <?php if($order['referrer'] == "POS" && $order['discount_code']>0): ?>
                                        <tr>
                                            <td colspan="<?= $invDesShow != 1 ? 3 : 2; ?>" style="padding: 5px;">&nbsp;</td>
                                            <td colspan="2" style="border-bottom: 1px dashed;text-align:right;padding: 5px;">Discount</td>
                                            <td style="border-bottom: 1px dashed;text-align:right;padding: 5px 10px;">(-)<?= number_format($order['discount_code'], $nf); ?></td>
                                        </tr>
                                        <?php endif; ?>
                                        <?php if($order['referrer'] == "POS"): 
                                            $p = unserialize($order['payment_type']);
                                            if($p):
                                            foreach($p as $pay){
                                                if($pay['payment_title'] == 'Advance' || $pay['payment_title'] == 'Collection'): ?>
                                                <tr>
                                                    <td colspan="<?= $invDesShow != 1 ? 3 : 2; ?>" style="padding: 5px;">&nbsp;</td>
                                                    <td colspan="2" style="border-bottom: 1px dashed;text-align:right;padding: 5px;"><?= $pay['payment_title']; ?> Receive</td>
                                                    <td style="border-bottom: 1px dashed;text-align:right;padding: 5px 10px;font-weight:700;">(+)<?= number_format($pay['payment_amount'], $nf); ?></td>
                                                </tr><?php
                                                $order['total'] += $pay['payment_amount'];
                                                endif;
                                            }
                                            endif;
                                        endif; ?>
                                        <?php if($order['total']>0): ?>
                                        <tr>
                                            <td>&nbsp;</td>
                                            <td colspan="<?= $invDesShow != 1 ? 2 : 1; ?>" style="padding: 5px; font-weight: bold;">In Word: <?= @$iw; ?> Taka Only.</td>
                                            <td colspan="2" style="border-bottom: 1px dashed;text-align:right;padding: 5px;">Total</td>
                                            <td style="border-bottom: 1px dashed;text-align:right;padding: 5px 10px;font-weight:700;"><?= number_format($order['total'], $nf); ?></td>
                                        </tr>
                                        <?php endif; ?>
                                        <?php if($order['referrer'] == "POS"): 
                                            if($p):
                                            foreach($p as $pay){
                                                // print_r($pay);
                                                if($pay['payment_title'] == 'Advance' || $pay['payment_title'] == 'Collection') continue; ?>
                                                <tr>
                                                    <td colspan="<?= $invDesShow != 1 ? 3 : 2; ?>" style="padding: 5px;">&nbsp;</td>
                                                    <td colspan="2" style="border-bottom: 1px dashed;text-align:right;padding: 5px;"><?= $pay['payment_title']; ?><?= $pay['payment_title'] != "Change Amount" ? " Payment" : ""; ?></td>
                                                    <td style="border-bottom: 1px dashed;text-align:right;padding: 5px 10px;font-weight:700;"><?= number_format($pay['payment_amount'], $nf); ?></td>
                                                </tr><?php
                                            }
                                            endif;
                                        endif; ?>
                                        <?php if($order['asof_date_due']>0): ?>
                                        <tr>
                                            <td colspan="<?= $invDesShow != 1 ? 3 : 2; ?>" style="padding: 5px;">&nbsp;</td>
                                            <td colspan="2" style="border-bottom: 1px dashed;text-align:right;padding: 5px;font-weight:700;font-size:15px;color:#F00;">Total Dues</td>
                                            <td style="border-bottom: 1px dashed;text-align:right;padding: 5px 10px;font-weight:700;font-size:15px;color:#F00;"><?= number_format($order['asof_date_due'], $nf); ?></td>
                                        </tr>
                                        <?php endif; ?>
                                        <?php if($order['asof_date_due']<0): ?>
                                        <tr>
                                            <td colspan="<?= $invDesShow != 1 ? 3 : 2; ?>" style="padding: 5px;">&nbsp;</td>
                                            <td colspan="2" style="border-bottom: 1px dashed;text-align:right;padding: 5px;font-weight:700;font-size:15px;color:#F00;">Total Advance</td>
                                            <td style="border-bottom: 1px dashed;text-align:right;padding: 5px 10px;font-weight:700;font-size:15px;color:#F00;"><?= number_format($order['asof_date_due'] * -1, $nf); ?></td>
                                        </tr>
                                        <?php endif; ?>
                                <!--</tfoot>-->
                            </table>
                        </td>
                    </tr>
                    <tr>
                        <td align="center">
                        <table style="width:100%; margin-top:100px; background:#F9F9F9;">
                            <tr>
                                <!--<td style="border-top:1px solid;padding: 5px 10px;"><img src="<?= base_url("assets/imgs/ablogo.png"); ?>" width="70px"></td>-->
                                <td style="border-top:1px solid;padding: 5px;"><b>Powred By ABSoft-BD.COM</b></td>
                                <td style="border-top:1px solid;padding: 5px 10px; text-align: right;"><b>Authorised by: <?= $companyName; ?></b></td>
                            </tr>
                        </table>
                        </td>
                    </tr>
                </table>
            </div>
            <?php } elseif($page_width == "50x2") { ?>
                <div id="invoice" class="box box-success table-responsive">
                <table width="48%" style="float:left">
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
                                        <b style="font-size:16px;">Mobile: <?= $footerContactPhone; ?></b><br>
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
                                            <td style="border-bottom: 1px dashed;width:2%;padding: 5px 10px;"><?= $i++; ?></td>
                                            <td style="border-bottom: 1px dashed;width:23%;padding: 5px;"><?= $item['product_info']['name']; ?></td>
                                            <td style="border-bottom: 1px dashed;width:40%;padding: 5px;"><?= $item['product_info']['description']; ?></td>
                                            <td style="border-bottom: 1px dashed;width:8%;padding: 5px;text-align:center;"><?= $item['product_info']['quantity']; ?></td>
                                            <td style="border-bottom: 1px dashed;width:10%;padding: 5px;text-align:center;"><?= number_format($item['product_info']['price'], $nf); ?></td>
                                            <td style="border-bottom: 1px dashed;width:12%;padding: 5px 10px;text-align:right;"><?= number_format($item['product_info']['total'], $nf); ?></td>
                                        </tr>
                                    <?php }?>
                                </tbody>
                                <tfoot>
                                        <tr>
                                            <td colspan="2" style="padding: 5px;">&nbsp;</td>
                                            <td style="text-align:center;padding: 5px;text-align:right;">Total Quantity</td>
                                            <td style="border-bottom: 1px dashed;text-align:center;padding: 5px;"><?= $tq; ?></td>
                                            <td style="border-bottom: 1px dashed;text-align:right;padding: 5px;">Sub Total</td>
                                            <td style="border-bottom: 1px dashed;text-align:right;padding: 5px 10px;font-weight:700;"><?= number_format($st, $nf); ?></td>
                                        </tr>
                                        <?php if($order['shipping_cost']>0): ?>
                                        <tr>
                                            <td colspan="3" style="padding: 5px;">&nbsp;</td>
                                            <td colspan="2" style="border-bottom: 1px dashed;text-align:right;padding: 5px;">Shipping Cost</td>
                                            <td style="border-bottom: 1px dashed;text-align:right;padding: 5px 10px;">(+)<?= number_format($order['shipping_cost'], $nf); ?></td>
                                        </tr>
                                        <?php endif; ?>
                                        <?php if($order['labour_cost']>0): ?>
                                        <tr>
                                            <td colspan="3" style="padding: 5px;">&nbsp;</td>
                                            <td colspan="2" style="border-bottom: 1px dashed;text-align:right;padding: 5px;">Labour Cost</td>
                                            <td style="border-bottom: 1px dashed;text-align:right;padding: 5px 10px;">(+)<?= number_format($order['labour_cost'], $nf); ?></td>
                                        </tr>
                                        <?php endif; ?>
										<?php if($order['carrying_cost']>0): ?>
                                        <tr>
                                            <td colspan="3" style="padding: 5px;">&nbsp;</td>
                                            <td colspan="2" style="border-bottom: 1px dashed;text-align:right;padding: 5px;">Carrying Cost</td>
                                            <td style="border-bottom: 1px dashed;text-align:right;padding: 5px 10px;">(+)<?= number_format($order['carrying_cost'], $nf); ?></td>
                                        </tr>
                                        <?php endif; ?>
										<?php if($order['other_cost']>0): ?>
                                        <tr>
                                            <td colspan="3" style="padding: 5px;">&nbsp;</td>
                                            <td colspan="2" style="border-bottom: 1px dashed;text-align:right;padding: 5px;">Other Cost</td>
                                            <td style="border-bottom: 1px dashed;text-align:right;padding: 5px 10px;">(+)<?= number_format($order['other_cost'], $nf); ?></td>
                                        </tr>
                                        <?php endif; ?>
                                        <?php if($order['referrer'] == "POS" && $order['discount_code']>0): ?>
                                        <tr>
                                            <td colspan="3" style="padding: 5px;">&nbsp;</td>
                                            <td colspan="2" style="border-bottom: 1px dashed;text-align:right;padding: 5px;">Discount</td>
                                            <td style="border-bottom: 1px dashed;text-align:right;padding: 5px 10px;">(-)<?= number_format($order['discount_code'], $nf); ?></td>
                                        </tr>
                                        <?php endif; ?>
                                        <?php if($order['referrer'] == "POS"): 
                                            $p = unserialize($order['payment_type']);
                                            if($p):
                                            foreach($p as $pay){
                                                if($pay['payment_title'] == 'Advance' || $pay['payment_title'] == 'Collection'): ?>
                                                <tr>
                                                    <td colspan="3" style="padding: 5px;">&nbsp;</td>
                                                    <td colspan="2" style="border-bottom: 1px dashed;text-align:right;padding: 5px;"><?= $pay['payment_title']; ?> Receive</td>
                                                    <td style="border-bottom: 1px dashed;text-align:right;padding: 5px 10px;font-weight:700;">(+)<?= number_format($pay['payment_amount'], $nf); ?></td>
                                                </tr><?php
                                                $order['total'] += $pay['payment_amount'];
                                                endif;
                                            }
                                            endif;
                                        endif; ?>
                                        <?php if($order['total']>0): ?>
                                        <tr>
                                            <td colspan="3" style="padding: 5px;">&nbsp;</td>
                                            <td colspan="2" style="border-bottom: 1px dashed;text-align:right;padding: 5px;">Total</td>
                                            <td style="border-bottom: 1px dashed;text-align:right;padding: 5px 10px;font-weight:700;"><?= number_format($order['total'], $nf); ?></td>
                                        </tr>
                                        <?php endif; ?>
                                        <?php if($order['referrer'] == "POS"): 
                                            if($p):
                                            foreach($p as $pay){
                                                if($pay['payment_title'] == 'Advance' || $pay['payment_title'] == 'Collection') continue; ?>
                                                <tr>
                                                    <td colspan="3" style="padding: 5px;">&nbsp;</td>
                                                    <td colspan="2" style="border-bottom: 1px dashed;text-align:right;padding: 5px;"><?= $pay['payment_title']; ?><?= $pay['payment_title'] != "Change Amount" ? " Payment" : ""; ?></td>
                                                    <td style="border-bottom: 1px dashed;text-align:right;padding: 5px 10px;font-weight:700;"><?= number_format($pay['payment_amount'], $nf); ?></td>
                                                </tr><?php
                                            }
                                            endif;
                                        endif; ?>
                                        <?php if($order['asof_date_due']>0): ?>
                                        <tr>
                                            <td colspan="3" style="padding: 5px;">&nbsp;</td>
                                            <td colspan="2" style="border-bottom: 1px dashed;text-align:right;padding: 5px;font-weight:700;font-size:15px;color:#F00;">Total Dues</td>
                                            <td style="border-bottom: 1px dashed;text-align:right;padding: 5px 10px;font-weight:700;font-size:15px;color:#F00;"><?= number_format($order['asof_date_due'], $nf); ?></td>
                                        </tr>
                                        <?php endif; ?>
                                        <?php if($order['asof_date_due']<0): ?>
                                        <tr>
                                            <td colspan="3" style="padding: 5px;">&nbsp;</td>
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
                <table width="48%">
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
                                        <b style="font-size:16px;">Mobile: <?= $footerContactPhone; ?></b><br>
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
                                            <td style="border-bottom: 1px dashed;width:2%;padding: 5px 10px;"><?= $i++; ?></td>
                                            <td style="border-bottom: 1px dashed;width:23%;padding: 5px;"><?= $item['product_info']['name']; ?></td>
                                            <td style="border-bottom: 1px dashed;width:40%;padding: 5px;"><?= $item['product_info']['description']; ?></td>
                                            <td style="border-bottom: 1px dashed;width:8%;padding: 5px;text-align:center;"><?= $item['product_info']['quantity']; ?></td>
                                            <td style="border-bottom: 1px dashed;width:10%;padding: 5px;text-align:center;"><?= number_format($item['product_info']['price'], $nf); ?></td>
                                            <td style="border-bottom: 1px dashed;width:12%;padding: 5px 10px;text-align:right;"><?= number_format($item['product_info']['total'], $nf); ?></td>
                                        </tr>
                                    <?php }?>
                                </tbody>
                                <tfoot>
                                        <tr>
                                            <td colspan="2" style="padding: 5px;">&nbsp;</td>
                                            <td style="text-align:center;padding: 5px;text-align:right;">Total Quantity</td>
                                            <td style="border-bottom: 1px dashed;text-align:center;padding: 5px;"><?= $tq; ?></td>
                                            <td style="border-bottom: 1px dashed;text-align:right;padding: 5px;">Sub Total</td>
                                            <td style="border-bottom: 1px dashed;text-align:right;padding: 5px 10px;font-weight:700;"><?= number_format($st, $nf); ?></td>
                                        </tr>
                                        <?php if($order['shipping_cost']>0): ?>
                                        <tr>
                                            <td colspan="3" style="padding: 5px;">&nbsp;</td>
                                            <td colspan="2" style="border-bottom: 1px dashed;text-align:right;padding: 5px;">Shipping Cost</td>
                                            <td style="border-bottom: 1px dashed;text-align:right;padding: 5px 10px;">(+)<?= number_format($order['shipping_cost'], $nf); ?></td>
                                        </tr>
                                        <?php endif; ?>
                                        <?php if($order['labour_cost']>0): ?>
                                        <tr>
                                            <td colspan="3" style="padding: 5px;">&nbsp;</td>
                                            <td colspan="2" style="border-bottom: 1px dashed;text-align:right;padding: 5px;">Labour Cost</td>
                                            <td style="border-bottom: 1px dashed;text-align:right;padding: 5px 10px;">(+)<?= number_format($order['labour_cost'], $nf); ?></td>
                                        </tr>
                                        <?php endif; ?>
										<?php if($order['carrying_cost']>0): ?>
                                        <tr>
                                            <td colspan="3" style="padding: 5px;">&nbsp;</td>
                                            <td colspan="2" style="border-bottom: 1px dashed;text-align:right;padding: 5px;">Carrying Cost</td>
                                            <td style="border-bottom: 1px dashed;text-align:right;padding: 5px 10px;">(+)<?= number_format($order['carrying_cost'], $nf); ?></td>
                                        </tr>
                                        <?php endif; ?>
										<?php if($order['other_cost']>0): ?>
                                        <tr>
                                            <td colspan="3" style="padding: 5px;">&nbsp;</td>
                                            <td colspan="2" style="border-bottom: 1px dashed;text-align:right;padding: 5px;">Other Cost</td>
                                            <td style="border-bottom: 1px dashed;text-align:right;padding: 5px 10px;">(+)<?= number_format($order['other_cost'], $nf); ?></td>
                                        </tr>
                                        <?php endif; ?>
                                        <?php if($order['referrer'] == "POS" && $order['discount_code']>0): ?>
                                        <tr>
                                            <td colspan="3" style="padding: 5px;">&nbsp;</td>
                                            <td colspan="2" style="border-bottom: 1px dashed;text-align:right;padding: 5px;">Discount</td>
                                            <td style="border-bottom: 1px dashed;text-align:right;padding: 5px 10px;">(-)<?= number_format($order['discount_code'], $nf); ?></td>
                                        </tr>
                                        <?php endif; ?>
                                        <?php if($order['referrer'] == "POS"): 
                                            if($p):
                                            foreach($p as $pay){
                                                if($pay['payment_title'] == 'Advance' || $pay['payment_title'] == 'Collection'): ?>
                                                <tr>
                                                    <td colspan="3" style="padding: 5px;">&nbsp;</td>
                                                    <td colspan="2" style="border-bottom: 1px dashed;text-align:right;padding: 5px;"><?= $pay['payment_title']; ?> Receive</td>
                                                    <td style="border-bottom: 1px dashed;text-align:right;padding: 5px 10px;font-weight:700;">(+)<?= number_format($pay['payment_amount'], $nf); ?></td>
                                                </tr><?php
                                                $order['total'] += $pay['payment_amount'];
                                                endif;
                                            }
                                            endif;
                                        endif; ?>
                                        <?php if($order['total']>0): ?>
                                        <tr>
                                            <td colspan="3" style="padding: 5px;">&nbsp;</td>
                                            <td colspan="2" style="border-bottom: 1px dashed;text-align:right;padding: 5px;">Total</td>
                                            <td style="border-bottom: 1px dashed;text-align:right;padding: 5px 10px;font-weight:700;"><?= number_format($order['total'], $nf); ?></td>
                                        </tr>
                                        <?php endif; ?>
                                        <?php if($order['referrer'] == "POS"): 
                                            if($p):
                                            foreach($p as $pay){
                                                if($pay['payment_title'] == 'Advance' || $pay['payment_title'] == 'Collection') continue; ?>
                                                <tr>
                                                    <td colspan="3" style="padding: 5px;">&nbsp;</td>
                                                    <td colspan="2" style="border-bottom: 1px dashed;text-align:right;padding: 5px;"><?= $pay['payment_title']; ?><?= $pay['payment_title'] != "Change Amount" ? " Payment" : ""; ?></td>
                                                    <td style="border-bottom: 1px dashed;text-align:right;padding: 5px 10px;font-weight:700;"><?= number_format($pay['payment_amount'], $nf); ?></td>
                                                </tr><?php
                                            }
                                            endif;
                                        endif; ?>
                                        <?php if($order['asof_date_due']>0): ?>
                                        <tr>
                                            <td colspan="3" style="padding: 5px;">&nbsp;</td>
                                            <td colspan="2" style="border-bottom: 1px dashed;text-align:right;padding: 5px;font-weight:700;font-size:15px;color:#F00;">Total Dues</td>
                                            <td style="border-bottom: 1px dashed;text-align:right;padding: 5px 10px;font-weight:700;font-size:15px;color:#F00;"><?= number_format($order['asof_date_due'], $nf); ?></td>
                                        </tr>
                                        <?php endif; ?>
                                        <?php if($order['asof_date_due']<0): ?>
                                        <tr>
                                            <td colspan="3" style="padding: 5px;">&nbsp;</td>
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
            <?php } elseif($page_width == "50") { ?>
            <div id="invoice" class="box box-success">
                <table style="width: 350px;margin-left: 150px;">
                    <tr>
                        <td>
                            <table width="100%">
                                <?php if(@$logo_in == 'left'){?>
								<tr>
                                    <td style="vertical-align: top;text-align:center">
                                        <img src="<?= base_url('attachments/'. SHOP_DIR .'/site_logo/' . $sitelogo) ?>" alt="<?= $_SERVER['HTTP_HOST'] ?> Logo" style="max-width:200px;max-height:50px;">
                                    </td>
									<td style="vertical-align: top;padding:5px 10px;text-align:center">
										<b style="font-size:18px;"><?= $companyName; ?></b><br>
                                        <b style="font-size:16px;"><?= $footerContactPhone; ?></b><br>
                                        <?= $order['order_type'] == 'sale_return' ? '<h4>Sales Return</h4>' : ''; ?>
                                        <div style="text-align:center">SL No: <?= $order['order_id']; ?></div>
                                        <?php if($order['note']): ?>
                                        <div style="text-align:center">Note: <?= $order['note']; ?></div>
                                        <?php endif; ?>
									</td>
                                </tr>
                                <tr>
                                    <td colspan="2" style="vertical-align: top;padding:5px 10px;text-align:center">
                                        <i><b><?= $footerContactAddr; ?></b></i>
                                    </td>
                                </tr>
								<?php } else { ?>
                                <tr>
                                    <td style="vertical-align: top;text-align:center">
                                        <img src="<?= base_url('attachments/'. SHOP_DIR .'/site_logo/' . $sitelogo) ?>" alt="<?= $_SERVER['HTTP_HOST'] ?> Logo" style="max-width:200px;max-height:50px;">
                                    </td>
                                </tr>
                                <tr>
                                    <td style="vertical-align: top;padding:5px 10px;text-align:center">
                                        <b style="font-size:18px;"><?= $companyName; ?></b><br>
                                        <b style="font-size:16px;"><?= $footerContactPhone; ?></b><br>
                                        <i><b><?= $footerContactAddr; ?></b></i>
                                        <?= $order['order_type'] == 'sale_return' ? '<h4>Sales Return</h4>' : ''; ?>
                                        <div style="text-align:center">SL No: <?= $order['order_id']; ?></div>
                                        <?php if($order['note']): ?>
                                        <div style="text-align:center">Note: <?= $order['note']; ?></div>
                                        <?php endif; ?>
                                    </td>
                                </tr>
								<?php } ?>
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
                                        <td style="border-top: 1px solid;border-bottom: 1px solid;width: 45%;padding: 5px;">Item Name</td>
                                        <!-- <td style="border-top: 1px solid;border-bottom: 1px solid;width: 40%;padding: 5px;">Description</td> -->
                                        <td style="border-top: 1px solid;border-bottom: 1px solid;width: 13%;padding: 5px;text-align:center;">Qty</td>
                                        <td style="border-top: 1px solid;border-bottom: 1px solid;width: 20%;padding: 5px;text-align:center;">Price</td>
                                        <td style="border-top: 1px solid;border-bottom: 1px solid;width: 20%;padding: 5px 10px;text-align:right;">Total</td>
                                    </tr>
                                </thead>
                                <tbody id="cart_contents">
                                    <?php $i=1; $tq=0; $st=0; foreach(unserialize($order['products']) as $line=>$item) { 
                                        $tq += $item['product_info']['quantity'];
                                        $st += $item['product_info']['total'];
                                    ?>
                                        <tr>
                                            <td style="border-bottom: 1px dashed;width:2%;padding: 5px 10px;"><?= $i++; ?></td>
                                            <td style="border-bottom: 1px dashed;width:45%;padding: 5px;"><?= $item['product_info']['name']; ?></td>
                                            <!-- <td style="border-bottom: 1px dashed;width:40%;padding: 5px;"><?= $item['product_info']['description']; ?></td> -->
                                            <td style="border-bottom: 1px dashed;width:13%;padding: 5px;text-align:center;"><?= $item['product_info']['quantity']; ?></td>
                                            <td style="border-bottom: 1px dashed;width:20%;padding: 5px;text-align:center;"><?= number_format($item['product_info']['price'], $nf); ?></td>
                                            <td style="border-bottom: 1px dashed;width:20%;padding: 5px 10px;text-align:right;"><?= number_format($item['product_info']['total'], $nf); ?></td>
                                        </tr>
                                    <?php }?>
                                </tbody>
                                <tfoot>
                                        <tr>
                                            <td colspan="2" rowspan="7" style="vertical-align: top;padding: 5px; text-align:right;">Total Quantity <br><br>
                                            <img src="data:image/png;base64,<?= $barcode; ?>">
                                            </td>
                                            <td style="border-bottom: 1px dashed;text-align:center;padding: 5px;"><?= $tq; ?></td>
                                            <td style="border-bottom: 1px dashed;text-align:right;padding: 5px;">Sub Total</td>
                                            <td style="border-bottom: 1px dashed;text-align:right;padding: 5px 10px;font-weight:700;"><?= number_format($st, $nf); ?></td>
                                        </tr>
                                        <?php if($order['shipping_cost']>0): ?>
                                        <tr>
                                            <td colspan="2" style="border-bottom: 1px dashed;text-align:right;padding: 5px;">Shipping Cost</td>
                                            <td style="border-bottom: 1px dashed;text-align:right;padding: 5px 10px;">(+)<?= number_format($order['shipping_cost'], $nf); ?></td>
                                        </tr>
                                        <?php endif; ?>
                                        <?php if($order['labour_cost']>0): ?>
                                        <tr>
                                            <td colspan="2" style="border-bottom: 1px dashed;text-align:right;padding: 5px;">Labour Cost</td>
                                            <td style="border-bottom: 1px dashed;text-align:right;padding: 5px 10px;">(+)<?= number_format($order['labour_cost'], $nf); ?></td>
                                        </tr>
                                        <?php endif; ?>
										<?php if($order['carrying_cost']>0): ?>
                                        <tr>
                                            <td colspan="2" style="border-bottom: 1px dashed;text-align:right;padding: 5px;">Carrying Cost</td>
                                            <td style="border-bottom: 1px dashed;text-align:right;padding: 5px 10px;">(+)<?= number_format($order['carrying_cost'], $nf); ?></td>
                                        </tr>
                                        <?php endif; ?>
										<?php if($order['other_cost']>0): ?>
                                        <tr>
                                            <td colspan="2" style="border-bottom: 1px dashed;text-align:right;padding: 5px;">Other Cost</td>
                                            <td style="border-bottom: 1px dashed;text-align:right;padding: 5px 10px;">(+)<?= number_format($order['other_cost'], $nf); ?></td>
                                        </tr>
                                        <?php endif; ?>
                                        <?php if($order['referrer'] == "POS" && $order['discount_code']>0): ?>
                                        <tr>
                                            <td colspan="2" style="border-bottom: 1px dashed;text-align:right;padding: 5px;">Discount</td>
                                            <td style="border-bottom: 1px dashed;text-align:right;padding: 5px 10px;">(-)<?= number_format($order['discount_code'], $nf); ?></td>
                                        </tr>
                                        <?php endif; ?>
                                        <?php if($order['referrer'] == "POS"): 
                                            $p = unserialize($order['payment_type']);
                                            if($p):
                                            foreach($p as $pay){
                                                if($pay['payment_title'] == 'Advance' || $pay['payment_title'] == 'Collection'): ?>
                                                <tr>
                                                    <td colspan="2" style="border-bottom: 1px dashed;text-align:right;padding: 5px;"><?= $pay['payment_title']; ?> Receive</td>
                                                    <td style="border-bottom: 1px dashed;text-align:right;padding: 5px 10px;font-weight:700;">(+)<?= number_format($pay['payment_amount'], $nf); ?></td>
                                                </tr><?php
                                                $order['total'] += $pay['payment_amount'];
                                                endif;
                                            }
                                            endif;
                                        endif; ?>
                                        <?php if($order['total']>0): ?>
                                        <tr>
                                            <td colspan="2" style="border-bottom: 1px dashed;text-align:right;padding: 5px;">Total</td>
                                            <td style="border-bottom: 1px dashed;text-align:right;padding: 5px 10px;font-weight:700;"><?= number_format($order['total'], $nf); ?></td>
                                        </tr>
                                        <?php endif; ?>
                                        <?php if($order['referrer'] == "POS"): 
                                            if($p):
                                            foreach($p as $pay){
                                                if($pay['payment_title'] == 'Advance' || $pay['payment_title'] == 'Collection') continue; ?>
                                                <tr>
                                                    <td colspan="2" style="border-bottom: 1px dashed;text-align:right;padding: 5px;"><?= $pay['payment_title']; ?><?= $pay['payment_title'] != "Change Amount" ? " Payment" : ""; ?></td>
                                                    <td style="border-bottom: 1px dashed;text-align:right;padding: 5px 10px;font-weight:700;"><?= number_format($pay['payment_amount'], $nf); ?></td>
                                                </tr><?php
                                            }
                                            endif;
                                        endif; ?>
                                        <?php if($order['asof_date_due']>0): ?>
                                        <tr>
                                            <td colspan="2" style="border-bottom: 1px dashed;text-align:right;padding: 5px;font-weight:700;font-size:15px;color:#F00;">Total Dues</td>
                                            <td style="border-bottom: 1px dashed;text-align:right;padding: 5px 10px;font-weight:700;font-size:15px;color:#F00;"><?= number_format($order['asof_date_due'], $nf); ?></td>
                                        </tr>
                                        <?php endif; ?>
                                        <?php if($order['asof_date_due']<0): ?>
                                        <tr>
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
                        <table style="width:100%; margin-top:10px; background:#F9F9F9;">
                            <tr>
                                <td style="border-top:1px solid;padding: 5px; text-align:center;">Thank you...</td>
                            </tr>
                        </table>
                        </td>
                    </tr>
                </table>
            </div>
            <?php } ?>
        </div>
        <div class="col-md-4 col-sm-4 col-xs-12">
			<?php if($printer_type == 'posp'){ ?>
			<a class="btn btn-success btn-block btn-flat" target="_blank" href="<?= site_url("admin/sale/print_order/".$order['id']); ?>">Print Invoice</a><br>
			<?php } else { ?>
			<button class="btn btn-success btn-block btn-flat" onclick="printDiv('invoice')">Print Invoice</button><br>
			<?php } ?>
			<!-- <button class="btn btn-success btn-block btn-flat" onclick="printDiv('invoice')">Print 3 Copy's Invoice</button><br> -->
            <a class="btn btn-primary btn-block btn-flat" href="<?= site_url("admin/sale"); ?>">Go to Sale Register</a><br>
            <a href="<?= site_url("admin/sale/print_order/".$order['id']."?sms=yes"); ?>" class="btn btn-info btn-block btn-flat">Send SMS Invoice</a><br>
            <!-- <button class="btn btn-primary btn-block btn-flat">Email Invoice</button><br> -->
            <?php if(strpos($access[0]['access'], SALES_INV_SETUP)>-1) { ?>
            <button class="btn btn-default btn-block btn-flat" onclick="toggle_div('#inv_settings')">Invoice Settings</button><br>
            <div class="row" style="display:none;" id="inv_settings">
                <div class="col-md-12 col-xs-12">
                    <div class="panel panel-success col-h">
                        <div class="panel-heading">Invoice Setup</div>
                        <div class="panel-body">
                            <?php if ($this->session->flashdata('page_width')) { ?>
                                <div class="alert alert-info"><?= $this->session->flashdata('page_width') ?></div>
                            <?php } ?>
                            <form method="POST" action="<?= site_url("admin/settings"); ?>">
                                <label>Invoice Page Address Line</label>
                                <input type="text" name="inv_addLine_1" class="form-control" placeholder="Address Line One">
                                <input type="text" name="inv_addLine_2" class="form-control" placeholder="Address Line Two">
                                <input type="text" name="inv_addLine_3" class="form-control" placeholder="Address Line Three">
                                <input type="text" name="inv_addLine_4" class="form-control" placeholder="Address Line Four">
                                <br>
                                <button class="btn btn-default" value="" type="submit">Save</button>
                            </form>
                            <br>
                            
                            
                            <form method="POST" action="<?= site_url("admin/settings"); ?>">
                                <input type="hidden" name="invid" value="<?= $order['id'] ?>" >
                                <label>Invoice Page Size</label>
                                <div class="radio">
                                    <label><input type="radio" name="page_width" value="100" <?= $page_width == "100" ? 'checked' : '' ?>> Full width ( 100% )</label><br>
                                    <label><input type="radio" name="page_width" value="50" <?= $page_width == "50" ? 'checked' : '' ?>> Half width ( 50% )</label><br>
                                    <label><input type="radio" name="page_width" value="50x2" <?= $page_width == "50x2" ? 'checked' : '' ?>> Half width x 2/per page</label>
                                </div>
                                <button class="btn btn-default" value="" type="submit">Save</button>
                            </form>
                            <br>
                            <form method="POST" action="<?= site_url("admin/settings"); ?>">
                                <input type="hidden" name="invid" value="<?= $order['id'] ?>" >
                                <label>Printer Type</label>
                                <div class="radio">
                                    <label><input type="radio" name="printer_type" value="np" <?= $printer_type == "np" ? 'checked' : '' ?>> Normal Printer</label><br>
                                    <label><input type="radio" name="printer_type" value="posp" <?= $printer_type == "posp" ? 'checked' : '' ?>> POS Printer</label>
                                </div>
                                <button class="btn btn-default" value="" type="submit">Save</button>
                            </form>
                            <br>
                            <form method="POST" action="<?= site_url("admin/settings"); ?>">
                                <input type="hidden" name="invid" value="<?= $order['id'] ?>" >
                                <label>Invoice Description/ Image</label>
                                <div class="radio">
                                    <label><input type="radio" name="details_img" value="des" <?= $details_img == "des" ? 'checked' : '' ?>> Description</label><br>
                                    <label><input type="radio" name="details_img" value="img" <?= $details_img == "img" ? 'checked' : '' ?>> Image</label>
                                </div>
                                <button class="btn btn-default" value="" type="submit">Save</button>
                            </form>
                            <br>
                            <form method="POST" action="<?= site_url("admin/settings"); ?>">
                                <input type="hidden" name="invid" value="<?= $order['id'] ?>" >
                                <label>Logo Image Position</label>
                                <div class="radio">
                                    <label><input type="radio" name="logo_in" value="center" <?= @$logo_in == "" ? 'checked' : '' ?>> Center</label><br>
                                    <label><input type="radio" name="logo_in" value="left" <?= @$logo_in == "left" ? 'checked' : '' ?>> Left</label>
                                </div>
                                <button class="btn btn-default" value="" type="submit">Save</button>
                            </form>
                        </div>
                    </div>
                </div>
            </div>
            <?php } ?>
        </div>
    </div>