<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Loop
{

    private static $CI;

    public function __construct()
    {
        self::$CI = & get_instance();
    }

    static function getCartItems($cartItems)
    {
        if (!empty($cartItems['array'])) {
            ?>
            <li class="cleaner text-right">
                <a href="javascript:void(0);" class="btn-blue-round" onclick="clearCart()">
                    <?= lang('clear_all') ?>
                </a>
            </li>
            <li class="divider"></li>
            <?php
            foreach ($cartItems['array'] as $cartItem) {
                $u_path = 'attachments/'. SHOP_DIR .'/shop_images/';
                if ($cartItem['image'] != null && file_exists($u_path . $cartItem['image'])) {
                    $image = base_url($u_path . $cartItem['image']);
                } else {
                    $image = base_url('attachments/no-image.png');
                }
                ?>
                <li class="shop-item" data-artticle-id="<?= $cartItem['id'] ?>">
                    <span class="num_added hidden"><?= $cartItem['num_added'] ?></span>
                    <div class="item">
                        <div class="item-in">
                            <div class="left-side">
                                <img src="<?= $image; ?>" alt="" />
                            </div>
                            <div class="right-side">
                                <a href="<?= LANG_URL . '/' . $cartItem['url'] ?>" class="item-info">
                                    <span><?= $cartItem['title'] ?></span>
                                    <span class="prices">
                                        <?=
                                        $cartItem['num_added'] == 1 ? $cartItem['price'] : '<span class="num-added-single">'
                                                . $cartItem['num_added'] . '</span> x <span class="price-single">'
                                                . $cartItem['price'] . '</span> - <span class="sum-price-single">'
                                                . $cartItem['sum_price'] . '</span>'
                                        ?>
                                    </span>
                                    <span class="currency"><?= CURRENCY ?></span>
                                </a>
                            </div>
                        </div>
                        <div class="item-x-absolute">
                            <button class="btn btn-xs btn-danger pull-right" onclick="removeProduct(<?= $cartItem['id'] ?>)">
                                x
                            </button>
                        </div>
                    </div>
                </li>
                <?php
            }
            ?>
            <li class="divider"></li>
            <li class="text-center">
                <a class="go-checkout btn btn-default btn-sm" href="<?= LANG_URL . '/checkout' ?>">
                    <?=
                    !empty($cartItems['array']) ? '<i class="fa fa-check"></i> '
                            . lang('checkout') . ' - <span class="finalSum">' . $cartItems['finalSum']
                            . '</span>' . CURRENCY : '<span class="no-for-pay">' . lang('no_for_pay') . '</span>'
                    ?>
                </a>
            </li>
        <?php } else {
            ?>
            <li class="text-center"><?= lang('no_products') ?></li>
            <?php
        }
    }

    static public function getProducts($products, $classes = '', $carousel = false)
    {
        $templete = self::$CI->load->get_var('template');
        $has_wish_it_btn = self::$CI->load->get_var('wish_list') == 1 ? 'wish-it-btn-active' : '';
        if($templete == 'food'){
            if ($carousel == true) {
                ?>
                <div class="carousel slide" id="small_carousel" data-ride="carousel" data-interval="3000">
                    <ol class="carousel-indicators">
                        <?php
                        $i = 0;
                        while ($i < count($products)) {
                            if ($i == 0)
                                $active = 'active';
                            else
                                $active = '';
                            ?>
                            <li data-target="#small_carousel" data-slide-to="<?= $i ?>" class="<?= $active ?>"></li>
                            <?php
                            $i++;
                        }
                        ?>
                    </ol>
                    <div class="carousel-inner">
                        <?php
                    }
                    $i = 0;
                    foreach ($products as $article) {
                        if ($i == 0 && $carousel == true) {
                            $active = 'active';
                        } else {
                            $active = '';
                        }
                        $u_path = 'attachments/'. SHOP_DIR .'/shop_images/';
                        if ($article['image'] != null && file_exists($u_path . $article['image'])) {
                            $image = base_url($u_path . $article['image']);
                        } else {
                            $image = base_url('attachments/no-image.png');
                        }
                        ?>
                        <div class="product-list <?= $has_wish_it_btn; ?> <?= $carousel == true ? 'item' : '' ?> <?= $classes ?> <?= $active ?>">
                            <div class="inner">
                                <div class="img-container">
                                    <a href="<?= LANG_URL . '/' . $article['url'] ?>">
                                        <img src="<?= $image; ?>" alt="<?= str_replace('"', "'", $article['title']) ?>">
                                    </a>
                                </div>
                                <h2>
                                    <a href="<?= LANG_URL . '/' . $article['url'] ?>"><?= character_limiter($article['title'], 70) ?></a>
                                </h2>
                                <div class="price">
                                    <span><?= lang('price') ?>: <span><?= CURRENCY ?> <?php echo price_format($article['price']); ?></span></span>
                                    <?php
                                    if ($article['old_price'] != '' && $article['old_price'] != 0 && $article['price'] != '' && $article['price'] != 0) {
                                        $percent_friendly = price_format((($article['old_price'] - $article['price']) / $article['old_price']) * 100) . '%';
                                        ?>
                                        <span class="price-down"><?= $percent_friendly ?></span>
                                    <?php } ?>
                                </div>
                                <div class="price-discount <?= $article['old_price'] == '' ? 'invisible hidden' : '' ?>">
                                    <?= lang('old_price') ?>: <span><?= $article['old_price'] != '' ? price_format($article['old_price']) . CURRENCY : '' ?></span>
                                </div>
                                <?php if(self::$CI->load->get_var('multiVendor') == 1): ?>
                                <div class="vendor"><?= $article['vendor_name']; ?></div>
                                <?php endif; ?>
                                <?php if (self::$CI->load->get_var('publicQuantity') == 1) { ?>
                                    <div class="quantity">
                                        <?= lang('in_stock') ?>: <span><?= $article['quantity'] ?></span>
                                    </div>
                                <?php } if (self::$CI->load->get_var('moreInfoBtn') == 1) { ?>
                                    <a href="<?= $article['vendor_url'] == null ? LANG_URL . '/' . $article['url'] : LANG_URL . '/' . $article['vendor_url'] . '/' . $article['url'] ?>" class="info-btn gradient-color">
                                        <span class="text-to-bg"><?= lang('info_product_list') ?></span>
                                    </a>
                                <?php } ?>
                                <?php if ($article['quantity'] > 0): ?>
                                    <div class="add-to-cart">
                                        <a href="javascript:void(0);" class="add-to-cart add2cart btn-add" data-goto="" data-id="<?= base64_encode($article['id']); ?>" data-title="<?= $article['title']; ?>" data-url="<?= $article['url']; ?>" data-img="<?= $article['image']; ?>" data-size="<?= $article['size']; ?>" data-price="<?= $article['price']; ?>">
                                            <img class="loader" src="<?= base_url('assets/imgs/ajax-loader.gif') ?>" alt="Loding">
                                            <span class="text-to-bg"><?= lang('add_to_cart') ?></span>
                                        </a>
                                    </div>
                                    <div class="add-to-cart">
                                        <a href="javascript:void(0);" class="add-to-cart buy-now btn-add more-blue" data-goto="<?= LANG_URL . '/checkout' ?>" data-id="<?= base64_encode($article['id']); ?>" data-title="<?= $article['title']; ?>" data-url="<?= $article['url']; ?>" data-img="<?= $article['image']; ?>" data-size="<?= $article['size']; ?>" data-price="<?= $article['price']; ?>">
                                            <img class="loader" src="<?= base_url('assets/imgs/ajax-loader.gif') ?>" alt="Loding">
                                            <span class="text-to-bg"><?= lang('buy_now') ?></span>
                                        </a>
                                    </div>
                                    <?php if(self::$CI->load->get_var('wish_list') == 1): ?>
                                    <div class="add-to-cart">
                                        <a href="javascript:void(0);" class="add-to-cart btn-add wish-it" data-goto="" data-id="<?= base64_encode($article['id']); ?>" data-title="<?= $article['title']; ?>" data-url="<?= $article['url']; ?>" data-img="<?= $article['image']; ?>" data-size="<?= $article['size']; ?>" data-price="<?= $article['price']; ?>">
                                            <span class="text-to-bg"><?= lang('wish_list'); ?></span>
                                        </a>
                                    </div>
                                    <?php endif; ?>
                                <?php else: ?>
                                    <div class="alert alert-info"><?= lang('out_of_stock_product') ?></div>
                                <?php endif; ?>
                            </div>
                        </div>
                        <?php
                        $i++;
                    }
                    if ($carousel == true) {
                        ?>
                    </div>
                    <a class="left carousel-control" href="#small_carousel" role="button" data-slide="prev">
                        <i class="fa fa-5x fa-angle-left" aria-hidden="true"></i>
                    </a>
                    <a class="right carousel-control" href="#small_carousel" role="button" data-slide="next">
                        <i class="fa fa-5x fa-angle-right" aria-hidden="true"></i>
                    </a>
                </div>
                <?php
            }
        }elseif($templete == 'tem3'){
            $i = 0;
            foreach ($products as $article) {
                $u_path = 'attachments/'. SHOP_DIR .'/shop_images/';
                if ($article['image'] != null && file_exists($u_path . $article['image'])) {
                    $image = base_url($u_path . $article['image']);
                } else {
                    $image = base_url('attachments/no-image.png');
                }
                ?>
                <div class="<?= $classes ?>"> 
                    <a href="<?= LANG_URL . '/' . $article['url'] ?>">
                        <div class="inner_content clearfix">
                            <div class="product_image">
                                <img src="<?= $image; ?>" alt="<?= str_replace('"', "'", $article['title']) ?>"/>
                            </div>
                            <div class="sale-box"><span class="on_sale title_shop">New</span></div>	
                            <div class="price">
                                <div class="cart-left">
                                    <p class="title"><?= character_limiter($article['title'], 70) ?></p>
                                    <div class="price1">
                                        <span class="actual"><?= CURRENCY ?><?php echo price_format($article['price']); ?></span>
                                    </div>
                                </div>
                                <div class="cart-right"> </div>
                                <div class="clear"></div>
                            </div>				
                        </div>
                    </a>
                </div><?php 
            }
        }
        elseif($templete == 'tem4'){
            if ($carousel == true) {
                ?>
                <div class="carousel slide" id="small_carousel" data-ride="carousel" data-interval="3000">
                    <ol class="carousel-indicators">
                        <?php
                        $i = 0;
                        while ($i < count($products)) {
                            if ($i == 0)
                                $active = 'active';
                            else
                                $active = '';
                            ?>
                            <li data-target="#small_carousel" data-slide-to="<?= $i ?>" class="<?= $active ?>"></li>
                            <?php
                            $i++;
                        }
                        ?>
                    </ol>
                    <div class="carousel-inner">
                        <?php
                    }
                    $i = 0;
                    foreach ($products as $article) {
                        if ($i == 0 && $carousel == true) {
                            $active = 'active';
                        } else {
                            $active = '';
                        }
                        $u_path = 'attachments/'. SHOP_DIR .'/shop_images/';
                        if ($article['image'] != null && file_exists($u_path . $article['image'])) {
                            $image = base_url($u_path . $article['image']);
                        } else {
                            $image = base_url('attachments/no-image.png');
                        }
                        ?>
                        <div class="product-list <?= $carousel == true ? 'item' : '' ?> <?= $classes ?> <?= $active ?>">
                            <div class="inner">
                                <div class="img-container">
                                    <a href="<?= LANG_URL . '/' . $article['url'] ?>">
                                        <img src="<?= $image; ?>" alt="<?= str_replace('"', "'", $article['title']) ?>">
                                    </a>
                                </div>
                                <h2>
                                    <a href="<?= LANG_URL . '/' . $article['url'] ?>"><?= character_limiter($article['title'], 70) ?></a>
                                </h2>
                                <div class="price">
                                    <span><?= lang('price') ?>: <span><?= CURRENCY ?> <?php echo price_format($article['price']); ?></span></span>
                                    <?php
                                    if ($article['old_price'] != '' && $article['old_price'] != 0 && $article['price'] != '' && $article['price'] != 0) {
                                        $percent_friendly = price_format((($article['old_price'] - $article['price']) / $article['old_price']) * 100) . '%';
                                        ?>
                                        <span class="price-down"><?= $percent_friendly ?></span>
                                    <?php } ?>
                                </div>
                                <div class="price-discount <?= $article['old_price'] == '' ? 'invisible hidden' : '' ?>">
                                    <?= lang('old_price') ?>: <span><?= $article['old_price'] != '' ? price_format($article['old_price']) . CURRENCY : '' ?></span>
                                </div>
                                <?php if(self::$CI->load->get_var('multiVendor') == 1): ?>
                                <div class="vendor"><?= $article['vendor_name']; ?></div>
                                <?php endif; ?>
                                <?php if (self::$CI->load->get_var('publicQuantity') == 1) { ?>
                                    <div class="quantity">
                                        <?= lang('in_stock') ?>: <span><?= $article['quantity'] ?></span>
                                    </div>
                                <?php } if (self::$CI->load->get_var('moreInfoBtn') == 1) { ?>
                                    <a href="<?= $article['vendor_url'] == null ? LANG_URL . '/' . $article['url'] : LANG_URL . '/' . $article['vendor_url'] . '/' . $article['url'] ?>" class="info-btn gradient-color">
                                        <span class="text-to-bg"><?= lang('info_product_list') ?></span>
                                    </a>
                                <?php } ?>
                                <?php if ($article['quantity'] > 0): ?>
                                    <div class="add-to-cart">
                                        <a href="javascript:void(0);" class="add-to-cart add2cart btn-add" data-goto="" data-id="<?= base64_encode($article['id']); ?>" data-title="<?= $article['title']; ?>" data-url="<?= $article['url']; ?>" data-img="<?= $article['image']; ?>" data-size="<?= $article['size']; ?>" data-price="<?= $article['price']; ?>">
                                            <img class="loader" src="<?= base_url('assets/imgs/ajax-loader.gif') ?>" alt="Loding">
                                            <span class="text-to-bg"><?= lang('add_to_cart') ?></span>
                                        </a>
                                    </div>
                                    <div class="add-to-cart">
                                        <a href="javascript:void(0);" class="add-to-cart buy-now btn-add more-blue" data-goto="<?= LANG_URL . '/checkout' ?>" data-id="<?= base64_encode($article['id']); ?>" data-title="<?= $article['title']; ?>" data-url="<?= $article['url']; ?>" data-img="<?= $article['image']; ?>" data-size="<?= $article['size']; ?>" data-price="<?= $article['price']; ?>">
                                            <img class="loader" src="<?= base_url('assets/imgs/ajax-loader.gif') ?>" alt="Loding">
                                            <span class="text-to-bg"><?= lang('buy_now') ?></span>
                                        </a>
                                    </div>
                                    <?php if(self::$CI->load->get_var('wish_list') == 1): ?>
                                    <div class="add-to-cart">
                                        <a href="javascript:void(0);" class="add-to-cart btn-add wish-it" data-goto="" data-id="<?= base64_encode($article['id']); ?>" data-title="<?= $article['title']; ?>" data-url="<?= $article['url']; ?>" data-img="<?= $article['image']; ?>" data-size="<?= $article['size']; ?>" data-price="<?= $article['price']; ?>">
                                            <span class="text-to-bg"><?= lang('wish_list'); ?></span>
                                        </a>
                                    </div>
                                    <?php endif; ?>
                                <?php else: ?>
                                    <div class="alert alert-info"><?= lang('out_of_stock_product') ?></div>
                                <?php endif; ?>
                            </div>
                        </div>
                        <?php
                        $i++;
                    }
                    if ($carousel == true) {
                        ?>
                    </div>
                    <a class="left carousel-control" href="#small_carousel" role="button" data-slide="prev">
                        <i class="fa fa-5x fa-angle-left" aria-hidden="true"></i>
                    </a>
                    <a class="right carousel-control" href="#small_carousel" role="button" data-slide="next">
                        <i class="fa fa-5x fa-angle-right" aria-hidden="true"></i>
                    </a>
                </div>
                <?php
            }
        }
    }
    
    static public function getLatestProducts($products, $classes = '', $carousel = false)
    {
        $i = 0;
        foreach ($products as $article) {
            if ($carousel == true) { ?><div class="<?= $classes; ?>"> <?php }
            if ($i == 0 && $carousel == true) {
                $active = 'active';
            } else {
                $active = '';
            }
            $u_path = 'attachments/'. SHOP_DIR .'/shop_images/';
            if ($article['image'] != null && file_exists($u_path . $article['image'])) {
                $image = base_url($u_path . $article['image']);
            } else {
                $image = base_url('attachments/no-image.png');
            }
            ?>
            <div class="product-item">
                <div class="pi-pic">
					<a href="<?= LANG_URL . '/' . $article['url'] ?>">
						<img src="<?= $image; ?>" alt="<?= str_replace('"', "'", $article['title']) ?>">
					</a>
                    <div class="pi-links">
						<?php if ($article['quantity'] > 0): ?>
                        <a  href="javascript:void(0);" class="add-card add2cart" data-goto="" data-id="<?= base64_encode($article['id']); ?>" data-title="<?= $article['title']; ?>" data-url="<?= $article['url']; ?>" data-img="<?= $article['image']; ?>" data-size="<?= $article['size']; ?>" data-price="<?= $article['price']; ?>"><i class="flaticon-bag"></i><span>ADD TO CART</span></a>
						<?php endif; ?>
                        <!--<a href="#" class="wishlist-btn"><i class="flaticon-heart"></i></a>-->
                    </div>
                </div>
                <div class="pi-text">
                    <h6><?= CURRENCY ?> <?= $article['price'] != '' ? price_format($article['price']) : 0 ?></h6>
                    <a href="<?= LANG_URL . '/' . $article['url'] ?>">
						<p><?= character_limiter($article['title'], 70) ?></p>
					</a>
                </div>
            </div>
            <?php
            $i++;
            if ($carousel == true) { ?> </div> <?php }
        }
    }

    static public function getFeaturedProducts($products, $classes = ''){
        $templete = self::$CI->load->get_var('template');
        if($templete == 'tem3'){
            $i = 0;
            foreach ($products as $article) {
                $u_path = 'attachments/'. SHOP_DIR .'/shop_images/';
                if ($article['image'] != null && file_exists($u_path . $article['image'])) {
                    $image = base_url($u_path . $article['image']);
                } else {
                    $image = base_url('attachments/no-image.png');
                }
                ?>
                <img src="<?= $image; ?>" alt="<?= str_replace('"', "'", $article['title']) ?>"/>
                <?php 
            }
        }
    }
}
