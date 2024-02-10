<?php $tabindex= 1; ?>
<h1><button class="btn btn-default" onclick="javascript:location.href='<?= site_url("admin/accounts"); ?>'"><i class="fa fa-arrow-left"></i></button> <?= $description; ?></h1>
<hr>
    <div class="row">
        <div class="col-md-8 col-sm-8 col-xs-12">
            <?php if(isset($customer_info)): ?>
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
                                    <td style="border-top: 1px solid;width:120px; padding:5px 10px;font-weight: 700;text-align:right;"><?= date("Y-m-d"); ?></td>
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
                                        <td style="border-top: 1px solid;border-bottom: 1px solid;width: 80%;padding: 5px;">Description</td>
                                        <td style="border-top: 1px solid;border-bottom: 1px solid;width: 18%;padding: 5px 10px;text-align:right;">Total</td>
                                    </tr>
                                </thead>
                                <tbody id="cart_contents">
                                    <tr>
                                        <td style="border-bottom: 1px dashed;width:2%;padding: 5px 10px;">1</td>
                                        <td style="border-bottom: 1px dashed;width:80%;padding: 5px;"><?= $details; ?></td>
                                        <td style="border-bottom: 1px dashed;width:18%;padding: 5px 10px;text-align:right;"><?= number_format($amount_tendered, $nf); ?></td>
                                    </tr>
                                </tbody>
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
            <?php endif; ?>
        </div>
        <div class="col-md-4 col-sm-4 col-xs-12">
            <button class="btn btn-success btn-block btn-flat" onclick="printDiv('invoice')">Print Invoice</button><br>
            <a class="btn btn-primary btn-block btn-flat" href="<?= site_url("admin/sale"); ?>">Go to Sale Register</a><br>
            <button class="btn btn-info btn-block btn-flat">SMS Invoice</button><br>
            <button class="btn btn-primary btn-block btn-flat">Email Invoice</button><br>
            <button class="btn btn-default btn-block btn-flat" onclick="toggle_div('#inv_settings')">Invoice Settings</button><br>
            <div class="row" style="display:none;" id="inv_settings">
                <div class="col-md-12 col-xs-12">
                    Settings Start
                </div>
            </div>
        </div>
    </div>