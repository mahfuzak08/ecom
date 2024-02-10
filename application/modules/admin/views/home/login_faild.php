<style>
    body {
        background-image:url('/assets/imgs/login-bg.png');
        background-position: bottom  right;
        background-repeat: no-repeat;
        background-color:#548fd0;
    }
    .avatar {background-image:url('<?= base_url("/attachments/". SHOP_DIR ."/site_logo/". $sitelogo); ?>')}
</style>
<div class="container">
    <div class="login-container">
        <div id="output">       
            <div class="alert alert-danger"><?= $this->session->flashdata('err_login') ?></div>
		</div>
        <div class="avatar"></div>
        <div class="form-box">
			<input type="text" placeholder="username">
			<input type="password" placeholder="password">
			<button class="btn btn-info btn-block login" type="submit">Login</button>
        </div>
    </div>
</div>