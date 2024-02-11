<div id="products">
    <?php
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
    if ($this->session->flashdata('error')) {
        ?>
        <hr>
        <div class="alert alert-warning"><?= $this->session->flashdata('error') ?></div>
        <hr>
        <?php
    } 
    ?>
    <div class="row">
        <div class="col-xs-8">
        <h1><img src="<?= base_url('assets/imgs/products-img.png') ?>" class="header-img" style="margin-top:-2px;"> <?= $description; ?></h1>
        </div>
        <div class="col-xs-2 pull-right">
            <?php if(strpos($access[0]['access'], EXPENSE_ADD)>-1) { ?>
                <a href="javascript:void(0);" onclick="set_data(0,'',0)" data-toggle="modal" data-target="#addPage" class="btn btn-default" style="margin-bottom:10px;">Add Expense</a>
            <?php } ?>
        </div>
    </div>
    <hr>
    <div class="row">
        <div class="col-xs-12">
            <?php
                print_r($bal);
            ?>
        </div>
    </div>