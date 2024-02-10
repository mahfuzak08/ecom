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
    <a href="javascript:void(0);" data-toggle="modal" data-target="#add_edit_articles" class="btn btn-primary btn-xs pull-right add_edit_customer" style="margin-bottom:10px;"><b>+</b> Add Vendor</a>
    <h1><img src="<?= base_url('assets/imgs/products-img.png') ?>" class="header-img" style="margin-top:-2px;"><?= $description; ?></h1>
    <hr>
    <div class="row">
        <div class="col-xs-12">
			<?php
            if ($vendors) {
                ?>
                <div class="table-responsive">
                    <table class="table table-bordered">
                        <thead>
                            <tr>
                                <th>SL</th>
                                <th>Name</th>
                                <th>Address</th>
                                <th>Email</th>
                                <th>Mobile</th>
                                <th>Balance</th>
                                <th class="text-right">Action</th>
                            </tr>
                        </thead>
                        <tbody>
                            <?php
                            $i = 1;
                            foreach ($vendors as $row) {
                                ?>

                                <tr>
                                    <td><?= $i++; ?></td>
                                    <td><?= $row->vendor_name; ?></td>
                                    <td><?= $row->vendor_address; ?></td>
                                    <td><?= $row->vendor_email; ?></td>
                                    <td><?= $row->vendor_mobile; ?></td>
                                    <td><?= ($row->balance * -1); ?></td>
                                    <td>
                                        <div class="pull-right">
                                            <a href="<?= base_url('admin/vendormanage?edit=' . $row->vendor_id) ?>" class="btn btn-info">Edit</a> 
                                            <?php if($row->active == 0) { ?>
                                                <a href="<?= base_url('admin/vendormanage?deactive=' . $row->vendor_id) ?>"  class="btn btn-warning confirm-delete">Deactive</a>
                                            <?php } elseif($row->active == 1) { ?>
                                                <a href="<?= base_url('admin/vendormanage?active=' . $row->vendor_id) ?>"  class="btn btn-warning confirm-delete">Active</a>
                                            <?php } ?>
                                            <a href="<?= base_url('admin/vendormanage?delete=' . $row->vendor_id) ?>"  class="btn btn-danger confirm-delete">Delete</a>
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
            <div class ="alert alert-info">No supplier found!</div>
        <?php } ?>
    </div>
    <div class="modal fade" id="add_edit_articles" tabindex="-1" role="dialog" aria-labelledby="myModalLabel">
        <div class="modal-dialog" role="document">
            <div class="modal-content">
                <form action="" method="POST">
                    <div class="modal-header">
                        <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
                        <h4 class="modal-title" id="myModalLabel">Add/ Update Supplier</h4>
                    </div>
                    <div class="modal-body">
                        <div class="form-group">
                            <label>Name</label>
                            <input type="text" name="vendor_name" value="<?= @$vendor['name']; ?>" class="form-control">
                        </div>
                        <div class="form-group">
                            <label>Mobile</label>
                            <input type="text" name="vendor_mobile" value="<?= @$vendor['mobile']; ?>" class="form-control">
                            <input type="hidden" name="old_vendor_mobile" value="<?= @$vendor['mobile']; ?>" class="form-control">
                        </div>
                        <div class="form-group">
                            <label>Address</label>
                            <textarea name="vendor_url" class="form-control"><?= @$vendor['url']; ?></textarea>
                        </div>
                        <?php if(@$vendor['balance'] == 0){ ?>
                        <div class="form-group">
                            <label>Balance</label>
                            <input type="text" name="balance" class="form-control" value="0">
                            <p class='text-red'> * ধারে পন্য ক্রয় হলে = শুধু টাকার অঙ্ক লিখুন </p>
                            <p class='text-red'> * অগ্রীম টাকা দেওয়া হলে = মাইনাস (-) দিয়ে টাকার অঙ্ক লিখুন </p>
                        </div>
                        <?php } ?>
                    </div>
                    <div class="modal-footer">
                        <input type="hidden" name="id" id="vendor_id" value="0">
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
                $("#vendor_id").val("<?= $vendor['id']; ?>");
                $(".add_edit_customer").trigger("click");
            <?php } ?>
        } );
    </script>