<?php $tabindex= 1; ?>
<h1><button class="btn btn-default" onclick="javascript:location.href='<?= site_url("admin/expenses"); ?>'"><i class="fa fa-arrow-left"></i></button> <?= $description; ?></h1>
<!--<p>This invoice is under maintaince. Please check in the morning.</p>-->
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
                                    </td>
                                    <td style="vertical-align: top;text-align:right;padding:10px;">
                                        <img src="data:image/png;base64,<?= $barcode; ?>">
                                        <div style="text-align:center">SL No: <?= $exp_trans_details[0]['id']; ?></div>
                                        <br><br><br>
                                        Date: <?= $exp_trans_details[0]['date']; ?>
                                    </td>
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
                                        <td style="border-top: 1px solid;border-bottom: 1px solid;width: 10%;padding: 5px;">Group</td>
                                        <td style="border-top: 1px solid;border-bottom: 1px solid;width: 60%;padding: 5px;">Description</td>
                                        <td style="border-top: 1px solid;border-bottom: 1px solid;width: 10%;padding: 5px;">Payment By</td>
                                        <td style="border-top: 1px solid;border-bottom: 1px solid;width: 18%;padding: 5px 10px;text-align:right;">Amount</td>
                                    </tr>
                                </thead>
                                <tbody id="cart_contents">
                                    <tr>
                                        <td style="border-bottom: 1px dashed;width:2%;padding: 5px 10px;">1</td>
                                        <td style="border-bottom: 1px dashed;width:10%;padding: 5px;"><?= $exp_trans_details[0]['group']; ?></td>
                                        <td style="border-bottom: 1px dashed;width:60%;padding: 5px;"><?= $exp_trans_details[0]['title'].' - '.$exp_trans_details[0]['details']; ?></td>
                                        <td style="border-bottom: 1px dashed;width:10%;padding: 5px;"><?= $exp_trans_details[0]['name']; ?></td>
                                        <td style="border-bottom: 1px dashed;width:18%;padding: 5px 10px;text-align:right;"><?= number_format($exp_trans_details[0]['amount'], $nf); ?></td>
                                    </tr>
                                    <tr>
                                        <td colspan=5>&nbsp;</td>
                                    </tr>
                                    <tr>
                                        <td>&nbsp;</td>
                                        <td colspan=4><b>In Word: <?= @$iw; ?> Taka Only.</b></td>
                                    </tr>
                                    <tr>
                                        <td>&nbsp;</td>
                                        <td colspan=4><br><br><br><br>Received By: _____________________</td>
                                    </tr>
                                </tbody>
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
        </div>
        <div class="col-md-4 col-sm-4 col-xs-12">
            <button class="btn btn-success btn-block btn-flat" onclick="printDiv('invoice')">Print Bill</button><br>
            <a class="btn btn-primary btn-block btn-flat" href="#" onclick="history.back()">Go to Expenses</a><br>
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