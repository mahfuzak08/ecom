<?php
defined('BASEPATH') OR exit('No direct script access allowed');
?>
<div class="container" id="shopping-cart">
    <h1><?= lang('wish_list') ?></h1>
    <hr>
    <?php
    if ($wishItems['array'] == null) {
        ?>
        <div class="alert alert-info"><?= lang('no_products_in_cart') ?></div>
        <?php
    } else {
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
        <?php if (isset($_SESSION['logged_user'])) { ?>
            <form action="" method="POST">
                <div class="table-responsive">
                    <table class="table table-bordered table-products">
                        <thead>
                            <tr>
                                <th><?= lang('product') ?></th>
                                <th><?= lang('title') ?></th>
                                <th><?= lang('quantity') ?></th>
                                <th><?= lang('original_price') ?></th>
                                <th><?= lang('your_price') ?></th>
                            </tr>
                        </thead>
                        <tbody>
                            <?php foreach ($wishItems['array'] as $item) { ?>
                                <tr>
                                    <td class="relative">
                                        <input type="hidden" name="id[]" value="<?= $item['id'] ?>">
                                        <input type="hidden" name="quantity[]" value="<?= $item['num_added'] ?>">
                                        <img class="product-image" src="<?= base_url('/attachments/'. SHOP_DIR .'/shop_images/' . $item['image']) ?>" alt="">
                                        <a href="javascript:void(0)" onclick="removeWishProduct('<?= strrev(base64_encode($item['id'].'@'.$item['num_added'])); ?>')" class="btn btn-xs btn-danger remove-product">
                                            <span class="glyphicon glyphicon-remove"></span>
                                        </a>
                                    </td>
                                    <td><a href="<?= LANG_URL . '/' . $item['url'] ?>"><?= $item['title'] ?></a></td>
                                    <td>
                                        <a class="btn  btn-xs btn-danger" onclick="removeWishProduct('<?= strrev(base64_encode($item['id'].'@1')); ?>')" href="javascript:void(0);">
                                            <span class="glyphicon glyphicon-minus"></span>
                                        </a>
                                        <span class="quantity-num">
                                            <?= $item['num_added'] ?>
                                        </span>
                                        <a class="btn btn-xs btn-primary wish-it" data-id="<?= strrev(base64_encode($item['id']."@".$item['title']."@".$item['url']."@".$item['image']."@".$item['price'])); ?>" href="javascript:void(0);">
                                            <span class="glyphicon glyphicon-plus"></span>
                                        </a>
                                    </td>
                                    <td><?= $item['price'] . CURRENCY ?></td>
                                    <td><input type="number" name="your_price[]" placeholder="Your your price"></td>
                                </tr>
                            <?php } ?>
                        </tbody>
                    </table>
                </div>
                <a href="<?= LANG_URL ?>" class="btn btn-primary go-shop">
                    <span class="glyphicon glyphicon-circle-arrow-left"></span>
                    <?= lang('back_to_shop') ?>
                </a>
                <button class="btn btn-primary pull-right" type="submit" name="wish_submit"><?= lang("wish_list_submit"); ?> <i class="fa fa-send" aria-hidden="true"></i></button>
            </form>
        <?php } else { ?>
                <p class="promot-to-login">
                    Already registered? Please <a href="<?= LANG_URL . '/login' ?>"><?= lang('login') ?></a><br>
                    New to <?= $companyName; ?>? Please <a href="<?= LANG_URL . '/register' ?>"><?= lang('register') ?></a>
                </p>
        <?php } ?>
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