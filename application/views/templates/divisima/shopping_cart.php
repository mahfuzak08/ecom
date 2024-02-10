<?php
defined('BASEPATH') OR exit('No direct script access allowed');
?>
<div class="container" id="shopping-cart">
    <br>
    <?php
    if (gettype($cartItems) === "integer") {
        ?>
        <br>
        <div class="alert alert-info"><?= lang('no_products_in_cart') ?></div>
        <br><br>
        <?php
    } else {
        echo purchase_steps(1);
        ?>
        <div class="container">
			<div class="row">
				<div class="col-lg-8">
					<div class="cart-table">
						<h3><?= lang('shopping_cart') ?></h3>
						<div class="cart-table-warp">
							<table>
							<thead>
								<tr>
									<th class="product-th"><?= lang('product'); ?></th>
									<th class="quy-th"><?= lang('quantity'); ?></th>
                                    <?php if($multiSize == 1) { ?>
									<th class="size-th"><?= lang('available_size'); ?></th>
                                    <?php } ?>
									<th class="total-th"><?= lang('total'); ?></th>
								</tr>
							</thead>
							<tbody>
                            <?php foreach ($cartItems['array'] as $item) { ?>
								<tr>
									<td class="product-col">
                                        <input type="hidden" name="id[]" value="<?= $item['id'] ?>">
                                        <input type="hidden" name="quantity[]" value="<?= $item['num_added'] ?>">
										<a href="javascript:void(0)" onclick="removeProduct('<?= base64_encode($item['id'].'@'.$item['num_added']); ?>')" class="remove-product">X</a>
                                        <img src="<?= base_url('/attachments/'. SHOP_DIR .'/shop_images/' . $item['image']) ?>" alt="">
										<div class="pc-title">
											<h4><a href="<?= LANG_URL . '/' . $item['url'] ?>"><?= $item['title'] ?></a></h4>
											<p><?= CURRENCY . price_format($item['price']) ?></p>
										</div>
									</td>
									<td class="quy-col">
										<div class="quantity">
					                        <div class="pro-qty">
												<input type="text" value="<?= price_format($item['num_added'],0) ?>">
											</div>
                    					</div>
									</td>
                                    <?php if($multiSize == 1 && $item['size'] != 'N' && $item['size'] != '') { ?>
                                    <td class="size-col"><h4>
                                        <?php 
                                        $sizes = explode(";", $item['size']);
                                        for($i=0; $i<count($sizes); $i++){
                                            $ss = explode("x", $sizes[$i]); ?>
                                            <span onclick="update_item_size('<?= base64_encode($item['id'].'@'.$ss[0]); ?>')" class="size <?= ($item['size_select'] == $ss[0]) ? 'select' : '';?>"><?= $ss[0]; ?></span>
                                            <?php
                                        }
                                        ?></h4>
                                    </td>
                                    <?php } elseif($multiSize == 1 && ($item['size'] == 'N' || $item['size'] == '')){ ?>
                                        <td class="size-col"></td><?php 
                                    } ?>
									<td class="total-col"><h4><?= CURRENCY . price_format($item['sum_price']) ?></h4></td>
								</tr>
                            <?php } ?>
							</tbody>
						</table>
						</div>
						<div class="total-cost">
							<h6><?= lang('total'); ?> <span><?= CURRENCY . price_format($cartItems['finalSum']) ?></span></h6>
						</div>
					</div>
				</div>
				<div class="col-lg-4 card-right">
					<!--
                    <form class="promo-code-form">
						<input type="text" placeholder="Enter promo code">
						<button>Submit</button>
					</form>
                    -->
					<a href="<?= LANG_URL . '/checkout' ?>" class="site-btn">Proceed to checkout</a>
					<a href="<?= LANG_URL . '/' ?>" class="site-btn sb-dark">Continue shopping</a>
				</div>
			</div>
		</div>
        <br><br>
    <?php } ?>
</div>
<?php
if ($this->session->flashdata('deleted')) {
    ?>
    <script>
        $(document).ready(function () {
            ShowNotificator('alert-info', '<?= $this->session->flashdata('deleted') ?>');
        });
    </script>
<?php } ?>