<?php
defined('BASEPATH') OR exit('No direct script access allowed');
?>
<div class="container" id="checkout-page">
    <?php
    if (gettype($cartItems) === "integer") {
        ?>
        <br>
        <div class="alert alert-info"><?= lang('no_products_in_cart') ?></div>
        <br><br>
        <?php
    } else {?>
		<br>
        <?= purchase_steps(1, 2) ?>

		<div class="row">
			<div class="col-lg-8 order-2 order-lg-1">
				<?php if (isset($_SESSION['logged_user'])) { ?>
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
					
					<form method="POST" onsubmit="submit_order()" class="checkout-form" id="goOrder">
						<div class="cf-title">Basic Info</div>
						<div class="row address-inputs">
							<div class="col-md-12">
								<input type="text" placeholder="Name" name="first_name" value="<?= $userInfo['name'] ?>">
								<input type="text" placeholder="Mobile Momber" name="phone" value="<?= $userInfo['phone'] ?>">
								<input type="text" placeholder="Email Address" name="email" value="<?= $userInfo['email'] ?>">
							</div>
						</div>
						<div class="cf-title">Delievery Info</div>
						<input type="text" placeholder="Address" name="address" value="<?= $userInfo['address'] ?>">
						<select name="city">
							<option value="">Select Location</option>
							<?php foreach($location as $row){ ?>
							<option data-cost=<?= $row['cost'] ?> value="<?= $row['name'] ?>"><?= $row['name'] ?></option>
							<?php } ?>
						</select>
						<div class="row shipping-btns">
							<div class="col-6">
								<h4>Standard</h4>
							</div>
							<div class="col-6">
								<div class="cf-radio-btns">
									<div class="cfr-item">
										<input type="radio" name="shipping" value="100" id="ship-1" checked>
										<label for="ship-1"><?= CURRENCY . price_format(100) ?></label>
									</div>
								</div>
							</div>
							<div class="col-6">
								<h4>Urgent delievery  </h4>
							</div>
							<div class="col-6">
								<div class="cf-radio-btns">
									<div class="cfr-item">
										<input type="radio" name="shipping" value="300" id="ship-2">
										<label for="ship-2"><?= CURRENCY . price_format(300) ?></label>
									</div>
								</div>
							</div>
							<div class="col-md-12">
								<input type="text" placeholder="Any special instructions" name="notes" value="<?= @$_POST['notes'] ?>">
							</div>
						</div>
						<div class="cf-title">Payment</div>
						<ul class="payment-list">
							<li><input type="radio" disabled> bKash<a href="javascript:void(0);" onclick="alert('This system are coming soon')"><img src="<?= base_url("assets/imgs/bkash.png"); ?>" alt=""></a></li>
							<li><input type="radio" disabled> Credit / Debit card<a href="javascript:void(0);" onclick="alert('This system are coming soon')"><img src="<?= base_url("assets/imgs/mastercart.png"); ?>" alt=""></a></li>
							<li><input type="radio" name="payment_type" value="cashOnDelivery" checked> Pay when you get the package</li>
						</ul>
						<?php if ($codeDiscounts == 1) { ?>
							<div class="cf-title"><?= lang('discount_code') ?></div>
							<div class="col-md-6">
								<input type="text" placeholder="<?= lang('enter_discount_code') ?>" name="discountCode" value="<?= @$_POST['discountCode'] ?>">
							</div>
							<div class="col-md-6">
								<a href="javascript:void(0);" class="btn btn-default" onclick="checkDiscountCode()"><?= lang('check_code') ?></a>
							</div>
						<?php } ?>
						<?php foreach ($cartItems['array'] as $item) { ?>
							<input type="hidden" name="id[]" value="<?= $item['id'] ?>">
							<input type="hidden" name="quantity[]" value="<?= $item['num_added'] ?>">
						<?php } ?>
						<input type="hidden" class="final-amount" name="final_amount" value="<?= $cartItems['finalSum'] ?>">
						<input type="hidden" name="amount_currency" value="<?= CURRENCY ?>">
						<input type="hidden" name="discountAmount" value="">
						<input type="hidden" id="product-amt" value="<?= ((float)str_replace(",", "", $cartItems['finalSum'])  - $cartItems['shipping']) ?>">
						<input type="hidden" id="postInput" name="post_code">
						<button class="site-btn submit-order-btn" id="submit-order" type="submit">Place Order</button>
					</form>
					<br><br>
				<?php } else { ?>
                    <p class="promot-to-login">
                        <h4>Already registered? Please <a href="<?= LANG_URL . '/login' ?>"><?= lang('login') ?></a></h4><br><br>
                        <h4>New to <?= $companyName; ?>? Please <a href="<?= LANG_URL . '/register' ?>"><?= lang('register') ?></a></h4>
                    </p>
				<?php } ?>
			</div>
			<div class="col-lg-4 order-1 order-lg-2">
				<div class="checkout-cart">
					<h3><?= lang('shopping_cart') ?></h3>
					<ul class="product-list">
					<?php foreach ($cartItems['array'] as $item) { ?>
						<li>
							<div class="pl-thumb"><img src="<?= base_url('/attachments/'. SHOP_DIR .'/shop_images/' . $item['image']) ?>" alt=""></div>
							<h6><a href="<?= LANG_URL . '/' . $item['url'] ?>"><?= $item['title'] ?></a></h6>
							<p><?= CURRENCY . price_format($item['price']) ?></p>
						</li>
					<?php } ?>
					</ul>
					<ul class="price-list">
						<li><?= lang('total'); ?> <span><?= CURRENCY . price_format($cartItems['finalSum']) ?></span></li>
						<li>Shipping<span>free</span></li>
						<li class="total"><?= lang('total'); ?> <span><?= CURRENCY . price_format($cartItems['finalSum']) ?></span></li>
					</ul>
				</div>
			</div>
		</div>
    </div>
    <div id="loadingdiv">
        <img src="<?= base_url("template/imgs/loading.gif"); ?>" style="">
    </div>
<?php }
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
        $('#submit-order').css('pointer-events', 'none');
        $('#loadingdiv').show();
        $('#goOrder').submit();
    }
</script>