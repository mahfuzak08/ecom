<?php
defined('BASEPATH') OR exit('No direct script access allowed');
?>
<div class="container user-page">
    <div class="row">
        <div class="col-sm-6">
            <img src="<?= base_url('assets/imgs/reg.jpg') ?>" style="width: 100%;">
        </div>
        <div class="col-sm-6">
            <div class="loginmodal-container">
                <h2 style="margin: 20px 0px;"><?= lang('user_register') ?></h2>
                <?php 
                if($this->session->flashdata('userError')){
                    $err = $this->session->flashdata('userError');
                    // foreach($err as $row){
                        echo "<div style='color: #F00'>".$err."</div>";
                    // }
                }
                ?>
                <form method="POST" action="">
                    <div class="form-group has-feedback">
                        <input type="text" name="name" class="form-control" placeholder="Name">
                    </div>
                    <div class="form-group has-feedback">
                        <input type="text" name="phone" class="form-control" placeholder="Phone">
                    </div>
                    <div class="form-group has-feedback">
                        <input type="text" name="email" class="form-control" placeholder="Email (Optional)">
                    </div>
                    <div class="form-group has-feedback">
                        <input type="password" name="pass" class="form-control logpass" placeholder="Password">
                        <span class="fa fa-eye form-control-feedback" style="cursor: pointer;pointer-events:auto;position: absolute;right: 25px;margin-top: -25px;" onclick="toggle_type()"></span>
                    </div>
                    <div class="form-group has-feedback">
                        <input type="password" name="pass_repeat" class="form-control logpass" placeholder="Password repeat">
                        <span class="fa fa-eye form-control-feedback" style="cursor: pointer;pointer-events:auto;position: absolute;right: 25px;margin-top: -25px;" onclick="toggle_type()"></span>
                    </div>
                    <input type="checkbox" name="privacy" id="privacy">&nbsp;&nbsp;I have read and agree to the <a href="<?= LANG_URL . '/privacy' ?>">Privacy Policy</a><br>
                    <input type="button" name="signup" style="margin:10px 0px;" id="signup" class="btn btn-success btn-sm btn-flat site-btn login loginmodal-submit disable" value="<?= lang('register_me') ?>">
                </form>
                <div class="login-help" style="margin:10px 0px;">
                    Already have an account? <a href="<?= LANG_URL . '/login' ?>"><?= lang('login') ?></a>
                </div>
            </div>
        </div>
    </div>
</div>