<style>
    .verify{
        margin-left: 10px;
        font-size: 20px;
        color: green;
    }
    .fa-recycle.verify{
        color: red;
        cursor: pointer;
    }
</style>
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
    ?>
    <div class="row">
        <div class="col-xs-8">
        <h1><img src="<?= base_url('assets/imgs/products-img.png') ?>" class="header-img" style="margin-top:-2px;"> <?= $description; ?></h1>
        </div>
        <div class="col-xs-2 pull-right">
            <a href="javascript:void(0);" onclick="set_data(0,'',0)" data-toggle="modal" data-target="#addPage" class="btn btn-default" style="margin-bottom:10px;">Add Account</a>
        </div>
    </div>
    <hr>
    <div class="row">
        <div class="col-xs-12">
			<?php
            if ($accounts) {
                ?>
                <div class="table-responsive">
                    <table class="table table-bordered">
                        <thead>
                            <tr>
                                <th>SL</th>
                                <th>Title</th>
                                <th>Type</th>
                                <th>Currency</th>
                                <th>Bank Name</th>
                                <th>Bank Address</th>
                                <th>A/C Number</th>
                                <th class="text-right">Action</th>
                            </tr>
                        </thead>
                        <tbody>
                            <?php
                            $i = 1;
                            foreach ($accounts as $row) {?>
                                <tr>
                                    <td><?= $i++; ?></td>
                                    <td><?= $row->name; ?></td>
                                    <td><?= $row->type; ?></td>
                                    <td><?= $row->currency; ?></td>
                                    <td><?= $row->bank_name; ?></td>
                                    <td><?= $row->bank_address; ?></td>
                                    <td><?= $row->acc_no; ?></td>
                                    <td>
                                        <div class="pull-right">
                                            <?php if($row->type != 'Due' && $row->type != 'GiftCard') { ?>
                                                <a href="<?= base_url("admin/accounts/". $row->id); ?>" class="btn btn-info">Details</a>
                                            <?php } ?>
                                            <!-- <a href="<?= base_url("admin/accounts/edit/".$row->id); ?>" class="btn btn-warning">Edit</a> -->
                                            <?php if(strpos($access[0]['access'], ACCOUNTS_DELETE)>-1) { ?>
                                                <?php if($row->type != 'Due' && $row->type != 'GiftCard') { ?>
                                                    <a href="<?= base_url("admin/accounts/delete/".$row->id); ?>" class="btn btn-danger">Delete</a>
                                                <?php } ?>
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

    <div class="modal fade" id="addPage" tabindex="-1" role="dialog" aria-labelledby="myModalLabel">
        <div class="modal-dialog" role="document">
            <div class="modal-content">
                <form action="" method="POST">
                    <div class="modal-header">
                        <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
                        <h4 class="modal-title" id="myModalLabel">Add new account</h4>
                    </div>
                    <div class="modal-body">
                        <div class="form-group">
                            <label for="name">Title</label>
                            <input type="text" name="name" class="form-control" id="name">
                        </div>
                        <div class="form-group">
                            <label for="type">Type</label>
                            <select name="type" class="form-control" id="type">
                                <option value="Cash">Cash</option>
                                <option value="Due">Due</option>
                                <option value="GiftCard">Gift Card</option>
                                <option value="Others">Others</option>
                            </select>
                        </div>
                        <div class="form-group">
                            <label for="bank_name">Bank Name</label>
                            <input type="text" name="bank_name" class="form-control" id="bank_name">
                        </div>
                        <div class="form-group">
                            <label for="bank_address">Bank Address</label>
                            <input type="text" name="bank_address" class="form-control" id="bank_address">
                        </div>
                        <div class="form-group">
                            <label for="acc_no">Account Number</label>
                            <input type="text" name="acc_no" class="form-control" id="acc_no">
                        </div>
                        <div class="form-group">
                            <label for="currency">Currency</label>
                            <input type="text" name="currency" value="BDT" class="form-control" id="currency">
                        </div>
                        <div class="form-group">
                            <label for="date">Date</label>
                            <input type="text" name="date" value="<?= date("Y-m-d") ?>" class="form-control" id="date">
                        </div>
                        <div class="form-group">
                            <label for="opening_balance">Opening Balance</label>
                            <input type="text" name="opening_balance" value="0" class="form-control" id="opening_balance">
                        </div>
                    </div>
                    <div class="modal-footer">
                        <input type="hidden" name="id" id="id" value="0">
                        <button type="button" class="btn btn-default" data-dismiss="modal">Cancel</button>
                        <button type="submit" name="add_account" class="btn btn-primary">Add</button>
                    </div>
                </form>
            </div>
        </div>
    </div>
    <script>
        $(document).ready( function () {
            $('.table').DataTable();
        } );
        function set_data(id, name, type){
            $("#addPage #id").val(id);
            $("#addPage #name").val(name);
            $("#addPage #type").val(type);
        }
    </script>