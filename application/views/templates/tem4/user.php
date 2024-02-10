<?php
defined('BASEPATH') OR exit('No direct script access allowed');
?>
<!--<div class="inner-nav">-->
<!--    <div class="container">-->
<!--        <a href="<?= LANG_URL ?>"><?= lang('home') ?></a> <span class="active"> > <?= lang('user_login') ?></span>-->
<!--    </div>-->
<!--</div>-->
<div class="container user-page">
    <div class="row">
        <div class="col-sm-4">
            <div class="loginmodal-container">
                <h1><?= lang('my_acc') ?></h1><br>
                <?php $readonly = ""; 
                if($userInfo['phone_verify'] != "Yes") $readonly = "disabled";
                ?>
                <form method="POST" action="">
                    <input type="text" name="name" class="form-control" value="<?= $userInfo['name'] ?>" placeholder="Name" <?= $readonly ?>>
                    <input type="text" name="phone" class="form-control" value="<?= $userInfo['phone'] ?>" placeholder="Phone" <?= $readonly ?>>
                    <input type="text" name="email" class="form-control" value="<?= $userInfo['email'] ?>" placeholder="Email" <?= $readonly ?>>
                    <input type="password" name="pass" class="form-control" placeholder="Password (leave blank if no change)" <?= $readonly ?>> 
                    <input type="submit" name="update" class="login loginmodal-submit" value="<?= lang('update') ?>" <?= $readonly ?>>
                    <a href="<?= LANG_URL . '/logout' ?>" class="login loginmodal-submit text-center"><?= lang('logout') ?></a>
                </form>
            </div>
        </div>
        <div class="col-sm-8">
            <?php if($userInfo['phone_verify'] == 'Yes'){ ?>
                <h3><?= lang('usr_order_history') ?></h3>
                <div class="table-responsive">
                    <table class="table table-condensed table-bordered table-striped">
                        <thead>
                            <tr>
                                <th><?= lang('usr_order_id') ?></th>
                                <th><?= lang('usr_order_date') ?></th>
                                <th><?= lang('usr_order_address') ?></th>
                                <th><?= lang('user_order_status') ?></th>
                                <th class="hidden"></th>
                                <th><?= lang('user_order_total') ?></th>
                                <th>Action</th>
                            </tr>
                        </thead>
                        <tbody>
                            <?php
                            if (!empty($orders_history)) {
                                foreach ($orders_history as $order) {
                                    $total_discount_amt = 0;
                                    $total_amount = $order['shipping_cost'];
                                    ?>
                                    <tr>
                                        <td><?= $order['order_id'] ?></td>
                                        <td><?= $order['date'] ?></td>
                                        <td><?= $order['address'] ?></td>
                                        <td><?php 
                                            if($order['processed'] == 0) echo "Place order";
                                            if($order['processed'] == 1) echo "Completed";
                                            if($order['processed'] == 2) echo "Rejected";
                                            if($order['processed'] == 3) echo "Processing";
                                            ?>
                                        </td>
                                        <td class="hidden" id="order-id-<?= $order['order_id'] ?>">
                                            <?php
                                            $arr_products = unserialize($order['products']);
                                            foreach ($arr_products as $key => $value) {
                                                $productInfo = modules::run('admin/ecommerce/products/getProductInfo', $value['product_info']['id'], true);
                                                $total_amount += $productInfo['price'] * $value['product_quantity'];
                                                ?>
                                                <div class="row">
                                                    <div class="col-md-3">
                                                        <div>
                                                            <img src="<?= base_url('attachments/'. SHOP_DIR .'/shop_images/' . $productInfo['image']) ?>" alt="Product" style="width:100px; margin-right:10px;" class="img-responsive">
                                                        </div>
                                                        <a target="_blank" href="<?= base_url($productInfo['url']) ?>">View Product</a> 
                                                    </div>
                                                    <div class="col-md-9">
                                                        <div><b><?= lang('price') ?></b> <?= $productInfo['price'] . CURRENCY ?></div>
                                                        <div><b><?= lang('user_order_quantity') ?></b> <?= $value['product_quantity'] ?></div>
                                                        <?php if($value['product_info']['size'] != 0 && $value['product_info']['size'] != 'N'){?>
                                                        <div><b><?= lang('user_product_size') ?></b> <?= $value['product_info']['size'] ?></div>
                                                        <?php } ?>
                                                        <?php if($productInfo['vendor_name'] != ''){ ?>
                                                        <div><b><?= lang('vendor') ?></b> <?= $productInfo['vendor_name'] ?></div>
                                                        <?php } ?>
                                                    </div>
                                                </div>
                                                <hr>
                                            <?php } ?>
                                            <div class="row">
                                                <div class="col-md-4"><?= lang('shippingCost') ?></div>
                                                <div class="col-md-8"><?= $order['shipping_cost'] . CURRENCY ?></div>
                                            </div>
                                            <div class="row">
                                                <div class="col-md-4">Total Discount</div>
                                                <div class="col-md-8">
                                                    <?php 
                                                    if($order['discount_type'] == 'float'){
                                                        $total_discount_amt = $order['discount_amount'];
                                                    }else{
                                                        $total_discount_amt = ($total_amount - $order['shipping_cost']) * $order['discount_amount'] / 100;
                                                    }
                                                    echo $total_discount_amt .' '. CURRENCY;
                                                    $total_amount = $total_amount - $total_discount_amt;
                                                    ?>
                                                </div>
                                            </div>
                                        </td>
                                        <td>
                                            <?php
                                            echo $total_amount .' '. CURRENCY; 
                                            ?>
                                        </td>
                                        <td>
                                            <a href="javascript:void(0);" class="btn btn-default more-info" data-toggle="modal" data-target="#modalPreviewMoreInfo" data-more-info="<?= $order['order_id'] ?>">
                                                View Products
                                                <i class="fa fa-info-circle" aria-hidden="true"></i>
                                            </a>
                                        </td>
                                    </tr>
                                    <?php
                                }
                            } else {
                                ?>
                                <tr>
                                    <td colspan="6"><?= lang('usr_no_orders') ?></td>
                                </tr>
                            <?php } ?>
                        </tbody>
                    </table>
                    <?= $links_pagination ?>
                </div>
            <?php } else { ?>
                <div class="col-sm-6">
                    <div class="loginmodal-container">
                        <!--<input type="button" class="login loginmodal-submit" value="Resend Verification Code"><br>-->
                        <p>Please enter the verification code which already send in your mobile. And then active your account.</p>
                        <form method="POST" action="">
                            <input type="text" name="verify_phone" placeholder="Enter Verification Code">
                            <input type="submit" name="verify_phone_number" class="login loginmodal-submit" value="Verify Phone">
                        </form>
                        <div id="resend-div">
                            <div id="resendbar"></div>
                        </div>
                    </div>
                </div>
            <?php } ?>
        </div>
    </div>
</div>
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
<script src="<?= base_url("assets/js/radial-progress-bar.js"); ?>"></script>
<script>
jQuery("#resendbar").radialProgress("init", {
  'size': 140,
  'fill': 70,
  'font-size': 36,
  'color': '#a0c03c',
  'text-color': '#FFF'
}).radialProgress("to", {'perc': 100, 'time': 100000, 'after100': '<a href="<?= site_url("myaccount"); ?>" >Resend</a>'});
</script>