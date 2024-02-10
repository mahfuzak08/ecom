<?php $tabindex= 1; ?>
<h1><img src="<?= base_url('assets/imgs/brands.jpg') ?>" class="header-img" style="margin-top:-3px;"> <?= $description; ?> </h1>
<hr>
    <div class="row">
        <div class="col-md-8 col-sm-8 col-xs-12">
            <?php print_r($result); ?>
        </div>
        <div class="col-md-4 col-sm-4 col-xs-12">
            <button class="btn btn-success btn-block btn-flat" onclick="printDiv('invoice')">Print Invoice</button><br>
            <a class="btn btn-primary btn-block btn-flat" href="<?= site_url("admin/sale"); ?>">Go to Sale Register</a><br>
            <button class="btn btn-info btn-block btn-flat">SMS Invoice</button><br>
            <button class="btn btn-primary btn-block btn-flat">Email Invoice</button><br>
            <button class="btn btn-default btn-block btn-flat" onclick="toggle_div('#inv_settings')">Invoice Settings</button><br>
            <div class="row" style="display:none;" id="inv_settings">
                <div class="col-md-12 col-xs-12">
                    Settings Start
                </div>
            </div>
        </div>
    </div>
<link rel="stylesheet" href="<?= base_url('assets/js/jquery-ui.min.css') ?>">
<script src="<?= base_url('assets/js/jquery-ui.min.js') ?>"></script>
<link rel="stylesheet" href="<?= base_url('assets/css/bootstrap-datepicker.min.css') ?>">
<script src="<?= base_url('assets/js/bootstrap-datepicker.min.js') ?>"></script>
<script>
    $('.datepicker').datepicker({
        format: "dd.mm.yyyy",
        todayHighlight: true
    });
</script>