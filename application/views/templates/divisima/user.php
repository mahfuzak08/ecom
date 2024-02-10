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
            <?php if($userInfo['phone_verify'] == 'Yes'){ ?>
                <img src="<?= base_url("assets/imgs/welcome.png"); ?>">
            <?php } else { ?>
                <div class="col-sm-6">
                    <div class="loginmodal-container">
                        <!--<input type="button" class="login loginmodal-submit" value="Resend Verification Code"><br>-->
                        <p>Please enter the verification code which already send in your mobile. And then active your account.</p>
                        <form method="POST" action="">
                            <input type="text" name="verify_phone" placeholder="Enter Verification Code">
                            <input type="submit" name="verify_phone_number" class="login loginmodal-submit" value="Verify Phone">
                        </form>
                        <div id="resend-div">
                            <div id="resendbar"></div>
                        </div>
                    </div>
                </div>
            <?php } ?>
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