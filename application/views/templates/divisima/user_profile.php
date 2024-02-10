<?php
defined('BASEPATH') OR exit('No direct script access allowed');
?>
<style>
    ul.myacc-nav{
        list-style: none;
        margin-top: 10px;
    }
    .myacc-nav li{
        background: #ccd;
        padding: 5px 20px;
        cursor: pointer;
        margin-bottom: 5px;
    }
    .myacc-nav li:hover{
        background:#000;
        color:#fff;
    }
</style> 
<div class="container user-page">
    <div class="row">
        <div class="col-sm-4">
            <?php require_once("_parts/usernav.php"); ?>
        </div>
        <div class="col-sm-8" style="min-height: 400px">
            <div class="loginmodal-container">
                <h3><?= $title; ?></h3>
                <?php $readonly = ""; 
                if($userInfo['phone_verify'] != "Yes") $readonly = "disabled";
                ?>
                <form method="POST" action="">
                <div class="form-group has-feedback">
                    <input type="text" name="name" class="form-control" value="<?= $userInfo['name'] ?>" placeholder="Name" <?= $readonly ?>>
                </div>
                <div class="form-group has-feedback">
                    <input type="text" name="phone" class="form-control" value="<?= $userInfo['phone'] ?>" placeholder="Phone" <?= $readonly ?>>
                </div>
                <div class="form-group has-feedback">
                    <input type="text" name="email" class="form-control" value="<?= $userInfo['email'] ?>" placeholder="Email" <?= $readonly ?>>
                </div>
                <div class="form-group has-feedback">
                    <input type="text" name="address" class="form-control" value="<?= $userInfo['address'] ?>" placeholder="Address" <?= $readonly ?>>
                </div>
                <div class="form-group has-feedback">
                    <input type="submit" name="update" class="btn btn-success btn-sm btn-flat site-btn  login loginmodal-submit" value="<?= lang('update') ?>" <?= $readonly ?>>
                </div>
                </form>
            </div>
        </div>
    </div>
</div>
<script src="<?= base_url("assets/js/radial-progress-bar.js"); ?>"></script>
<script>
jQuery("#resendbar").radialProgress("init", {
  'size': 140,
  'fill': 70,
  'font-size': 36,
  'color': '#a0c03c',
  'text-color': '#FFF'
}).radialProgress("to", {'perc': 100, 'time': 100000, 'after100': '<a href="<?= site_url("myaccount"); ?>" >Resend</a>'});
</script>