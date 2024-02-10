<?php
defined('BASEPATH') OR exit('No direct script access allowed');
if (count($sliderProducts) > 0) {
    ?>
    <div id="home-slider" class="carousel slide" data-ride="carousel">
        <ol class="carousel-indicators">
            <?php
            $i = 0;
            while ($i < count($sliderProducts)) {
                ?>
                <li data-target="#home-slider" data-slide-to="0" class="<?= $i == 0 ? 'active' : '' ?>"></li>
                <?php
                $i++;
            }
            ?>
        </ol>
        <div class="carousel-inner" role="listbox">
            <?php
            $i = 0;
            foreach ($sliderProducts as $article) {
                ?>
                <div class="item <?= $i == 0 ? 'active' : '' ?>" style="background-image: url('<?= base_url('attachments/'. SHOP_DIR .'/shop_images/' . $article['image']) ?>')">
                    <div class="container">
                        <div class="row">
                            <div class="col-sm-6">
                                <h3>
                                    <a href="<?= LANG_URL . '/' . $article['url'] ?>">
                                        <?= character_limiter($article['title'], 100) ?>
                                    </a>
                                </h3>
                                <div class="description">
                                    <?= character_limiter(strip_tags($article['basic_description']), 150) ?>
                                </div>
                                <a class="option add-to-cart" data-goto="<?= LANG_URL . '/checkout' ?>" href="javascript:void(0);" data-id="<?= $article['id'] ?>">
                                    <?= lang('buy_now') ?>
                                </a>
                            </div>
                            <div class="col-sm-6">

                            </div>
                        </div>
                    </div>
                </div>
                <?php
                $i++;
            }
            ?>
        </div>
        <a class="left carousel-control" href="#home-slider" role="button" data-slide="prev"><span class="glyphicon glyphicon-chevron-left"></span></a>
        <a class="right carousel-control" href="#home-slider" role="button" data-slide="next"><span class="glyphicon glyphicon-chevron-right"></span></a>
    </div>
<?php } ?>
<div class="container" id="home-page">
    <div class="row">
        <div class="col-md-3">
            <div class="filter-sidebar">
                <div class="title">
                    <span><?= lang('categories') ?></span>
                    <?php if (isset($_GET['category']) && $_GET['category'] != '') { ?>
                        <a href="javascript:void(0);" class="clear-filter" data-type-clear="category" data-toggle="tooltip" data-placement="right" title="<?= lang('clear_the_filter') ?>"><i class="fa fa-times" aria-hidden="true"></i></a>
                    <?php } ?>
                </div>
                <a href="javascript:void(0)" id="show-xs-nav" class="visible-xs visible-sm">
                    <span class="show-sp"><?= lang('showXsNav') ?><i class="fa fa-arrow-circle-o-down" aria-hidden="true"></i></span>
                    <span class="hidde-sp"><?= lang('hideXsNav') ?><i class="fa fa-arrow-circle-o-up" aria-hidden="true"></i></span>
                </a>
                <div id="nav-categories">
                    <?php

                    function loop_tree($pages, $is_recursion = false)
                    {
                        ?>
                        <ul class="<?= $is_recursion === true ? 'children' : 'parent' ?>">
                            <?php
                            foreach ($pages as $page) {
                                $children = false;
                                if (isset($page['children']) && !empty($page['children'])) {
                                    $children = true;
                                }
                                ?>
                                <li>
                                    <?php if ($children === true) {
                                        ?>
                                        <i class="fa fa-chevron-right" aria-hidden="true"></i>
                                    <?php } else { ?>
                                        <i class="fa fa-circle-o" aria-hidden="true"></i>
                                    <?php } ?>
                                    <a href="javascript:void(0);" data-categorie-id="<?= $page['id'] ?>" class="go-category left-side <?= isset($_GET['category']) && $_GET['category'] == $page['id'] ? 'selected' : '' ?>">
                                        <?= $page['name'] ?>
                                    </a>
                                    <?php
                                    if ($children === true) {
                                        loop_tree($page['children'], true);
                                    } else {
                                        ?>
                                    </li>
                                    <?php
                                }
                            }
                            ?>
                        </ul>
                        <?php
                        if ($is_recursion === true) {
                            ?>
                            </li>
                            <?php
                        }
                    }

                    loop_tree($home_categories);
                    ?>
                </div>
            </div>
            <?php if ($showBrands == 1) { ?>
                <div class="filter-sidebar">
                    <div class="title">
                        <span><?= lang('brands') ?></span>
                        <?php if (isset($_GET['brand_id']) && $_GET['brand_id'] != '') { ?>
                            <a href="javascript:void(0);" class="clear-filter" data-type-clear="brand_id" data-toggle="tooltip" data-placement="right" title="<?= lang('clear_the_filter') ?>"><i class="fa fa-times" aria-hidden="true"></i></a>
                        <?php } ?>
                    </div>
                    <ul>
                        <?php foreach ($brands as $brand) { ?>
                            <li>
                                <i class="fa fa-chevron-right" aria-hidden="true"></i> <a href="javascript:void(0);" data-brand-id="<?= $brand['brand_id'] ?>" class="brand <?= isset($_GET['brand_id']) && $_GET['brand_id'] == $brand['brand_id'] ? 'selected' : '' ?>"><?= $brand['name'] ?></a>
                            </li>
                        <?php } ?>
                    </ul>
                </div>
            <?php } if ($multiVendor == 1) { ?>
                <div class="filter-sidebar">
                    <div class="title">
                        <span><?= lang('vendor') ?></span>
                        <?php if (isset($_GET['vendor_id']) && $_GET['vendor_id'] != '') { ?>
                            <a href="javascript:void(0);" class="clear-filter" data-type-clear="vendor_id" data-toggle="tooltip" data-placement="right" title="<?= lang('clear_the_filter') ?>"><i class="fa fa-times" aria-hidden="true"></i></a>
                        <?php } ?>
                    </div>
                    <ul>
                        <?php foreach ($vendor as $row) { ?>
                            <li>
                                <i class="fa fa-chevron-right" aria-hidden="true"></i> <a href="javascript:void(0);" data-vendor-id="<?= $row['id'] ?>" class="vendor <?= isset($_GET['vendor_id']) && $_GET['vendor_id'] == $row['id'] ? 'selected' : '' ?>"><?= $row['name'] ?></a>
                            </li>
                        <?php } ?>
                    </ul>
                </div>
            <?php } /*if ($showOutOfStock == 1) { ?>
                <div class="filter-sidebar">
                    <div class="title">
                        <span><?= lang('store') ?></span>
                        <?php if (isset($_GET['in_stock']) && $_GET['in_stock'] != '') { ?>
                            <a href="javascript:void(0);" class="clear-filter" data-type-clear="in_stock" data-toggle="tooltip" data-placement="right" title="<?= lang('clear_the_filter') ?>"><i class="fa fa-times" aria-hidden="true"></i></a>
                        <?php } ?>
                    </div>
                    <ul>
                        <li>
                            <a href="javascript:void(0);" data-in-stock="1" class="in-stock <?= isset($_GET['in_stock']) && $_GET['in_stock'] == '1' ? 'selected' : '' ?>"><?= lang('in_stock') ?> (<?= $countQuantities['in_stock'] ?>)</a>
                        </li>
                        <li>
                            <a href="javascript:void(0);" data-in-stock="0" class="in-stock <?= isset($_GET['in_stock']) && $_GET['in_stock'] == '0' ? 'selected' : '' ?>"><?= lang('out_of_stock') ?> (<?= $countQuantities['out_of_stock'] ?>)</a>
                        </li>
                    </ul>
                </div>
            <?php }*/ ?>
            <?php if ($shippingOrder != 0 && $shippingOrder != null) { ?>
                <div class="filter-sidebar">
                    <img style="width:100%" src="<?= site_url("template/imgs/free-shipping.gif"); ?>">
                    <div class="shippingfree"><?= 'Order more than <br>'. $shippingOrder .' '. CURRENCY .' & get' ?></div>
                </div>
            <?php } ?>
            <?php if($siteAPK != ""): ?>
                <div class="filter-sidebar hidden-xs">
                    <a href="<?= base_url("attachments/". SHOP_DIR ."/site_app/".$siteAPK); ?>" target="_blank">
                        <img src="<?= base_url('assets/imgs/apk.png') ?>" alt="APK file" class="img-responsive">
                    </a>
                </div>
            <?php endif; ?>
            <?php if($officeTimeStart != "" && $officeTimeEnd != "") { ?>
                <div class="filter-sidebar">
                    <div class="title">Office Time</div>
                    <div class="info">Opening Time: <?= $officeTimeStart; ?></div>
                    <div class="info">Closing Time: <?= $officeTimeEnd; ?></div>
                </div>
			<?php } ?>
        </div>
        <div class="col-md-9 eqHeight" id="products-side">
            <div class="alone title">
                <span><?= lang('products') ?></span>
            </div>
            <div class="product-sort gradient-color">
                <div class="row">
                    <div class="ord col-sm-4">
                        <div class="form-group">
                            <select class="selectpicker order form-control" data-order-to="order_new">
                                <option <?= isset($_GET['order_new']) && $_GET['order_new'] == "desc" ? 'selected' : '' ?> <?= !isset($_GET['order_new']) || $_GET['order_new'] == "" ? 'selected' : '' ?> value="desc"><?= lang('new') ?> </option>
                                <option <?= isset($_GET['order_new']) && $_GET['order_new'] == "asc" ? 'selected' : '' ?> value="asc"><?= lang('old') ?> </option>
                            </select>
                        </div>
                    </div>
                    <div class="ord col-sm-4">
                        <div class="form-group">
                            <select class="selectpicker order form-control" data-order-to="order_price" title="<?= lang('price_title') ?>..">
                                <option label="<?= lang('not_selected') ?>"></option>
                                <option <?= isset($_GET['order_price']) && $_GET['order_price'] == "asc" ? 'selected' : '' ?> value="asc"><?= lang('price_low') ?> </option>
                                <option <?= isset($_GET['order_price']) && $_GET['order_price'] == "desc" ? 'selected' : '' ?> value="desc"><?= lang('price_high') ?> </option>
                            </select>
                        </div>
                    </div>
                    <div class="ord col-sm-4">
                        <div class="form-group">
                            <select class="selectpicker order form-control" data-order-to="order_procurement" title="<?= lang('procurement_title') ?>..">
                                <option label="<?= lang('not_selected') ?>"></option>
                                <option <?= isset($_GET['order_procurement']) && $_GET['order_procurement'] == "desc" ? 'selected' : '' ?> value="desc"><?= lang('procurement_desc') ?> </option>
                                <option <?= isset($_GET['order_procurement']) && $_GET['order_procurement'] == "asc" ? 'selected' : '' ?> value="asc"><?= lang('procurement_asc') ?> </option>
                            </select>
                        </div>
                    </div>
                </div>
            </div>
            <?php
            if (!empty($products)) {
                $load::getProducts($products, 'col-xs-6 col-sm-4 col-md-3', false);
            } else {
                ?>
                <script>
                    $(document).ready(function () {
                        ShowNotificator('alert-info', '<?= lang('no_results') ?>');
                    });
                </script>
                <?php
            }
            ?>
        </div>
    </div>
    <?php if ($links_pagination != '') { ?>
        <div class="row">
            <div class="col-sm-6 col-sm-offset-3">
                <?= $links_pagination ?>
            </div>
        </div>
    <?php } ?>
</div>