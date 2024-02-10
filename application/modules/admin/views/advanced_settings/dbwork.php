<?php if(strpos($access[0]['access'], DBA)>-1) { ?>
<div id="dba">
    <h1><img src="<?= base_url('assets/imgs/admin-user.png') ?>" class="header-img" style="margin-top:-3px;"> <?= $description; ?></h1> 
    <hr>
    <?php if (validation_errors()) { ?>
        <hr>
        <div class="alert alert-danger"><?= validation_errors() ?></div>
        <hr>
        <?php
    }
    if ($this->session->flashdata('result_add')) {
        ?>
        <hr>
        <div class="alert alert-success"><?= $this->session->flashdata('result_add') ?></div>
        <hr>
        <?php
    }
    if ($this->session->flashdata('result_delete')) {
        ?>
        <hr>
        <div class="alert alert-success"><?= $this->session->flashdata('result_delete') ?></div>
        <hr>
        <?php
    }
    ?>
    <div class="clearfix"></div>
    <div class="row">
        <div class="col-md-4 col-xs-12">
            <div class="box">
                <div class="box-header">
                    <h3 class="box-title">Database related work</h3>
                </div>
                <!-- /.box-header -->
                <div class="box-body table-responsive no-padding">
                    <table class="table">
                        <tbody>
                            <tr>
                                <th><a href="?backup=all" class="btn btn-info btn-flat btn-block">Backup Full Database</a></th>
							<tr>
                                <!-- <th><a href="?backup=today" class="btn btn-primary btn-flat btn-block">Backup Today's Transections</a></th> -->
							</tr>
                                <!--<th><a href="#" class="btn btn-danger btn-flat btn-block">Truncate All Database</a></th>-->
                            </tr>
                            <tr>
                                <!-- <th><a href="#" class="btn btn-info btn-flat btn-block">Full Site Backup</a></th> -->
                                <!-- <th><a href="#" class="btn btn-primary btn-flat btn-block">Backup Last Month Transections</a></th> -->
                                <th><a href="?deleteTrans=all" class="btn btn-danger btn-flat btn-block confirm-delete">Delete All Transections</a></th>
                            </tr>
                            <tr>
                                <th><a href="?deleteTrans=all&deleteProductsAlso=product_delete" class="btn btn-danger btn-flat btn-block confirm-delete">Delete All Transections & Products</a></th>
                            </tr>
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>
</div>
<?php } else { echo "<h1>404</h1><h3>Page not  found</h3>"; } ?>