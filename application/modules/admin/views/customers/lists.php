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
    <a href="javascript:void(0);" data-toggle="modal" data-target="#add_edit_articles" class="btn btn-primary btn-xs pull-right add_edit_customer" style="margin-bottom:10px;"><b>+</b> Add Customer</a>
    <h1><img src="<?= base_url('assets/imgs/products-img.png') ?>" class="header-img" style="margin-top:-2px;"><?= $description; ?></h1>
    <hr>
    <div class="row">
        <div class="col-xs-12">
			<?php
            if ($customers) {
                ?>
                <div class="table-responsive">
                    <table class="table table-bordered">
                        <thead>
                            <tr>
                                <th>SL</th>
                                <th>Name</th>
                                <th>Phone</th>
                                <th>Email</th>
                                <th>Order(s)</th>
                                <th>Order(s) Amt</th>
                                <th>Total Dues</th>
                                <!-- <th>User ID</th> -->
                                <th class="text-right">Action</th>
                            </tr>
                        </thead>
                        <tbody>
                            <?php
                            $i = 1;
                            foreach ($customers as $row) {
                                ?>

                                <tr>
                                    <td><?= $i++; ?></td>
                                    <td><?= $row->name; ?></td>
                                    <td><?= $row->phone; ?><?= $row->phone_verify == 'Yes' ? '<i class="fa fa-check verify" title="Verified"></i>' : '<i class="fa fa-recycle verify" title="Pending" onclick="verified(event, \''. $row->phone .'\')"></i>'; ?></td>
                                    <td><?= $row->email; ?></td>
                                    <td><?= $row->noorder; ?></td>
                                    <td><?= $row->toamt; ?></td>
                                    <td><?= $row->balance; ?></td>
                                    <td>
                                        <div class="pull-right">
                                            <a href="<?= base_url("admin/customer/".$row->id); ?>" class="btn btn-info">Details</a>
                                            <?php if(strpos($access[0]['access'], CUSTOMER_EDIT)>-1) { ?>
                                            <a href="<?= base_url("admin/customer?edit=".$row->id); ?>" class="btn btn-warning">Edit</a>
                                            <?php } ?>
                                            <?php if(strpos($access[0]['access'], CUSTOMER_DELETE)>-1) { ?>
                                            <a href="<?= base_url("admin/customer?delete=".$row->id); ?>" class="btn btn-danger confirm-delete">Delete</a>
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
            <div class ="alert alert-info">No customer found!</div>
        <?php } ?>
    </div>
    <div class="modal fade" id="add_edit_articles" tabindex="-1" role="dialog" aria-labelledby="myModalLabel">
        <div class="modal-dialog" role="document">
            <div class="modal-content">
                <form action="" method="POST">
                    <div class="modal-header">
                        <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
                        <h4 class="modal-title" id="myModalLabel">Add/ Update Customer</h4>
                    </div>
                    <div class="modal-body">
                        <div class="form-group">
                            <label>Name</label>
                            <input type="text" name="customer_name" value="<?= @$customer[0]->name; ?>" class="form-control">
                        </div>
                        <div class="form-group">
                            <label>Mobile</label>
                            <input type="text" name="customer_mobile" value="<?= @$customer[0]->phone; ?>" class="form-control">
                            <input type="hidden" name="old_customer_mobile" value="<?= @$customer[0]->phone; ?>" class="form-control">
                        </div>
                        <div class="form-group">
                            <label>Address</label>
                            <textarea name="customer_address" class="form-control"><?= @$customer[0]->address; ?></textarea>
                        </div>
                        <?php if(@$customer[0]->balance == 0){ ?>
                        <div class="form-group">
                            <label>Balance</label>
                            <input type="text" name="customer_balance" class="form-control" value="0">
                            <p class='text-red'> * বাকিতে পন্য বিক্রয় = শুধু টাকার অঙ্ক লিখুন </p>
                            <p class='text-red'> * অগ্রীম টাকা গ্রহন হলে = মাইনাস (-) দিয়ে টাকার অঙ্ক লিখুন </p>
                        </div>
                        <?php } ?>
                    </div>
                    <div class="modal-footer">
                        <input type="hidden" name="id" id="customer_id" value="0">
                        <button type="button" class="btn btn-default" data-dismiss="modal">Cancel</button>
                        <button type="submit" name="submit" class="btn btn-primary">Save</button>
                    </div>
                </form>
            </div>
        </div>
    </div>
    <script>
        $(document).ready( function () {
            $('.table').DataTable();
            <?php if(isset($_GET["edit"])) { ?>
                $("#customer_id").val("<?= $customer[0]->id; ?>");
                $(".add_edit_customer").trigger("click");
            <?php } ?>
        } );
    </script>