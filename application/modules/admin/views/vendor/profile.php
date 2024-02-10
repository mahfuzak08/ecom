<div id="products">
    <?php
	$timeNow = time();
    if ($this->session->flashdata('result_delete')) {
        ?>
        <hr>
        <div class="alert alert-success"><?= $this->session->flashdata('result_delete') ?></div>
        <hr>
        <?php
    }
    if ($this->session->flashdata('result_publish')) {
        ?>
        <hr>
        <div class="alert alert-success"><?= $this->session->flashdata('result_publish') ?></div>
        <hr>
        <?php
    } 
    ?>
    <h1><img src="<?= base_url('assets/imgs/products-img.png') ?>" class="header-img" style="margin-top:-2px;"> Vendor Profile Add/Update</h1>
    <hr>
    <div class="row">
        <div class="col-xs-12">
			<form method="POST" action="" class="col-sm-9 col-md-6">
				<input type="hidden" value="<?= isset($_POST['folder']) ? $_POST['folder'] : $timeNow ?>" name="folder">
				<input type="text" class="form-control" value="<?= @$_POST['name'] ?>" name="vendor_name" placeholder="<?= lang('vendor_name') ?>">
				<br>
				<input type="text" class="form-control" value="<?= @$_POST['url'] ?>" name="vendor_url" placeholder="<?= lang('vendor_url') ?>">
				<br>
				<input type="text" class="form-control" value="<?= @$_POST['mobile'] ?>" name="vendor_mobile" placeholder="<?= lang('vendor_mobile') ?>">
				<br>
				<input type="text" class="form-control" value="<?= @$_POST['email'] ?>" name="vendor_email" placeholder="<?= lang('vendor_email') ?>">
				<br>
				<input type="password" class="form-control" value="" name="vendor_pass" placeholder="<?= lang('please_enter_password') ?>">
				<br>
				<input type="password" class="form-control" value="" name="vendor_conpass" placeholder="<?= lang('please_repeat_password') ?>">
				<input type="hidden" value="<?= @$_POST['password'] ?>" name="oldpass">
				<input type="hidden" value="<?= @$_POST['id'] ?>" name="id">
				
				<button type="submit" name="saveVendorDetails" class="btn btn-default"><span class="glyphicon glyphicon-floppy-disk"></span> <?= lang('vendor_update') ?></button>
			</form>
		</div>
    </div>