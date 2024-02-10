<style>
    body {
        background-image:url('/assets/imgs/login-bg.png');
        background-position: bottom  right;
        background-repeat: no-repeat;
        background-color:#548fd0;
    }
    .avatar {background-image:url('<?= base_url("/attachments/". SHOP_DIR ."/site_logo/". $sitelogo); ?>')}
    .g-recaptcha{
        margin-top: 15px;
    }
</style>
<div class="container">
    <div class="login-container">
        <div id="output">       
            <?php
            if ($this->session->flashdata('err_login')) {
                ?>
                <div class="alert alert-danger"><?= $this->session->flashdata('err_login') ?></div>
                <?php
            }
            ?></div>
        <div class="avatar"></div>
        <div class="form-box">
            <form action="" method="POST">
                <input type="text" name="username" placeholder="username">
                <input type="password" name="password" placeholder="password">
                <?php if($g_recaptcha_site_key != "") { ?>
                <div class="g-recaptcha" data-sitekey="<?= $g_recaptcha_site_key; ?>"></div>
                <br/>
                <?php } ?>
                <button class="btn btn-info btn-block login" type="submit" name="LoginPost">Login</button>
            </form>
        </div>
    </div>
</div>
<?= $addedJs; ?>