<?php if ($this->session->flashdata('update_vend_err')) { ?>
    <div class="alert alert-danger"><?= implode('<br>', $this->session->flashdata('update_vend_err')) ?></div>
<?php } ?>
<?php if ($this->session->flashdata('update_vend_details')) { ?>
    <div class="alert alert-success"><?= $this->session->flashdata('update_vend_details') ?></div>
<?php } ?>
<div class="content col-sm-12 col-md-12">
    <form method="POST" class="col-sm-9 col-md-6" action="<?= LANG_URL . '/vendor/me' ?>">
        <input type="text" class="form-control" value="<?= $vendor_name ?>" name="vendor_name" placeholder="<?= lang('vendor_name') ?>">
        <br>
        <input type="text" class="form-control" value="<?= $vendor_url ?>" name="vendor_url" placeholder="<?= lang('vendor_url') ?>">
        <br>
        <input type="text" class="form-control" value="<?= $vendor_mobile ?>" name="vendor_mobile" placeholder="<?= lang('vendor_mobile') ?>">
        <br>
        <input type="text" class="form-control" disabled="disabled" value="<?= $vendor_email ?>" name="vendor_email" placeholder="<?= lang('vendor_email') ?>">
        <br>
        <button type="submit" name="saveVendorDetails" class="btn btn-default"><span class="glyphicon glyphicon-floppy-disk"></span> <?= lang('vendor_update') ?></button>
    </form>
</div>