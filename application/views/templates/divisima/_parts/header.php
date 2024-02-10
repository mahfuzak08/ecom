<?php 
defined('BASEPATH') OR exit('No direct script access allowed');
?>
<!DOCTYPE html>
<html lang="<?= MY_LANGUAGE_ABBR ?>">
    <head>
        <meta charset="utf-8" />
        <meta http-equiv="X-UA-Compatible" content="IE=edge" />
        <meta name='viewport' content='width=device-width, initial-scale=1.0, maximum-scale=1.0, user-scalable=0'/>
        <meta name="title" content="<?= $companyName; ?>" />
        <meta name="description" content="" />
        <meta name="keywords" content="" />
        <meta property="og:title" content="<?= $companyName; ?>" />
        <meta property="og:description" content="" />
        <meta property="og:url" content="<?= base_url(); ?>" />
        <meta property="og:type" content="website" />
        <meta property="og:site_name" content="<?= $companyName; ?>" />
        <meta property="og:image" content="<?= base_url('attachments/'. SHOP_DIR .'/site_overview/' . $siteOverview) ?>" />
        <title><?= $companyName; ?></title>
        <!-- Google Font -->
	    <link href="https://fonts.googleapis.com/css?family=Josefin+Sans:300,300i,400,400i,700,700i" rel="stylesheet">
        <link rel="stylesheet" href="<?= base_url('templatecss/bootstrap.min.css'); ?>"/>
        <link rel="stylesheet" href="<?= base_url('templatecss/font-awesome.min.css'); ?>"/>
        <link rel="stylesheet" href="<?= base_url('templatecss/flaticon.css'); ?>"/>
        <link rel="stylesheet" href="<?= base_url('templatecss/slicknav.min.css'); ?>"/>
        <link rel="stylesheet" href="<?= base_url('templatecss/jquery-ui.min.css'); ?>"/>
        <link rel="stylesheet" href="<?= base_url('templatecss/owl.carousel.min.css'); ?>"/>
        <link rel="stylesheet" href="<?= base_url('templatecss/animate.css'); ?>"/>
        <link rel="stylesheet" href="<?= base_url('templatecss/style.css'); ?>"/>


        <script src="<?= base_url('templatejs/jquery-3.2.1.min.js') ?>"></script>
        <script src="<?= base_url('loadlanguage/all.js') ?>"></script>
        <?php if ($cookieLaw != false) { ?>
            <script type="text/javascript">
                window.cookieconsent_options = {"message": "<?= $cookieLaw['message'] ?>", "dismiss": "<?= $cookieLaw['button_text'] ?>", "learnMore": "<?= $cookieLaw['learn_more'] ?>", "link": "<?= $cookieLaw['link'] ?>", "theme": "<?= $cookieLaw['theme'] ?>"};
            </script>
            <script type="text/javascript" src="//cdnjs.cloudflare.com/ajax/libs/cookieconsent2/1.0.10/cookieconsent.min.js"></script>
        <?php } ?>
        <!--[if lt IE 9]>
		    <script src="https://oss.maxcdn.com/html5shiv/3.7.2/html5shiv.min.js"></script>
	        <script src="https://oss.maxcdn.com/respond/1.4.2/respond.min.js"></script>
	    <![endif]-->
    </head>
    <body>
        <!-- Page Preloder -->
        <div id="preloder">
            <div class="loader"></div>
        </div>
        	<!-- Header section -->
        <header class="header-section">
            <div class="header-top">
                <div class="container">
                    <div class="row">
                        <div class="col-lg-2 text-center text-lg-left">
                            <!-- logo -->
                            <a href="<?= base_url() ?>" class="site-logo">
                                <img src="<?= base_url('attachments/'. SHOP_DIR .'/site_logo/' . $sitelogo) ?>" alt="<?= $_SERVER['HTTP_HOST'] ?>">
                            </a>
                        </div>
                        <div class="col-xl-6 col-lg-5">
                            <form class="header-search-form form-horizontal" method="GET" action="<?= isset($vendor_url) ? $vendor_url : LANG_URL ?>" id="bigger-search">
                                <input type="text" name="search_in_title" value="<?= isset($_GET['search_in_title']) ? $_GET['search_in_title'] : '' ?>" placeholder="Search on divisima ....">
                                <button><i class="flaticon-search"></i></button>
                                <input type="hidden" name="category" value="<?= isset($_GET['category']) ? $_GET['category'] : '' ?>">
                                <input type="hidden" name="in_stock" value="<?= isset($_GET['in_stock']) ? $_GET['in_stock'] : '' ?>">
                                <input type="hidden" name="order_new" value="<?= isset($_GET['order_new']) ? $_GET['order_new'] : '' ?>">
                                <input type="hidden" name="order_price" value="<?= isset($_GET['order_price']) ? $_GET['order_price'] : '' ?>">
                                <input type="hidden" name="order_procurement" value="<?= isset($_GET['order_procurement']) ? $_GET['order_procurement'] : '' ?>">
                                <input type="hidden" name="brand_id" value="<?= isset($_GET['brand_id']) ? $_GET['brand_id'] : '' ?>">
                                <input type="hidden" name="vendor_id" value="<?= isset($_GET['vendor_id']) ? $_GET['vendor_id'] : '' ?>">
                                <ul></ul>
                                <input type="hidden" value="<?= isset($_GET['quantity_more']) ? $_GET['quantity_more'] : '' ?>" name="quantity_more" id="quantity_more" placeholder="<?= lang('type_a_number') ?>" class="form-control">
                                <input type="hidden" value="<?= isset($_GET['added_after']) ? $_GET['added_after'] : '' ?>" name="added_after" id="added_after">
                                <input type="hidden" value="<?= isset($_GET['added_before']) ? $_GET['added_before'] : '' ?>" name="added_before">
                                <input type="hidden" class="form-control" value="<?= isset($_GET['search_in_body']) ? $_GET['search_in_body'] : '' ?>" name="search_in_body" id="search_in_body" />
                                <input type="hidden" value="<?= isset($_GET['price_from']) ? $_GET['price_from'] : '' ?>" name="price_from">
                                <input type="hidden" name="price_to" value="<?= isset($_GET['price_to']) ? $_GET['price_to'] : '' ?>" >
                            </form>
                        </div>
                        <div class="col-xl-4 col-lg-5">
                            <div class="user-panel">
                                <div class="up-item">
                                    <i class="flaticon-profile"></i>
                                    <?php if (isset($_SESSION['logged_user'])) { ?>
                                        <a href="<?= site_url("myaccount"); ?>">Welcome <?= $_SESSION['logged_user_name']; ?></a>
                                    <?php } else { ?>
                                        <a href="<?= site_url("login"); ?>">Sign In</a> or 
                                        <a href="<?= site_url("register"); ?>">Create Account</a>
                                    <?php } ?>
                                </div>
                                <div class="up-item dropdown">
                                    <div class="shopping-card">
                                        <i class="flaticon-bag" id="openpopupcart"></i>
                                        <span class="sumOfItems"><?= gettype($cartItems) === 'integer' ? 0 : $sumOfItems ?></span>
                                    </div>
									<a href="<?= LANG_URL . '/shopping-cart' ?>" class="shopping-card-btn"><?= lang('shopping_cart_only') ?></a>
									<ul class="shopping-card-body" style="display:none;" id="popupcart"></ul>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <nav class="main-navbar">
                <div class="container">
                    <!-- menu -->
                    <?php
                    function loop_tree($pages, $is_recursion = false)
                    {
                        ?>
                        <ul class="<?= $is_recursion === true ? 'sub-menu' : 'main-menu' ?>">
                            <?php
                            foreach ($pages as $page) {
                                $children = false;
                                if (isset($page['children']) && !empty($page['children'])) {
                                    $children = true;
                                }
                                ?>
                                <li>
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

                    loop_tree($all_categories);
                    ?>
                </div>
            </nav>
        </header>
        <!-- Header section end -->