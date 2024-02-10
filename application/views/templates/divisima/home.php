<?php
defined('BASEPATH') OR exit('No direct script access allowed');

$arrCategories = array();
foreach ($all_categories as $categorie) {
    if (isset($_GET['category']) && is_numeric($_GET['category']) && $_GET['category'] == $categorie['sub_for']) {
        $arrCategories[] = $categorie;
    }
    if (!isset($_GET['category']) || $_GET['category'] == '') {
        if ($categorie['sub_for'] == 0) {
            $arrCategories[] = $categorie;
        }
    }
}

if (count($sliderProducts) > 0) { ?>
	<!-- Hero section -->
	<section class="hero-section">
		<div class="hero-slider owl-carousel">
			<?php
				$i = 0;
				foreach ($sliderProducts as $article) { ?>
					<div class="hs-item set-bg" data-setbg="<?= base_url('attachments/'. SHOP_DIR .'/shop_images/' . $article['image']) ?>">
						<div class="container">
							<div class="row">
								<div class="col-xl-6 col-lg-7 text-white">
									<span>New Arrivals</span>
									<h2><?= character_limiter($article['title'], 100) ?></h2>
									<p>Lorem ipsum dolor sit amet, consectetur adipiscing elit, sed do eiusmod tempor incididunt ut labore et dolore magna aliqua. Quis ipsum sus-pendisse ultrices gravida. Risus commodo viverra maecenas accumsan lacus vel facilisis. </p>
									<a href="<?= LANG_URL . '/' . $article['url'] ?>" class="site-btn sb-line">DISCOVER</a>
									<a href="#" class="site-btn sb-white">ADD TO CART</a>
								</div>
							</div>
							<div class="offer-card text-white">
								<span>from</span>
								<h2><?= CURRENCY ?><?= $article['price'] != '' ? $article['price'] : 0 ?></h2>
								<p>SHOP NOW</p>
							</div>
						</div>
					</div><?php 
				}
			?>
		</div>
		<div class="container">
			<div class="slide-num-holder" id="snh-1"></div>
		</div>
	</section>
	<!-- Hero section end -->
<?php } ?>


	<!-- Features section -->
	<section class="features-section">
		<div class="container-fluid">
			<div class="row">
				<div class="col-md-4 p-0 feature">
					<div class="feature-inner">
						<div class="feature-icon">
							<img src="<?= site_url("template/imgs/1.png"); ?>" alt="#">
						</div>
						<h2>Fast Secure Payments</h2>
					</div>
				</div>
				<div class="col-md-4 p-0 feature">
					<div class="feature-inner">
						<div class="feature-icon">
							<img src="<?= site_url("template/imgs/2.png"); ?>" alt="#">
						</div>
						<h2>Premium Products</h2>
					</div>
				</div>
				<div class="col-md-4 p-0 feature">
					<div class="feature-inner">
						<div class="feature-icon">
							<img src="<?= site_url("template/imgs/3.png"); ?>" alt="#">
						</div>
						<h2>Free & fast Delivery</h2>
					</div>
				</div>
			</div>
		</div>
	</section>
	<!-- Features section end -->


	<!-- letest product section -->
	<section class="top-letest-product-section">
		<div class="container">
			<div class="section-title">
				<h2>LATEST PRODUCTS</h2>
			</div>
			<div class="product-slider owl-carousel">
				<?php
				if (!empty($products)) {
					$load::getLatestProducts($products, 'col-xs-6 col-sm-4 col-md-3', false);
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
	</section>
	<!-- letest product section end -->



	<!-- Product filter section -->
	<section class="product-filter-section">
		<div class="container">
			<div class="section-title">
				<h2>BROWSE TOP SELLING PRODUCTS</h2>
			</div>
			<ul class="product-filter-menu">
				<li><a href="#">TOPS</a></li>
				<li><a href="#">JUMPSUITS</a></li>
				<li><a href="#">LINGERIE</a></li>
				<li><a href="#">JEANS</a></li>
				<li><a href="#">DRESSES</a></li>
				<li><a href="#">COATS</a></li>
				<li><a href="#">JUMPERS</a></li>
				<li><a href="#">LEGGINGS</a></li>
			</ul>
			<div class="row">
				<?php
				if (!empty($products)) {
					$load::getLatestProducts($products, 'col-lg-3 col-6', true);
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
			<div class="text-center pt-5">
				<button class="site-btn sb-line sb-dark">LOAD MORE</button>
			</div>
		</div>
	</section>
	<!-- Product filter section end -->


	<!-- Banner section -->
	<section class="banner-section">
		<div class="container">
			<div class="banner set-bg" data-setbg="<?= site_url("template/imgs/banner-bg.jpg"); ?>">
				<div class="tag-new">NEW</div>
				<span>New Arrivals</span>
				<h2>STRIPED SHIRTS</h2>
				<a href="#" class="site-btn">SHOP NOW</a>
			</div>
		</div>
	</section>
	<!-- Banner section end  -->