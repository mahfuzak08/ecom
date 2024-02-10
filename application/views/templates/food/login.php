<?php
defined('BASEPATH') OR exit('No direct script access allowed');
?>
<!--<div class="inner-nav">-->
<!--    <div class="container">-->
<!--        <?= lang('home') ?> <span class="active"> > <?= lang('user_login') ?></span>-->
<!--    </div>-->
<!--</div>-->
<div class="container user-page">
    <div class="row">
        <div class="col-sm-6 col-sm-offset-3 col-md-4 col-md-offset-4">
            <div class="loginmodal-container">
                <h1><?= lang('login_to_acc') ?></h1><br>
                <form method="POST" action="">
                    <div class="form-group has-feedback">
                        <input type="text" name="email" class="form-control" placeholder="Email/ Mobile Number">
                    </div>
                    <div class="form-group has-feedback">
                        <input type="password" name="pass" class="form-control logpass" placeholder="Password">
                        <span class="fa fa-eye form-control-feedback" style="cursor: pointer;pointer-events:auto;" onclick="toggle_type()"></span>
                    </div>
                    <input type="submit" name="login" class="login loginmodal-submit" value="<?= lang('login') ?>">
                </form> 
                <?php if($multiVendor == 1) { ?>
                <div class="col-sm-12 login-help">
                    <a href="<?= LANG_URL . '/forgotten-password' ?>"><?= lang('user_forgotten_page') ?></a>
                </div>
                <br><br>
                <div class="col-sm-6 login-help">
                    <a href="<?= LANG_URL . '/register' ?>"><?= lang('user_register') ?></a>
                </div>
                <div class="col-sm-6" style="text-align: right">
                    <a href="<?= LANG_URL . '/vendor/login' ?>"><?= lang('vendor_login') ?></a>
                </div>
				<?php } else { ?>
				<div class="col-sm-6 login-help">
                    <a href="<?= LANG_URL . '/register' ?>"><?= lang('user_register') ?></a>
                </div>
                <div class="col-sm-6" style="text-align: right">
                    <a href="<?= LANG_URL . '/forgotten-password' ?>"><?= lang('user_forgotten_page') ?></a>
                </div>
				<?php } ?>
            </div>
        </div>
    </div>
</div>