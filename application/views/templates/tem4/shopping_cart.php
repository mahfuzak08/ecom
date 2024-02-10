<?php
defined('BASEPATH') OR exit('No direct script access allowed');
?>
<div class="container" id="shopping-cart">
    <h1><?= lang('shopping_cart') ?></h1>
    <hr>
    <?php
    if ($cartItems['array'] == null) {
        ?>
        <div class="alert alert-info"><?= lang('no_products_in_cart') ?></div>
        <?php
    } else {
        echo purchase_steps(1);
        ?>
        <div class="table-responsive">
            <table class="table table-bordered table-products">
                <thead>
                    <tr>
                        <th><?= lang('product'); ?></th>
                        <th><?= lang('title'); ?></th>
                        <th><?= lang('quantity'); ?></th>
                        <?php if($multiSize == 1) { ?>
                        <th><?= lang('available_size'); ?>
                        <?php } ?>
                        <th><?= lang('price'); ?></th>
                        <th><?= lang('total'); ?></th>
                    </tr>
                </thead>
                <tbody>
                    <?php foreach ($cartItems['array'] as $item) { ?>
                        <tr>
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
                                <a class="btn btn-xs btn-primary add-to-cart add2cart" data-id="<?= base64_encode($item['id']); ?>" data-title="<?= $item['title']; ?>" data-url="<?= $item['url']; ?>" data-img="<?= $item['image']; ?>" data-price="<?= $item['price']; ?>"  href="javascript:void(0);">
                                    <span class="glyphicon glyphicon-plus"></span>
                                </a>
                            </td>
                            <?php if($multiSize == 1 && $item['size'] != 'N' && $item['size'] != '') { ?>
                            <td>
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
                        <td><?= CURRENCY . price_format($cartItems['shipping']) ?></td>
                    </tr>
                    <tr>
                        <td colspan="5" class="text-right"><?= lang('total') ?></td>
                        <td><?= CURRENCY . price_format($cartItems['finalSum']) ?></td>
                    </tr>
                </tbody>
            </table>
        </div>
        <a href="<?= LANG_URL ?>" class="btn btn-primary go-shop">
            <span class="glyphicon glyphicon-circle-arrow-left"></span>
            <?= lang('back_to_shop') ?>
        </a>
        <a class="btn btn-primary go-checkout" href="<?= LANG_URL . '/checkout' ?>">
            <?= lang('checkout') ?> 
            <i class="fa fa-credit-card-alt" aria-hidden="true"></i>
        </a>
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