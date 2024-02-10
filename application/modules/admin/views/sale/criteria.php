<?php $tabindex= 1; ?>
<h1><img src="<?= base_url('assets/imgs/brands.jpg') ?>" class="header-img" style="margin-top:-3px;"> <?= $description; ?> </h1>
<hr>
    <div class="row">
        <div class="col-md-8 col-sm-8 col-xs-12">
            <form action="<?= base_url("admin/sale/report/search"); ?>" method="POST">
                <div class="box box-success table-responsive">
                    <hr style="margin: 5px;">
                    <div class="form-group col-md-6 col-xs-12">
                        <div class="input-group">
                            <span class="input-group-addon"><i class="fa fa-calendar"></i></span>
                            <input type="text" value="<?= date("01.m.Y"); ?>" class="form-control datepicker" placeholder="Start Date" readonly>
                        </div>
                    </div>
                    <div class="form-group col-md-6 col-xs-12">
                        <div class="input-group">
                            <span class="input-group-addon"><i class="fa fa-calendar"></i></span>
                            <input type="text" value="<?= date("d.m.Y"); ?>" class="form-control datepicker" placeholder="End Date" readonly>
                        </div>
                    </div>
                    <hr style="margin: 5px;">
                    <div class="form-group col-md-6 col-xs-12">
                        <select name="customer_id" class="form-control">
                            <option selected="selected" disabled>Select Customer</option>
                            <option value="0">All</option>
                            <?php foreach($customers as $row){ ?>
                            <option value="<?= $row->id; ?>"><?= $row->name; ?></option>
                            <?php } ?>
                        </select>
                    </div>
                    <div class="form-group col-md-6 col-xs-12">
                        <select class="form-control">
                            <option selected="selected" disabled>Select Payment Type</option>
                            <option value="0">All</option>
                            <?php foreach($payment_type as $row){ ?>
                            <option value="<?= $row->name; ?>"><?= $row->name; ?></option>
                            <?php } ?>
                        </select>
                    </div>
                    <div class="form-group col-md-6 col-xs-12">
                        <button type="reset" class="btn btn-default btn-block btn-flat">Reset</button>
                    </div>
                    <div class="form-group col-md-6 col-xs-12">
                        <button type="submit" name="report_search" class="btn btn-success btn-block btn-flat">Search</button>
                    </div>
                </div>
            </form>
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
<link rel="stylesheet" href="//code.jquery.com/ui/1.12.1/themes/base/jquery-ui.css">
<link rel="stylesheet" href="<?= base_url('assets/css/bootstrap-datepicker.min.css') ?>">
<script src="https://code.jquery.com/ui/1.12.1/jquery-ui.js"></script>
<script src="<?= base_url('assets/js/bootstrap-datepicker.min.js') ?>"></script>
<script>
    $('.datepicker').datepicker({
        format: "dd.mm.yyyy",
        todayHighlight: true
    });
</script>