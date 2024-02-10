<?php
defined('BASEPATH') OR exit('No direct script access allowed');
?>
	<!-- product section -->
	<section class="product-section">
		<div class="container">
			<div class="back-link">
				<a href="<?= site_url(); ?>"> &lt;&lt; Back to Category</a>
			</div>
			<?php 
            $u_path = 'attachments/'. SHOP_DIR .'/shop_images/';
            if ($product['image'] != null && file_exists($u_path . $product['image'])) {
                $image = base_url($u_path . $product['image']);
            } else {
                $image = base_url('attachments/no-image.png');
            }
            ?>
			<div class="row">
				<div class="col-lg-4">
					<div class="product-pic-zoom">
						<img class="product-big-img" src="<?= $image; ?>" alt="<?= str_replace('"', "'", $product['title']) ?>">
					</div>
					<div class="product-thumbs" tabindex="1" style="overflow: hidden; outline: none;">
						<div class="product-thumbs-track">
							<?php 
							if ($product['folder'] != null) { 
								$dir = "attachments/". SHOP_DIR ."/shop_images/" . $product['folder'] . '/';
									if (is_dir($dir)) {
										if ($dh = opendir($dir)) {
											$i = 1;
											while (($file = readdir($dh)) !== false) {
												if (is_file($dir . $file)) { ?>
													<div class="pt" data-imgbigurl="<?= base_url($dir . $file) ?>"><img src="<?= base_url($dir . $file) ?>" alt="<?= str_replace('"', "'", $product['title']) ?>"></div>
													<?php
													$i++;
												}
											}
											closedir($dh);
										}
									}
									
							} ?>
						</div>
					</div>
				</div>
				<div class="col-lg-8 product-details">
					<h2 class="p-title"><?= $product['title'] ?></h2>
					<h3 class="p-price"><?= CURRENCY. price_format($product['price']) ?></h3>
					<h4 class="p-stock">Available: <span><?= ($publicQuantity == 1) ? "In Stock" : "Out of Stock"; ?></span></h4>
					<!--
					<div class="p-rating">
						<i class="fa fa-star-o"></i>
						<i class="fa fa-star-o"></i>
						<i class="fa fa-star-o"></i>
						<i class="fa fa-star-o"></i>
						<i class="fa fa-star-o fa-fade"></i>
					</div>
					<div class="p-review">
						<a href="">3 reviews</a>|<a href="">Add your review</a>
					</div>
					<div class="fw-size-choose">
						<p>Size</p>
						<div class="sc-item">
							<input type="radio" name="sc" id="xs-size">
							<label for="xs-size">32</label>
						</div>
						<div class="sc-item">
							<input type="radio" name="sc" id="s-size">
							<label for="s-size">34</label>
						</div>
						<div class="sc-item">
							<input type="radio" name="sc" id="m-size" checked="">
							<label for="m-size">36</label>
						</div>
						<div class="sc-item">
							<input type="radio" name="sc" id="l-size">
							<label for="l-size">38</label>
						</div>
						<div class="sc-item disable">
							<input type="radio" name="sc" id="xl-size" disabled>
							<label for="xl-size">40</label>
						</div>
						<div class="sc-item">
							<input type="radio" name="sc" id="xxl-size">
							<label for="xxl-size">42</label>
						</div>
					</div>
					-->
					<div class="quantity">
						<p>Quantity</p>
                        <div class="pro-qty"><input type="text" value="1"></div>
                    </div>
					<?php if ($product['quantity'] > 0) { ?>
					<a href="javascript:void(0);" class="add2cart btn btn-success btn-sm btn-flat site-btn" data-id="<?= base64_encode($product['id']); ?>" data-title="<?= $product['title']; ?>" data-url="<?= $product['url']; ?>" data-size="<?= $product['size']; ?>" data-img="<?= $product['image']; ?>" data-qty=1 data-price="<?= $product['price']; ?>"><?= lang('add_to_cart') ?></a>
					<?php } ?>
					<div id="accordion" class="accordion-area">
						<div class="panel">
							<div class="panel-header" id="headingOne">
								<button class="panel-link active" data-toggle="collapse" data-target="#collapse1" aria-expanded="true" aria-controls="collapse1">information</button>
							</div>
							<div id="collapse1" class="collapse show" aria-labelledby="headingOne" data-parent="#accordion">
								<div class="panel-body">
									<?= $product['description'] ?>
								</div>
							</div>
						</div>
					</div>
					<!--
					<div class="social-sharing">
						<a href=""><i class="fa fa-google-plus"></i></a>
						<a href=""><i class="fa fa-pinterest"></i></a>
						<a href=""><i class="fa fa-facebook"></i></a>
						<a href=""><i class="fa fa-twitter"></i></a>
						<a href=""><i class="fa fa-youtube"></i></a>
					</div>
					-->
				</div>
			</div>
		</div>
	</section>
	<!-- product section end -->


	<!-- RELATED PRODUCTS section -->
	<section class="related-product-section">
		<div class="container">
			<div class="section-title">
				<h2>RELATED PRODUCTS</h2>
			</div>
			<div class="product-slider owl-carousel">
			<?php
			if (!empty($sameCagegoryProducts)) {
				// print_r($sameCagegoryProducts);
				$load::getLatestProducts($sameCagegoryProducts, 'col-xs-6 col-sm-4 col-md-3', false);
			} else {
				?>
				<div class="alert alert-info"><?= lang('no_same_category_products') ?></div>
				<?php
			}
			?>
			</div>
			<!--
			<div class="product-slider owl-carousel">
				<div class="product-item">
					<div class="pi-pic">
						<img src="./img/product/1.jpg" alt="">
						<div class="pi-links">
							<a href="#" class="add-card"><i class="flaticon-bag"></i><span>ADD TO CART</span></a>
							<a href="#" class="wishlist-btn"><i class="flaticon-heart"></i></a>
						</div>
					</div>
					<div class="pi-text">
						<h6>$35,00</h6>
						<p>Flamboyant Pink Top </p>
					</div>
				</div>
				<div class="product-item">
					<div class="pi-pic">
						<div class="tag-new">New</div>
						<img src="./img/product/2.jpg" alt="">
						<div class="pi-links">
							<a href="#" class="add-card"><i class="flaticon-bag"></i><span>ADD TO CART</span></a>
							<a href="#" class="wishlist-btn"><i class="flaticon-heart"></i></a>
						</div>
					</div>
					<div class="pi-text">
						<h6>$35,00</h6>
						<p>Black and White Stripes Dress</p>
					</div>
				</div>
				<div class="product-item">
					<div class="pi-pic">
						<img src="./img/product/3.jpg" alt="">
						<div class="pi-links">
							<a href="#" class="add-card"><i class="flaticon-bag"></i><span>ADD TO CART</span></a>
							<a href="#" class="wishlist-btn"><i class="flaticon-heart"></i></a>
						</div>
					</div>
					<div class="pi-text">
						<h6>$35,00</h6>
						<p>Flamboyant Pink Top </p>
					</div>
				</div>
				<div class="product-item">
						<div class="pi-pic">
							<img src="./img/product/4.jpg" alt="">
							<div class="pi-links">
								<a href="#" class="add-card"><i class="flaticon-bag"></i><span>ADD TO CART</span></a>
								<a href="#" class="wishlist-btn"><i class="flaticon-heart"></i></a>
							</div>
						</div>
						<div class="pi-text">
							<h6>$35,00</h6>
							<p>Flamboyant Pink Top </p>
						</div>
					</div>
				<div class="product-item">
					<div class="pi-pic">
						<img src="./img/product/6.jpg" alt="">
						<div class="pi-links">
							<a href="#" class="add-card"><i class="flaticon-bag"></i><span>ADD TO CART</span></a>
							<a href="#" class="wishlist-btn"><i class="flaticon-heart"></i></a>
						</div>
					</div>
					<div class="pi-text">
						<h6>$35,00</h6>
						<p>Flamboyant Pink Top </p>
					</div>
				</div>
			</div>
			-->
		</div>
	</section>
	<!-- RELATED PRODUCTS section end -->