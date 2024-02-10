<link href="<?= base_url('assets/css/bootstrap-datepicker.min.css') ?>" rel="stylesheet">
<div id="products">
    <?php
    if ($this->session->flashdata('error')) {
        ?>
        <hr>
        <div class="alert alert-danger"><?= $this->session->flashdata('error') ?></div>
        <hr>
        <?php
    }
    if ($this->session->flashdata('success')) {
        ?>
        <hr>
        <div class="alert alert-success"><?= $this->session->flashdata('success') ?></div>
        <hr>
        <?php
    } 
    ?>
    <div class="row">
        <div class="col-xs-8">
            <h1><button class="btn btn-default" onclick="javascript:location.href='<?= site_url("admin/expenses"); ?>'"><i class="fa fa-arrow-left"></i></button> <?= $description; ?></h1>
        </div>
        <div class="col-xs-2 pull-right">
        <?php if(strpos($access[0]['access'], EXPENSE_TRANS_ADD)>-1) { ?>
            <a href="javascript:void(0);" data-toggle="modal" data-target="#addPage" class="btn btn-default" style="margin-bottom:10px;">Add Expense Transection</a>
        <?php } ?>
        </div>
    </div>
    <hr>
    <div class="row">
        <?php if($expenses){ ?>
            <div class="col-xs-12 table-responsive">
                <h4><?= $expenses[0]->title; ?></h4>
            </div>
            <div class="col-xs-12" style="margin-top: 10px;">
                <div class="table-responsive">
                    <table class="table table-bordered">
                        <thead>
                            <tr>
                                <th>SL</th>
                                <!--<th>Entry Date</th>-->
                                <th>Transection Date</th>
                                <th>Title</th>
                                <th>Description</th>
                                <th>Account</th>
                                <th class="text-right">Amount</th>
                                <th>Action</th>
                            </tr>
                        </thead>
                        <tbody>
                            <?php
                            $i = 1; $running_balance=0;
                            foreach ($expense_trans as $row) {
                                
                                ?>
                                <tr>
                                    <td><?= $i++; ?></td>
                                    <!--<td><?= $row['created_at']; ?></td>-->
                                    <td><?= $row['date']; ?></td>
                                    <td><?= $row['title']; ?></td>
                                    <td><?= $row['details']; ?></td>
                                    <td><?= $row['name']; ?></td>
                                    <td><?= $row['amount']; ?></td>
                                    <td>
                                        <div class="pull-right">
                                            <a href="<?= base_url("admin/expenses/print_bill/".$row['id']); ?>" class="btn btn-info">Print</a>
                                            <?php if(strpos($access[0]['access'], EXPENSE_TRANS_EDIT)>-1) { ?>
                                                <!--<a href="<?= base_url("admin/expenses/edit/".$row['id']); ?>" class="btn btn-warning">Edit</a>-->
                                            <?php } ?>
                                            <?php if(strpos($access[0]['access'], EXPENSE_TRANS_DELETE)>-1) { ?>
                                                <a href="<?= base_url("admin/expenses/delete/".$row['id']."?type=t"); ?>" class="btn btn-danger">Delete</a>
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
    <?php if(strpos($access[0]['access'], EXPENSE_TRANS_ADD)>-1) { ?>
        <div class="modal fade" id="addPage" tabindex="-1" role="dialog" aria-labelledby="myModalLabel">
            <div class="modal-dialog" role="document">
                <div class="modal-content">
                    <form action="<?= site_url("admin/expenses/add_trans"); ?>" method="POST">
                        <div class="modal-header">
                            <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
                            <h4 class="modal-title" id="myModalLabel">Expense Transection</h4>
                        </div>
                        <div class="modal-body">
                        <div class="form-group">
                                <label for="date">Date</label>
                                <input type="text" name="date" value="<?= date("Y-m-d") ?>" class="form-control datepicker" id="date">
                            </div>
                            <div class="form-group">
                                <label for="title">Expense Title</label>
                                <input type="text" class="form-control" name="title" id="title">
                                <input type="hidden" value="<?= $expenses[0]->id; ?>" class="form-control" name="eid" id="eid">
                            </div>
                            <div class="form-group">
                                <label for="details">Description</label>
                                <input type="text" name="details" class="form-control" id="details">
                            </div>
                            <div class="form-group">
                                <label for="accno">Account</label>
                                <select name="accno" class="form-control" id="accno">
                                    <?php foreach($accounts as $v){ ?>
                                    <?php if($v->type == 'Due') continue; ?>
                                    <option value="<?= $v->id; ?>"><?= $v->name; ?></option>
                                    <?php } ?>
                                </select>
                            </div>
                            <div class="form-group">
                                <label for="amount">Amount</label>
                                <input type="text" name="amount" value="0" class="form-control" id="amount">
                            </div>
                        </div>
                        <div class="modal-footer">
                            <button type="button" class="btn btn-default" data-dismiss="modal">Cancel</button>
                            <button type="submit" name="add_trans" class="btn btn-primary">Add</button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    <?php } ?>
    <script src="<?= base_url('assets/js/bootstrap-datepicker.min.js') ?>"></script>
    <script>
        $(document).ready( function () {
            $('.table.table-bordered').DataTable();
            $('.datepicker').datepicker({ format: "yyyy-mm-dd" }).datepicker("setDate", new Date());
        } );
    </script>