<?php
defined('BASEPATH') OR exit('No direct script access allowed');
?>
<!-- <div class="inner-nav">-->
<!--    <div class="container">-->
<!--        <a href="<?= LANG_URL ?>"><?= lang('home') ?></a> <span class="active"> > <?= lang('user_login') ?></span>-->
<!--    </div>-->
<!--</div>-->
<div class="container user-page">
<div class="row">
<div class="col-sm-6 col-sm-offset-3 col-md-4 col-md-offset-4">
<div class="loginmodal-container">
<h1><?= lang('user_register') ?></h1><br>
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
        <span class="fa fa-eye form-control-feedback" style="cursor: pointer;pointer-events:auto;" onclick="toggle_type()"></span>
    </div>
    <div class="form-group has-feedback">
        <input type="password" name="pass_repeat" class="form-control logpass" placeholder="Password repeat">
        <span class="fa fa-eye form-control-feedback" style="cursor: pointer;pointer-events:auto;" onclick="toggle_type()"></span>
    </div>
    <input type="checkbox" name="privacy" id="privacy">&nbsp;&nbsp;I have read and agree to the <a href="<?= LANG_URL . '/privacy' ?>">Privacy Policy</a><br>
    <input type="button" name="signup" id="signup" class="login loginmodal-submit disable" value="<?= lang('register_me') ?>">
</form>
<div class="login-help">
    <a href="<?= LANG_URL . '/login' ?>"><?= lang('login') ?></a>
</div>
</div>
</div>
</div>
</div>