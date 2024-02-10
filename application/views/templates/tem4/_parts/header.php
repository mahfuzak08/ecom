<!DOCTYPE html>
<html lang="<?= MY_LANGUAGE_ABBR ?>">
    <head>
        <meta charset="utf-8" />
        <meta http-equiv="X-UA-Compatible" content="IE=edge" />
        <meta name='viewport' content='width=device-width, initial-scale=1.0, maximum-scale=1.0, user-scalable=0'/>
        <meta name="title" content="<?= $companyName; ?>" />
        <link href="<?= base_url("attachments/". SHOP_DIR ."/site_ico/". $siteico); ?>" rel="shortcut icon"/>
        <meta name="description" content="" />
        <meta name="keywords" content="" />
        <!-- Open Graph / Facebook -->
        <meta property="og:title" content="<?= $companyName; ?>" />
        <meta property="og:description" content="" />
        <meta property="og:url" content="<?= base_url(); ?>" />
        <meta property="og:type" content="website" />
        <meta property="og:site_name" content="<?= $companyName; ?>" />
        <meta property="og:image" content="<?= base_url("attachments/". SHOP_DIR ."/site_overview/" . $siteOverview); ?>" />
        <!-- Twitter -->
        <meta property="twitter:card" content="summary_large_image">
        <meta property="twitter:url" content="<?= base_url(); ?>">
        <meta property="twitter:title" content="<?= $companyName; ?>">
        <meta property="twitter:description" content="">
        <meta property="twitter:image" content="<?= base_url("attachments/". SHOP_DIR ."/site_overview/" . $siteOverview); ?>">

        <title><?= $companyName; ?></title>
        <link rel="stylesheet" href="<?= base_url('assets/css/font-awesome.min.css') ?>" />
        <link rel="stylesheet" href="<?= base_url('assets/css/bootstrap.min.css') ?>" />
        <link rel="stylesheet" href="<?= base_url('assets/bootstrap-select-1.12.1/bootstrap-select.min.css') ?>" />
        <link href="<?= base_url('assets/css/bootstrap-datepicker.min.css') ?>" rel="stylesheet" />
        <link href="<?= base_url('templatecss/custom.css') ?>" rel="stylesheet" />
        <link href="<?= base_url('cssloader/theme.css') ?>" rel="stylesheet" />
        <script src="<?= base_url('assets/js/jquery.min.js') ?>"></script>
        <script src="<?= base_url('loadlanguage/all.js') ?>"></script>
        <?php if ($cookieLaw != false) { ?>
            <script type="text/javascript">
                window.cookieconsent_options = {"message": "<?= $cookieLaw['message'] ?>", "dismiss": "<?= $cookieLaw['button_text'] ?>", "learnMore": "<?= $cookieLaw['learn_more'] ?>", "link": "<?= $cookieLaw['link'] ?>", "theme": "<?= $cookieLaw['theme'] ?>"};
            </script>
            <script type="text/javascript" src="//cdnjs.cloudflare.com/ajax/libs/cookieconsent2/1.0.10/cookieconsent.min.js"></script>
        <?php } ?>
        <!--[if lt IE 9]>
          <script src="https://oss.maxcdn.com/html5shiv/3.7.3/html5shiv.min.js"></script>
          <script src="https://oss.maxcdn.com/respond/1.4.2/respond.min.js"></script>
        <![endif]-->
    </head>
    <body>
        <div id="wrapper">
            <div id="content">
				<!--
                <?php if ($multiVendor == 1) { ?>
                    <div id="top-user-panel">
                        <div class="container">
                            <a href="<?= LANG_URL . '/vendor/register' ?>" class="btn btn-default"><?= lang('register_me') ?></a>
                            <form class="form-inline" method="POST" action="<?= LANG_URL . '/vendor/login' ?>">
                                <div class="form-group">
                                    <input type="email" name="u_email" class="form-control" placeholder="<?= lang('email') ?>">
                                </div>
                                <div class="form-group">
                                    <input type="password" name="u_password" class="form-control" placeholder="<?= lang('password') ?>">
                                </div>
                                <div class="checkbox">
                                    <label><input type="checkbox" name="remember_me"><?= lang('remember_me') ?></label>
                                </div>
                                <button type="submit" name="login" class="btn btn-default"><?= lang('u_login') ?></button>
                            </form> 
                        </div>
                    </div>
                <?php } ?>
				-->
                <div id="languages-bar">
                    <div class="container">
                        <?php
                        $num_langs = count($allLanguages);
                        if ($num_langs > 0) {
                            ?>
                            <ul class="pull-left">
                                <?php
                                $i = 1;
                                $lang_last = '';
                                foreach ($allLanguages as $key_lang => $lang) {
                                    ?>
                                    <li <?= $i == $num_langs ? 'class="last-item"' : '' ?>>
                                        <img src="<?= base_url('attachments/lang_flags/' . $lang['flag']) ?>" alt="Language-<?= MY_LANGUAGE_ABBR ?>"><a href="<?= base_url($key_lang) ?>"><?= $lang['name'] ?></a>
                                    </li>
                                    <?php
                                    $i++;
                                }
                                ?>
                            </ul>
                        <?php } ?>
                        <div class="phone pull-right">
                            <?php
                            if ($footerContactPhone != '') {
                                ?>
                                <img width="22px" src="<?= base_url('template/imgs/Phone-icon.png') ?>" alt="Call us">
                                <?php
                                echo $footerContactPhone;
                            }
                            ?>
                        </div>
                    </div>
                </div>
                <div id="top-part">
                    <div class="container">
                        <div class="row">
                            <div class="col-sm-12 col-md-3 col-lg-4 left">
                                <a href="<?= base_url() ?>">
                                    <img src="<?= base_url('attachments/'. SHOP_DIR .'/site_logo/' . $sitelogo) ?>" class="site-logo" alt="<?= $_SERVER['HTTP_HOST'] ?>">
                                </a>
                            </div>
                            <div class="col-sm-6 col-md-5 col-lg-5">
                                <div class="search">	  
                                    <input type="text" value="<?= isset($_GET['search_in_title']) ? $_GET['search_in_title'] : 'Search' ?>" id="search_in_title" class="textbox" onfocus="this.value = '';" onblur="if (this.value == '') {this.value = 'Search';}">
                                    <input type="submit" value="Search" id="submit" name="submit">
                                    <form class="form-horizontal" method="GET" action="<?= isset($vendor_url) ? $vendor_url : LANG_URL ?>" id="bigger-search">
                                        <input type="hidden" name="category" value="<?= isset($_GET['category']) ? $_GET['category'] : '' ?>">
                                        <input type="hidden" name="in_stock" value="<?= isset($_GET['in_stock']) ? $_GET['in_stock'] : '' ?>">
                                        <input type="hidden" name="search_in_title" value="<?= isset($_GET['search_in_title']) ? $_GET['search_in_title'] : '' ?>">
                                        <input type="hidden" name="order_new" value="<?= isset($_GET['order_new']) ? $_GET['order_new'] : '' ?>">
                                        <input type="hidden" name="order_price" value="<?= isset($_GET['order_price']) ? $_GET['order_price'] : '' ?>">
                                        <input type="hidden" name="order_procurement" value="<?= isset($_GET['order_procurement']) ? $_GET['order_procurement'] : '' ?>">
                                        <input type="hidden" name="brand_id" value="<?= isset($_GET['brand_id']) ? $_GET['brand_id'] : '' ?>">
                                        <input type="hidden" name="vendor_id" value="<?= isset($_GET['vendor_id']) ? $_GET['vendor_id'] : '' ?>">
                                        <ul></ul>
                                        <input type="hidden" value="<?= isset($_GET['quantity_more']) ? $_GET['quantity_more'] : '' ?>" name="quantity_more" id="quantity_more" placeholder="<?= lang('type_a_number') ?>" class="form-control">
                                        <input type="hidden" value="<?= isset($_GET['added_after']) ? $_GET['added_after'] : '' ?>" name="added_after" id="added_after">
                                        <input type="hidden" value="<?= isset($_GET['added_before']) ? $_GET['added_before'] : '' ?>" name="added_before">
                                        <input class="form-control" value="<?= isset($_GET['search_in_body']) ? $_GET['search_in_body'] : '' ?>" name="search_in_body" id="search_in_body"  type="hidden" />
                                        <input type="hidden" value="<?= isset($_GET['price_from']) ? $_GET['price_from'] : '' ?>" name="price_from">
                                        <input type="hidden" name="price_to" value="<?= isset($_GET['price_to']) ? $_GET['price_to'] : '' ?>" >
                                    </form>
                                    <div class="dropdown-menu dropdown-menu-right" id="suggestions" role="menu"><ul></ul></div>
                                </div>
                            </div>
                            <div class="col-sm-6 col-md-4 col-lg-3 hidden-xs">
                                <div class="basket-box">
                                    <table>
                                        <tr>
                                            <td>&nbsp;</td>
                                            <td>
                                                <div class="center">
                                                    <h4><?= lang('your_basket') ?></h4>
                                                    <a href="<?= LANG_URL . '/checkout' ?>"><?= lang('checkout_top_header') ?></a> |
                                                    <a href="<?= LANG_URL . '/shopping-cart' ?>"><?= lang('shopping_cart_only') ?></a>
                                                </div>
                                            </td>
                                            <td>
                                                <ul class="shop-dropdown">
                                                    <li class="dropdown text-center">
                                                        <a href="#" class="dropdown-toggle" id="openpopupcart" data-toggle="dropdown" role="button" aria-expanded="false"> 
                                                            <div><span class="sumOfItems"><?= is_array($cartItems)? $sumOfItems : 0; ?></span> <?= lang('items') ?></div>
                                                            <img src="<?= base_url('template/imgs/shopping-cart-icon-515.png') ?>" alt="">
                                                            <span class="caret"></span>
                                                        </a>
                                                        <ul class="dropdown-menu dropdown-menu-right dropdown-cart" id="popupcart" role="menu">
                                                            
                                                        </ul>
                                                    </li>
                                                </ul>
                                            </td>
                                        </tr>
                                    </table>
                                </div>
                            </div>        
                        </div>
                    </div>
                </div>
                <nav class="navbar gradient-color hidden-xs">
                    <div class="container">
                        <div class="navbar-header">
                            <button type="button" class="navbar-toggle collapsed" data-toggle="collapse" data-target="#navbar" aria-expanded="false" aria-controls="navbar">
                                <span class="sr-only">Toggle navigation</span>
                                <span class="icon-bar"></span>
                                <span class="icon-bar"></span>
                                <span class="icon-bar"></span>
                            </button>
                            <?php if ($naviText != null) { ?>
                                <a class="navbar-brand" href="<?= base_url() ?>"><?= $naviText ?></a>
                            <?php } ?>
                        </div>
                        <div id="navbar" class="collapse navbar-collapse">
                            <ul class="nav navbar-nav" style="<?= $naviText == null ? 'margin-left:-15px;' : '' ?>">
                                <li<?= uri_string() == '' || uri_string() == MY_LANGUAGE_ABBR ? ' class="active"' : '' ?>><a href="<?= LANG_URL ?>"><?= lang('home') ?></a></li>
                                <?php
                                if (!empty($nonDynPages)) {
                                    foreach ($nonDynPages as $addonPage) {
                                        ?>
                                        <li<?= uri_string() == $addonPage || uri_string() == MY_LANGUAGE_ABBR . '/' . $addonPage ? ' class="active"' : '' ?>><a href="<?= LANG_URL . '/' . $addonPage ?>"><?= mb_ucfirst(lang($addonPage)) ?></a></li>
                                        <?php
                                    }
                                }
                                if (!empty($dynPages)) {
                                    foreach ($dynPages as $addonPage) {
                                        ?>
                                        <li<?= urldecode(uri_string()) == 'page/' . $addonPage['pname'] || uri_string() == MY_LANGUAGE_ABBR . '/' . 'page/' . $addonPage['pname'] ? ' class="active"' : ''
                                        ?>><a href="<?= LANG_URL . '/page/' . $addonPage['pname'] ?>"><?= mb_ucfirst($addonPage['lname']) ?></a></li>
                                            <?php
                                        }
                                    }
                                    ?>
                                <li<?= uri_string() == 'checkout' || uri_string() == MY_LANGUAGE_ABBR . '/checkout' ? ' class="active"' : '' ?>><a href="<?= LANG_URL . '/checkout' ?>"><?= lang('checkout') ?></a></li>
                                <li<?= uri_string() == 'shopping-cart' || uri_string() == MY_LANGUAGE_ABBR . '/shopping-cart' ? ' class="active"' : '' ?>><a href="<?= LANG_URL . '/shopping-cart' ?>"><?= lang('shopping_cart') ?></a></li>
                                <li<?= uri_string() == 'contacts' || uri_string() == MY_LANGUAGE_ABBR . '/contacts' ? ' class="active"' : '' ?>><a href="<?= LANG_URL . '/contacts' ?>"><?= lang('contacts') ?></a></li>
                                <?php if (isset($_SESSION['logged_user'])) { ?>
                                    <li<?= uri_string() == 'myaccount' || uri_string() == MY_LANGUAGE_ABBR . '/myaccount' ? ' class="active"' : '' ?>><a href="<?= LANG_URL . '/myaccount' ?>"><?= lang('my_acc') ?></a></li>
                                    <li<?= uri_string() == 'logout' || uri_string() == MY_LANGUAGE_ABBR . '/logout' ? ' class="active"' : '' ?>><a href="<?= LANG_URL . '/logout' ?>"><?= lang('logout') ?></a></li>
                                <?php } else { ?>
                                    <li<?= uri_string() == 'login' || uri_string() == MY_LANGUAGE_ABBR . '/login' ? ' class="active"' : '' ?>><a href="<?= LANG_URL . '/login' ?>"><?= lang('login') ?></a></li>
                                <?php } ?>
                            </ul>
                        </div>
                    </div>
                </nav>