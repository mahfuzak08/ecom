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
            if ($expenses) {
                ?>
                <div class="table-responsive">
                    <table class="table table-bordered">
                        <thead>
                            <tr>
                                <th>SL</th>
                                <th>Created Date</th>
                                <th>Title</th>
                                <th>Created By</th>
                                <th class="text-right">Action</th>
                            </tr>
                        </thead>
                        <tbody>
                            <?php
                            $i = 1;
                            foreach ($expenses as $row) {?>
                                <tr>
                                    <td><?= $i++; ?></td>
                                    <td><?= $row->created_at; ?></td>
                                    <td><?= $row->title; ?></td>
                                    <td><?= $row->username; ?></td>
                                    <td>
                                        <div class="pull-right">
                                            <a href="<?= base_url("admin/expenses/". $row->id); ?>" class="btn btn-info">Details</a>
                                            <?php if(strpos($access[0]['access'], EXPENSE_EDIT)>-1) { ?>
                                                <!--<a href="<?= base_url("admin/expenses/edit/".$row->id); ?>" class="btn btn-warning">Edit</a>-->
                                            <?php } ?>
                                            <?php if(strpos($access[0]['access'], EXPENSE_DELETE)>-1) { ?>
                                                <a href="<?= base_url("admin/expenses/delete/".$row->id."?type=g"); ?>" class="btn btn-danger">Delete</a>
                                            <?php } ?>
                                        </div>
                                    </td>
                                </tr>
                                <?php
                            }
                            ?>
                        </tbody>
                    </table>
                </div>
            </div>
            <?php
        } else {
            ?>
            <div class ="alert alert-info">No <?= $description; ?> found!</div>
        <?php } ?>
    </div>
    <?php if(strpos($access[0]['access'], EXPENSE_ADD)>-1) { ?>
        <div class="modal fade" id="addPage" tabindex="-1" role="dialog" aria-labelledby="myModalLabel">
            <div class="modal-dialog" role="document">
                <div class="modal-content">
                    <form action="" method="POST">
                        <div class="modal-header">
                            <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
                            <h4 class="modal-title" id="myModalLabel">Add new expense</h4>
                        </div>
                        <div class="modal-body">
                            <div class="form-group">
                                <label for="name">Title</label>
                                <input type="text" name="name" class="form-control" id="name">
                            </div>
                        </div>
                        <div class="modal-footer">
                            <input type="hidden" name="id" id="id" value="0">
                            <button type="button" class="btn btn-default" data-dismiss="modal">Cancel</button>
                            <button type="submit" name="add_expense" class="btn btn-primary">Add</button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    <?php } ?>
    <script>
        $(document).ready( function () {
            $('.table').DataTable();
        } );
        function set_data(id, name, type){
            $("#addPage #id").val(id);
            $("#addPage #name").val(name);
        }
    </script>