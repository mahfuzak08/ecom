<link rel="stylesheet" href="<?= base_url('assets/bootstrap-select-1.12.1/bootstrap-select.min.css') ?>">
<link href="<?= base_url('assets/css/bootstrap-datepicker.min.css') ?>" rel="stylesheet">
<div class="content orders-page">
    <form class="row" action="" method="POST">
        <div class="form-group col-xs-3">
            <label>Order from date</label>
            <input class="form-control datepicker" name="valid_from_date" value="<?= @$fd ?>" autocomplete="off" type="text">
        </div>
        <div class="form-group col-xs-3">
            <label>Order to date</label>
            <input class="form-control datepicker" name="valid_to_date" value="<?= @$td ?>" autocomplete="off" type="text">
        </div>
        <div class="form-group col-xs-3">
            <input class="btn btn-success" name="filter_by_date" value="Search Now" type="submit" style="margin-top:20px;">
            <a href="<?= site_url("vendor/orders"); ?>" class="btn btn-success" style="margin-top:20px;">View All</a>
        </div>
    </form>
    <table class="table">
        <thead class="blue-grey lighten-4">
            <tr>
                <th>#</th>
                <th><?= lang('time_created') ?></th>
                <th><?= lang('order_type') ?></th>
                <th><?= lang('phone') ?></th>
                <th><?= lang('status') ?></th>
                <th><?= lang('total') ?></th>
                <th class="text-right"><i class="fa fa-list" aria-hidden="true"></i></th>
            </tr>
        </thead>
        <tbody>
            <?php
            $i = 0; $total = 0;
            foreach ($orders as $order) {
                $per_row_total = 0;
                ?>
                <tr class="tr-more" data-tr="<?= $i ?>">
                    <td colspan="7">
                        <div class="row">
                            <div class="col-sm-6">
                                <ul>
                                    <li>
                                        <b><?= lang('first_name') ?></b> <span><?= $order['first_name'] ?></span>
                                    </li>
                                    <!--<li>-->
                                    <!--    <b><?= lang('last_name') ?></b> <span><?= $order['last_name'] ?></span>-->
                                    <!--</li>-->
                                    <li>
                                        <b><?= lang('email') ?></b> <span><?= $order['email'] ?></span>
                                    </li>
                                    <li>
                                        <b><?= lang('phone') ?></b> <span><?= $order['phone'] ?></span>
                                    </li>
                                    <li>
                                        <b><?= lang('address') ?></b> <span><?= $order['address'] ?></span>
                                    </li>
                                    <li>
                                        <b>Delevery Location</b> <span><?= $order['city'] ?></span>
                                    </li>
                                    <li>
                                        <b>Delevery Cost</b> <span><?= $order['post_code'] ?></span>
                                    </li>
                                    <li>
                                        <b><?= lang('notes') ?></b> <span><?= $order['notes'] ?></span>
                                    </li>
                                </ul>
                            </div>
                            <div class="col-sm-6">
                                <?php
                                $product = unserialize($order['products']);
                                foreach ($product as $prod_id => $prod_qua) {
                                    $productInfo = modules::run('vendor/orders/getProductInfo', $prod_id, $order['vendor_id']);
                                    ?>
                                    <div class="product">
                                        <a href="" target="_blank">
                                            <img src="<?= base_url('/attachments/'. SHOP_DIR .'/shop_images/' . $productInfo['image']) ?>" alt="">
                                            <div class="info">
                                                <span class="qiantity">
                                                    <b><?= lang('quantity') ?></b> <?= $prod_qua ?><br>
                                                    <?= $productInfo['price']; ?>
                                                    <?php 
                                                        $per_row_total += $productInfo['price'];
                                                        $total += $productInfo['price'];
                                                    ?>
                                                </span>
                                            </div>
                                            <div class="clearfix"></div>
                                        </a>
                                    </div>
                                <?php } ?>
                            </div>
                        </div>
                    </td>
                </tr>
                <tr>
                    <td><?= $order['order_id'] ?></td>
                    <td><?= date('d.m.Y', $order['date']) ?></td>
                    <td><?= $order['payment_type'] ?></td>
                    <td><?= $order['phone'] ?></td>
                    <td>
                        <select class="selectpicker change-ord-status" data-ord-id="<?= $order['id'] ?>" data-style="btn-green"> 
                            <option <?= $order['processed'] == 0 ? 'selected="selected"' : '' ?> value="0"><?= lang('new') ?></option>
                            <option <?= $order['processed'] == 1 ? 'selected="selected"' : '' ?> value="1"><?= lang('processed') ?></option>
                            <option <?= $order['processed'] == 2 ? 'selected="selected"' : '' ?> value="2"><?= lang('rejected') ?></option>
                        </select>
                    </td>
                    <td class="text-right"><b><?= $per_row_total .' '.CURRENCY ?></b></td>
                    <td class="text-right">
                        <a href="javascript:void(0);" class="btn btn-sm btn-green show-more" data-show-tr="<?= $i ?>">
                            <i class="fa fa-chevron-down" aria-hidden="true"></i>
                            <i class="fa fa-chevron-up" aria-hidden="true"></i>
                        </a>
                    </td>
                </tr>
                <?php
                $i++;
            }
            ?>
        </tbody>
        <tfoot>
            <tr>
                <td colspan=6 align=right><b><?= $total .' '.CURRENCY ?></b></td>
                <td>&nbsp;</td>
            </tr>
        </tfoot>
    </table>
</div>
<script src="<?= base_url('assets/bootstrap-select-1.12.1/js/bootstrap-select.min.js') ?>"></script>
<script src="<?= base_url('assets/js/bootstrap-datepicker.min.js') ?>"></script>
<script>
    $('.datepicker').datepicker({
        format: "dd.mm.yyyy"
    });
</script>