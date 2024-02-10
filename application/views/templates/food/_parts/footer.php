<footer>
    <div class="footer" id="footer">
        <div class="container">
            <div class="row">
                <div class="col-lg-3 col-md-3 col-sm-6 col-xs-12 f-col">
                    <h3><?= lang('about_us') ?></h3>
                    <p><?= $footerAboutUs ?></p>
                </div>
                <div class="col-lg-3 col-md-3 col-sm-6 hidden-xs f-col">
                    <h3><?= lang('pages') ?></h3>
                    <ul>
                        <li><a href="<?= base_url() ?>">» <?= lang('home') ?> </a></li>
                        <li><a href="<?= LANG_URL . '/checkout' ?>">» <?= lang('checkout') ?> </a></li>
                        <li><a href="<?= LANG_URL . '/contacts' ?>">» <?= lang('contacts') ?> </a></li>
                    </ul>
                </div>
                <div class="col-lg-3 col-md-3 col-sm-6 col-xs-12 f-col">
                    <h3><?= lang('contacts') ?></h3>
                    <ul class="footer-icon">
                        <?php if ($footerContactAddr != '') { ?>
                            <li>
                                <span class="pull-left"><i class="fa fa-map-marker"></i></span> 
                                <span class="pull-left f-cont-info"> <?= $footerContactAddr ?></span> 
                            </li>
                        <?php }if ($footerContactPhone != '') { ?>
                            <li>
                                <span class="pull-left"><i class="fa fa-phone"></i></span> 
                                <span class="pull-left f-cont-info"> <?= $footerContactPhone ?></span> 
                            </li>
                        <?php } if ($footerContactEmail != '') { ?>
                            <li>
                                <span class="pull-left"><i class="fa fa-envelope"></i></span> 
                                <span class="pull-left f-cont-info"><a href="mailto:<?= $footerContactEmail ?>"><?= $footerContactEmail ?></a></span>
                            </li>
                        <?php } ?>
                    </ul>
                </div>
                <div class="col-lg-3 col-md-3 col-sm-6 hidden-xs f-col">
                    <h3><?= lang('newsletter') ?></h3>
                    <ul>
                        <li>
                            <div class="input-append newsletter-box text-center">
                                <form method="POST" id="subscribeForm">
                                    <input type="text" class="full text-center" name="subscribeEmail" placeholder="<?= lang('email_address') ?>"><br><br>
                                    <button class="btn bg-info" onclick="checkEmailField()" type="button"> <?= lang('subscribe') ?> <i class="fa fa-long-arrow-right"></i></button>
                                </form>
                            </div>
                        </li>
                    </ul>
                    <ul class="social">
                        <?php if ($footerSocialFacebook != '') { ?>
                            <li> <a href="<?= $footerSocialFacebook ?>"><i class=" fa fa-facebook"></i></a></li>
                        <?php } if ($footerSocialTwitter != '') { ?>
                            <li> <a href="<?= $footerSocialTwitter ?>"><i class="fa fa-twitter"></i></a></li>
                        <?php } if ($footerSocialGooglePlus != '') { ?>
                            <li> <a href="<?= $footerSocialGooglePlus ?>"><i class="fa fa-google-plus"></i></a></li>
                        <?php } if ($footerSocialPinterest != '') { ?>
                            <li> <a href="<?= $footerSocialPinterest ?>"><i class="fa fa-pinterest"></i></a></li>
                        <?php } if ($footerSocialYoutube != '') { ?>
                            <li> <a href="<?= $footerSocialYoutube ?>"><i class="fa fa-youtube"></i></a></li>
                        <?php } ?>
                    </ul>
                </div>
            </div> 
        </div>
    </div>
    <div class="footer-bottom">
        <div class="container">
            <p class="pull-left"><?= $footerCopyright ?></p>
            <div class="pull-right hidden-xs">
                <!--<ul class="nav nav-pills payments">-->
                <!--    <li><i class="fa fa-cc-visa"></i></li>-->
                <!--    <li><i class="fa fa-cc-mastercard"></i></li>-->
                <!--    <li><i class="fa fa-cc-amex"></i></li>-->
                <!--    <li><i class="fa fa-cc-paypal"></i></li>-->
                <!--</ul> -->
                <p>Developed By <a href="http://absoft-bd.com" target="_blank">ABSoftBD</a></p>
            </div>
        </div>
    </div>
</footer>
<?php if ($this->session->flashdata('emailAdded')) { ?>
    <script>
        $(document).ready(function () {
            ShowNotificator('alert-info', '<?= lang('email_added') ?>');
        });
    </script>
    <?php
}
echo $addedJs;
?>
</div>
</div>
<div id="notificator" class="alert"></div>
<div class="dropdown-wish" id="popupWishCart">
    <ul></ul>
</div>
<div class="hidden-lg hidden-md hidden-sm nav-bottom">
    <div class="col"><a href="/"><img src="<?= base_url("assets/imgs/admin-home.png"); ?>"><br>Home</a></div>
    <div class="col">
        <a href="<?= LANG_URL . '/shopping-cart' ?>">
            <div class="bscart"><span class="sumOfItems"><?= $cartItems['array'] == 0 ? 0 : $sumOfItems ?></span></div>
            <img src="<?= base_url("assets/imgs/shop-cart-add-icon.png"); ?>">
            <br>Cart
        </a>
    </div>
    <?php if (isset($_SESSION['logged_user'])) { ?>
    <div class="col"><a href="<?= LANG_URL . '/myaccount' ?>"><img src="<?= base_url("assets/imgs/admin-user.png"); ?>"><br><?= lang('my_acc') ?></a></div>
    <?php } else { ?>
    <div class="col"><a href="<?= LANG_URL . '/login' ?>"><img src="<?= base_url("assets/imgs/admin-user.png"); ?>"><br>Login</a></div>
    <?php } ?>
</div>
<?php if(isset($_GET['search_in_title'])) { ?>
    <script>
        console.log("96");
        $('html, body').animate({
            scrollTop: $("#products-side").offset().top
        }, 1000);
    </script>
<?php } ?>
<script src="<?= base_url('assets/js/bootstrap.min.js') ?>"></script>
<script src="<?= base_url('assets/js/bootstrap-confirmation.min.js') ?>"></script>
<script src="<?= base_url('assets/bootstrap-select-1.12.1/js/bootstrap-select.min.js') ?>"></script>
<script src="<?= base_url('assets/js/placeholders.min.js') ?>"></script>
<script src="<?= base_url('assets/js/bootstrap-datepicker.min.js') ?>"></script>
<script>
var variable = {
    clearShoppingCartUrl: "<?= base_url('clearShoppingCart') ?>",
    manageShoppingCartUrl: "<?= base_url('manageShoppingCart') ?>",
    send_email: "<?= base_url('send_email') ?>",
    send_sms: "<?= base_url('send_sms') ?>",
    discountCodeChecker: "<?= base_url('discountCodeChecker') ?>"
};
var langabbr = "<?= MY_LANGUAGE_ABBR ?>";
var SHOP_DIR = "<?= SHOP_DIR ?>";
</script>
<script src="<?= base_url('assets/js/system.js') ?>"></script>
<script src="<?= base_url('templatejs/jquery.zoom.min.js') ?>"></script>
<script src="<?= base_url('templatejs/mine.js') ?>"></script>
<?php if(isset($temp_noti)): ?>
<script>
send_sms("<?= $temp_noti['cus_mob']; ?>", "Your order #<?= $temp_noti['order_id']; ?> has been placed successfully.");
</script>
<?php endif; ?>
</body>
</html>
