    <!-- Footer section -->
    <section class="footer-section">
		<div class="container">
			<div class="footer-logo text-center">
				<a href="<?= base_url() ?>" class="site-logo">
					<img src="<?= base_url('attachments/'. SHOP_DIR .'/site_logo/white-' . $sitelogo) ?>" style="max-width: 162px;" alt="<?= $_SERVER['HTTP_HOST'] ?>">
				</a>
			</div>
			<div class="row">
				<div class="col-sm-4">
					<div class="footer-widget about-widget">
						<h2>About</h2>
						<p><?= $footerAboutUs ?></p>
						<img src="<?= site_url("template/imgs/cards.png"); ?>" alt="">
					</div>
				</div>
				<div class="col-sm-4">
					<div class="footer-widget about-widget">
						<h2>Quick Link</h2>
						<ul>
							<li><a href="#">About Us</a></li>
							<li><a href="<?= LANG_URL . "/shopping-cart"; ?>">Shopping Cart</a></li>
							<li><a href="<?= LANG_URL . "/checkout"; ?>">Checkout</a></li>
							<li><a href="<?= LANG_URL . "/login"; ?>">Login</a></li>
							<li><a href="<?= LANG_URL . "/register"; ?>">Registration</a></li>
							<li><a href="<?= LANG_URL . "/privacy"; ?>">Privacy Policy</a></li>
						</ul>
					</div>
				</div>
				<div class="col-sm-4">
					<div class="footer-widget contact-widget">
						<h2>Address</h2>
						<?php if ($companyName != '') { ?>
						<div class="con-info">
							<span>C.</span>
							<p><?= $companyName ?></p>
						</div>
						<?php } ?>
						<?php if ($footerContactAddr != '') { ?>
						<div class="con-info">
							<span>B.</span>
							<p><?= $footerContactAddr ?></p>
						</div>
						<?php } ?>
						<?php if ($footerContactPhone != '') { ?>
						<div class="con-info">
							<span>T.</span>
							<p><?= $footerContactPhone ?></p>
						</div>
						<?php } ?>
						<?php if ($footerContactEmail != '') { ?>
						<div class="con-info">
							<span>T.</span>
							<p><a href="mailto:<?= $footerContactEmail ?>"><?= $footerContactEmail ?></a></p>
						</div>
						<?php } ?>
					</div>
				</div>
			</div>
		</div>
		<div class="social-links-warp">
			<div class="container">
				<div class="social-links">
					<?php if ($footerSocialFacebook != '') { ?>
						<a href="" class="facebook"><i class="fa fa-facebook"></i><span>facebook</span></a>
					<?php } ?>
					<?php if ($footerSocialTwitter != '') { ?>
						<a href="" class="twitter"><i class="fa fa-twitter"></i><span>twitter</span></a>
					<?php } ?>
					<?php if ($footerSocialGooglePlus != '') { ?>
						<a href="" class="google-plus"><i class="fa fa-google-plus"></i><span>g+plus</span></a>
					<?php } ?>
					<?php if ($footerSocialPinterest != '') { ?>
						<a href="" class="pinterest"><i class="fa fa-pinterest"></i><span>pinterest</span></a>
					<?php } ?>
					<?php if ($footerSocialYoutube != '') { ?>
						<a href="" class="youtube"><i class="fa fa-youtube"></i><span>youtube</span></a>
					<?php } ?>
                </div>
                <p class="text-white text-center mt-5">All rights reserved by <?= $companyName ?></a></p>
			</div>
		</div>
	</section>
	<!-- Footer section end -->



	<!--====== Javascripts & Jquery ======-->
	<!-- <script src="js/jquery-3.2.1.min.js"></script> -->
	<script src="<?= base_url('templatejs/bootstrap.min.js'); ?>"></script>
	<script src="<?= base_url('templatejs/jquery.slicknav.min.js'); ?>"></script>
	<script src="<?= base_url('templatejs/owl.carousel.min.js'); ?>"></script>
	<script src="<?= base_url('templatejs/jquery.nicescroll.min.js'); ?>"></script>
	<script src="<?= base_url('templatejs/jquery.zoom.min.js'); ?>"></script>
	<script src="<?= base_url('templatejs/jquery-ui.min.js'); ?>"></script>
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
	<script src="<?= base_url('templatejs/main.js'); ?>"></script>
	<script src="<?= base_url('templatejs/mine.js'); ?>"></script>
	<?php if(isset($temp_noti)): ?>
	<script>
	send_sms("<?= $temp_noti['cus_mob']; ?>", "Your order #<?= $temp_noti['order_id']; ?> has been placed successfully.");
	</script>
	<?php endif; ?>
	</body>
</html>
