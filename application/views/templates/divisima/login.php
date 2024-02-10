<?php
defined('BASEPATH') OR exit('No direct script access allowed');
?>
<div class="container user-page">
    <div class="row">
        <div class="col-sm-6">
            <img src="<?= base_url('assets/imgs/login.jpg') ?>" style="width: 100%;">
        </div>
        <div class="col-sm-6">
            <div class="loginmodal-container">
                <h2 style="margin: 20px 0px;"><?= lang('login_to_acc') ?></h2>
                <form method="POST" action="">
                    <div class="form-group has-feedback">
                        <input type="text" name="email" class="form-control" placeholder="Email/ Mobile Number">
                    </div>
                    <div class="form-group has-feedback">
                        <input type="password" name="pass" class="form-control logpass" placeholder="Password">
                        <span class="fa fa-eye form-control-feedback" style="cursor: pointer;pointer-events:auto;position: absolute;right: 25px;margin-top: -25px;" onclick="toggle_type()"></span>
                    </div>
                    <div class="row">
                        <div class="col-sm-6">
                            <input type="submit" name="login" class="btn btn-success btn-sm btn-flat site-btn login loginmodal-submit" value="<?= lang('login') ?>">
                        </div>
                        <div class="col-sm-6" style="text-align: right; margin:10px 0px;">
                            <a href="<?= LANG_URL . '/forgotten-password' ?>"><?= lang('user_forgotten_page') ?></a>
                        </div>
                    </div>
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
                <div class="row">
                    <div class="col-sm-12" style="margin:10px 0px;">
                        Not registered? Please click on <a href="<?= LANG_URL . '/register' ?>"><?= lang('user_register') ?></a>
                    </div>
                </div>
				<?php } ?>
            </div>
        </div>
    </div>
</div>