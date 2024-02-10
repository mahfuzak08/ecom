<?php
defined('BASEPATH') OR exit('No direct script access allowed');
?>
<div class="container" id="checkout-page">
    <?php
    if ($cartItems['array'] != null) {
        ?>
        <?= purchase_steps(1, 2) ?>
        <div class="row">
            <div class="col-sm-9 left-side">
                <?php if (isset($_SESSION['logged_user'])) { ?>
                    <form method="POST" id="goOrder">
                        <div class="title alone">
                            <span><?= lang('checkout') ?></span>
                        </div>
                        <?php
                        if ($this->session->flashdata('submit_error')) {
                            ?>
                            <hr>
                            <div class="alert alert-danger">
                                <h4><span class="glyphicon glyphicon-alert"></span> <?= lang('finded_errors') ?></h4>
                                <?php
                                foreach ($this->session->flashdata('submit_error') as $error) {
                                    echo $error . '<br>';
                                }
                                ?>
                            </div>
                            <hr>
                            <?php
                        }
                        ?>
                        <div class="payment-type-box">
                            <select class="selectpicker payment-type" data-style="btn-blue" name="payment_type">
                                <?php if ($cashondelivery_visibility == 1) { ?>
                                    <option value="cashOnDelivery"><?= lang('cash_on_delivery') ?> </option>
                                <?php } if (filter_var($paypal_email, FILTER_VALIDATE_EMAIL)) { ?>
                                    <option value="PayPal"><?= lang('paypal') ?> </option>
                                <?php } if ($bank_account['iban'] != null) { ?>
                                    <option value="Bank"><?= lang('bank_payment') ?> </option>
                                <?php } ?>
                            </select>
                            <span class="top-header text-center"><?= lang('choose_payment') ?></span>
                        </div>
                        <div class="row">
                            <div class="form-group col-sm-6">
                                <label for="firstNameInput"><?= lang('first_name') ?> <sup class="requires"><?= lang('requires') ?></sup></label>
                                <input id="firstNameInput" class="form-control" name="first_name" value="<?= $userInfo['name'] ?>" type="text" placeholder="<?= lang('first_name') ?>">
                            </div>
                            <div class="form-group col-sm-6">
                                <label for="phoneInput"><?= lang('phone') ?> <sup class="requires"><?= lang('requires') ?></sup></label>
                                <input id="phoneInput" class="form-control" name="phone" value="<?= $userInfo['phone'] ?>" type="text" placeholder="<?= lang('phone') ?>">
                            </div>
                            <div class="form-group col-sm-6">
                                <label for="emailAddressInput"><?= lang('email_address') ?></label>
                                <input id="emailAddressInput" class="form-control" name="email" value="<?= $userInfo['email'] ?>" type="text" placeholder="<?= lang('email_address') ?>">
                            </div>
                            <div class="form-group col-sm-6">
                                <label for="cityInput"><?= lang('shippingArea') ?> <sup class="requires"><?= lang('requires') ?></sup></label>
                                <select name="city" id="cityInput" class="form-control" onchange="setShippingCost()">
                                    <option value="">Select Location</option>
                                    <?php foreach($location as $row){ ?>
                                    <option data-cost=<?= $row['cost'] ?> value="<?= $row['name'] ?>"><?= $row['name'] ?></option>
                                    <?php } ?>
                                </select>
                            </div>
                            <div class="form-group col-sm-12">
                                <label for="addressInput"><?= lang('address') ?> <sup class="requires"><?= lang('requires') ?></sup></label>
                                <textarea id="addressInput" name="address" class="form-control" rows="3"><?= @$_POST['address'] ?></textarea>
                            </div>
                            <!--<div class="form-group col-sm-6">-->
                            <!--    <label for="postInput"><?= lang('post_code') ?></label>-->
                            <!--    <input id="postInput" class="form-control" name="post_code" value="<?= @$_POST['post_code'] ?>" type="text" placeholder="<?= lang('post_code') ?>">-->
                            <!--</div>-->
                            <div class="form-group col-sm-12">
                                <label for="notesInput"><?= lang('notes') ?></label>
                                <textarea id="notesInput" class="form-control" name="notes" rows="3"><?= @$_POST['notes'] ?></textarea>
                            </div>
                        </div>
                        <?php if ($codeDiscounts == 1) { ?>
                            <div class="discount">
                                <label><?= lang('discount_code') ?></label>
                                <input class="form-control" name="discountCode" value="<?= @$_POST['discountCode'] ?>" placeholder="<?= lang('enter_discount_code') ?>" type="text">
                                <a href="javascript:void(0);" class="btn btn-default" onclick="checkDiscountCode()"><?= lang('check_code') ?></a>
                            </div>
                        <?php } ?>
                        <div class="table-responsive">
                            <table class="table table-bordered table-products">
                                <thead>
                                    <tr>
                                        <th><?= lang('product') ?></th>
                                        <th><?= lang('title') ?></th>
                                        <th><?= lang('quantity') ?></th>
                                        <?php if($multiSize == 1) { ?>
                                        <th><?= lang('available_size'); ?>
                                        <?php } ?>
                                        <th><?= lang('price') ?></th>
                                        <th><?= lang('total') ?></th>
                                    </tr>
                                </thead>
                                <tbody>
                                    <?php foreach ($cartItems['array'] as $item) { ?>
                                        <tr class="item-<?= $item['id']; ?>">
                                            <td class="relative">
                                                <input type="hidden" name="id[]" value="<?= $item['id'] ?>">
                                                <input type="hidden" name="quantity[]" value="<?= $item['num_added'] ?>">
                                                <img class="product-image" src="<?= base_url('/attachments/'. SHOP_DIR .'/shop_images/' . $item['image']) ?>" alt="">
                                                <!-- <a href="<?= base_url('home/removeFromCart?delete-product=' . $item['id'] . '&back-to=checkout') ?>" class="btn btn-xs btn-danger remove-product"> -->
                                                <a href="javascript:void(0)" onclick="removeProduct('<?= base64_encode($item['id'].'@'.$item['num_added']); ?>')" class="btn btn-xs btn-danger remove-product">
                                                    <span class="glyphicon glyphicon-remove"></span>
                                                </a>
                                            </td>
                                            <td><a href="<?= LANG_URL . '/' . $item['url'] ?>"><?= $item['title'] ?></a></td>
                                            <td>
                                                <a class="btn  btn-xs btn-danger" onclick="removeProduct('<?= base64_encode($item['id'].'@1'); ?>')" href="javascript:void(0);">
                                                    <span class="glyphicon glyphicon-minus"></span>
                                                </a>
                                                <span class="quantity-num">
                                                    <?= price_format($item['num_added'],0) ?>
                                                </span>
                                                <a class="btn btn-xs btn-primary add-to-cart add2cart" data-id="<?= base64_encode($item['id']); ?>" data-title="<?= $item['title']; ?>" data-url="<?= $item['url']; ?>" data-img="<?= $item['image']; ?>" data-price="<?= $item['price']; ?>" href="javascript:void(0);">
                                                    <span class="glyphicon glyphicon-plus"></span>
                                                </a>
                                            </td>
                                            <?php if($multiSize == 1 && $item['size'] != 'N' && $item['size'] != '') { ?>
                                            <td class="size_req">
                                                <?php 
                                                $sizes = explode(";", $item['size']);
                                                for($i=0; $i<count($sizes); $i++){
                                                    $ss = explode("x", $sizes[$i]); ?>
                                                    <span onclick="update_item_size('<?= base64_encode($item['id'].'@'.$ss[0]); ?>')" class="size <?= ($item['size_select'] == $ss[0]) ? 'select' : '';?>"><?= $ss[0]; ?></span>
                                                    <?php
                                                }
                                                ?>
                                                <input type="hidden" name="size[]" value="<?= $item['size_select']; ?>">
                                            </td>
                                            <?php } elseif($multiSize == 1 && ($item['size'] == 'N' || $item['size'] == '')){ ?>
                                                <td>
                                                    <input type="hidden" name="size[]" value="0">
                                                </td><?php 
                                            } ?>
                                            <td><?= CURRENCY . price_format($item['price']) ?></td>
                                            <td><?= CURRENCY . price_format($item['sum_price']) ?></td>
                                        </tr>
                                    <?php } ?>
                                    <?php 
                                    if (!empty($shippingOrder) && $shippingOrder != 0 && $shippingOrder != null && (float)str_replace(",", "", $cartItems['finalSum']) > 999) {
                                        $cartItems['finalSum'] = (float)str_replace(",", "", $cartItems['finalSum']) - $cartItems['shipping'];
                                        $cartItems['shipping'] = 0;
                                    }
                                    ?>
                                    <tr>
                                        <td colspan="5" class="text-right"><?= lang('shippingCost') ?></td>
                                        <td>
                                            <span class="shipping"><?= $cartItems['shipping'] ?></span><?= CURRENCY ?>
                                            <input type="hidden" id="shipping" name="shipping" value="<?= $cartItems['shipping'] ?>">
                                        </td>
                                    </tr>
                                    <tr class="discountrow" style="display: none;">
                                        <td colspan="5" class="text-right">Discount Amount</td>
                                        <td>
                                            <span class="discountAmount"></span><?= CURRENCY ?>
                                        </td>
                                    </tr>
                                    <tr>
                                        <td colspan="5" class="text-right"><?= lang('total') ?></td>
                                        <td>
                                            <span class="final-amount"><?= $cartItems['finalSum'] ?></span><?= CURRENCY ?>
                                            <input type="hidden" class="final-amount" name="final_amount" value="<?= $cartItems['finalSum'] ?>">
                                            <input type="hidden" name="amount_currency" value="<?= CURRENCY ?>">
                                            <input type="hidden" name="discountAmount" value="">
                                            <input type="hidden" id="product-amt" value="<?= ((float)str_replace(",", "", $cartItems['finalSum'])  - $cartItems['shipping']) ?>">
                                            <input type="hidden" id="postInput" name="post_code">
                                        </td>
                                    </tr>
                                </tbody>
                            </table>
                        </div>
                    </form>
                    <div>
                        <a href="<?= LANG_URL ?>" class="btn btn-primary go-shop">
                            <span class="glyphicon glyphicon-circle-arrow-left"></span>
                            <?= lang('back_to_shop') ?>
                        </a>
                        <a href="javascript:void(0);" class="btn btn-primary go-order" id="submit-order" onclick="submit_order()" class="pull-left">
                            <?= lang('custom_order') ?> 
                            <span class="glyphicon glyphicon-circle-arrow-right"></span>
                        </a>
                        <div class="clearfix"></div>
                    </div>
                <?php } else { ?>
                    <p class="promot-to-login">
                        Already registered? Please <a href="<?= LANG_URL . '/login' ?>"><?= lang('login') ?></a><br>
                        New to <?= $companyName; ?>? Please <a href="<?= LANG_URL . '/register' ?>"><?= lang('register') ?></a>
                    </p>
                    <div class="table-responsive">
                        <table class="table table-bordered table-products">
                            <thead>
                                <tr>
                                    <th><?= lang('product') ?></th>
                                    <th><?= lang('title') ?></th>
                                    <th><?= lang('quantity') ?></th>
                                    <?php if($multiSize == 1) { ?>
                                    <th><?= lang('available_size'); ?>
                                    <?php } ?>
                                    <th><?= lang('price') ?></th>
                                    <th><?= lang('total') ?></th>
                                </tr>
                            </thead>
                            <tbody>
                                <?php foreach ($cartItems['array'] as $item) { ?>
                                    <tr class="item-<?= $item['id']; ?>">
                                        <td class="relative">
                                            <input type="hidden" name="id[]" value="<?= $item['id'] ?>">
                                            <input type="hidden" name="quantity[]" value="<?= $item['num_added'] ?>">
                                            <img class="product-image" src="<?= base_url('/attachments/'. SHOP_DIR .'/shop_images/' . $item['image']) ?>" alt="">
                                            <a href="javascript:void(0)" onclick="removeProduct('<?= base64_encode($item['id'].'@'.$item['num_added']); ?>')" class="btn btn-xs btn-danger remove-product">
                                                <span class="glyphicon glyphicon-remove"></span>
                                            </a>
                                        </td>
                                        <td><a href="<?= LANG_URL . '/' . $item['url'] ?>"><?= $item['title'] ?></a></td>
                                        <td>
                                            <a class="btn  btn-xs btn-danger" onclick="removeProduct('<?= base64_encode($item['id'].'@1'); ?>')" href="javascript:void(0);">
                                                <span class="glyphicon glyphicon-minus"></span>
                                            </a>
                                            <span class="quantity-num">
                                                <?= price_format($item['num_added'],0) ?>
                                            </span>
                                            <a class="btn btn-xs btn-primary add-to-cart add2cart" data-id="<?= base64_encode($item['id']); ?>" data-title="<?= $item['title']; ?>" data-url="<?= $item['url']; ?>" data-img="<?= $item['image']; ?>" data-price="<?= $item['price']; ?>" href="javascript:void(0);">
                                                <span class="glyphicon glyphicon-plus"></span>
                                            </a>
                                        </td>
                                        <?php if($multiSize == 1 && $item['size'] != 'N' && $item['size'] != '') { ?>
                                        <td class="size_req">
                                            <?php 
                                            $sizes = explode(";", $item['size']);
                                            for($i=0; $i<count($sizes); $i++){
                                                $ss = explode("x", $sizes[$i]); ?>
                                                <span onclick="update_item_size('<?= base64_encode($item['id'].'@'.$ss[0]); ?>')" class="size <?= ($item['size_select'] == $ss[0]) ? 'select' : '';?>"><?= $ss[0]; ?></span>
                                                <?php
                                            }
                                            ?>
                                        </td>
                                        <?php } elseif($multiSize == 1 && ($item['size'] == 'N' || $item['size'] == '')){ ?>
                                            <td></td><?php 
                                        } ?>
                                        <td><?= CURRENCY . price_format($item['price']) ?></td>
                                        <td><?= CURRENCY . price_format($item['sum_price']) ?></td>
                                    </tr>
                                <?php } ?>
                                <tr>
                                    <td colspan="5" class="text-right"><?= lang('shippingCost') ?></td>
                                    <td><?= CURRENCY ?><span class="shipping"><?= price_format($cartItems['shipping']) ?></span></td>
                                </tr>
                                <tr>
                                    <td colspan="5" class="text-right"><?= lang('total') ?></td>
                                    <td>
                                        <?= CURRENCY ?><span class="final-amount"><?= $cartItems['finalSum'] ?></span>
                                        <input type="hidden" class="final-amount" name="final_amount" value="<?= $cartItems['finalSum'] ?>">
                                        <input type="hidden" name="amount_currency" value="<?= CURRENCY ?>">
                                        <input type="hidden" name="discountAmount" value="">
                                    </td>
                                </tr>
                            </tbody>
                        </table>
                    </div>
                <?php } ?>
            </div>
            <div class="col-sm-3"> 
                <div class="filter-sidebar">
                    <div class="title">
                        <span><?= lang('best_sellers') ?></span>
                        <i class="fa fa-trophy" aria-hidden="true"></i>
                    </div>
                    <?= $load::getProducts($bestSellers, '', true) ?>
                </div>
            </div>
        </div>
    </div>
    <div id="loadingdiv">
        <img src="<?= base_url("template/imgs/loading.gif"); ?>" style="">
    </div>
<?php } else { ?>
    <div class="alert alert-info"><?= lang('no_products_in_cart') ?></div>
    <?php
}
if ($this->session->flashdata('deleted')) {
    ?>
    <script>
        $(document).ready(function () {
            ShowNotificator('alert-info', '<?= $this->session->flashdata('deleted') ?>');
        });
    </script>
<?php } if ($codeDiscounts == 1 && isset($_POST['discountCode'])) { ?>
    <script>
        $(document).ready(function () {
            checkDiscountCode();
        });
    </script>
<?php } ?>
<script>
    function setShippingCost(){
        var sc=Number($("#cityInput option:selected").attr('data-cost'));
        if(Number($("#product-amt").val()) < <?= empty($shippingOrder)?0:$shippingOrder; ?>){
            $(".shipping").text(sc.toFixed(2));
            $("#shipping").val(sc);
            $(".final-amount").text((Number($("#product-amt").val()) + sc).toFixed(2));
            $(".final-amount").val((Number($("#product-amt").val()) + sc).toFixed(2));
        }
    }
    function submit_order(){
        <?php if($multiSize ==1) {?>
        if($(".size_req").length != $('.size.select').length) {
            alert("Please select your size");
            return false;
        }
        <?php } ?>
        $('#submit-order').css('pointer-events', 'none');
        $('#loadingdiv').show();
        $('#goOrder').submit();
    }
</script>