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
    <h1><img src="<?= base_url('assets/imgs/products-img.png') ?>" class="header-img" style="margin-top:-2px;"> Vendor Manage</h1>
    <hr>
    <div class="row">
        <div class="col-xs-12">
			<div class="well hidden-xs"> 
                <div class="row">
                    <form method="GET" id="searchProductsForm" action="">
                        <div class="col-sm-12">
                            <label>Search:</label>
                            <div class="input-group">
                                <input class="form-control" placeholder="Search Vendor" type="text" value="<?= isset($_GET['search_title']) ? $_GET['search_title'] : '' ?>" name="search_title">
                                <span class="input-group-btn">
                                    <button class="btn btn-default" type="submit" value="">
                                        <i class="fa fa-search"></i>
                                    </button>
                                </span>
                            </div>
                        </div>
                    </form>
                </div>
            </div>
            <?php
            if ($vendors) {
                ?>
                <div class="table-responsive">
                    <table class="table table-bordered">
                        <thead>
                            <tr>
                                <th>SL</th>
                                <th>Vendor Name</th>
                                <th>Address</th>
                                <th>Email</th>
                                <th>Mobile</th>
                                <th>Balance</th>
                                <th class="text-right">Action</th>
                            </tr>
                        </thead>
                        <tbody>
                            <?php
                            foreach ($vendors as $row) {
                                ?>
                                <tr>
                                    <td><?= $row->vendor_id; ?></td>
                                    <td><?= $row->vendor_name; ?></td>
                                    <td><?= $row->vendor_address; ?></td>
                                    <td><?= $row->vendor_email; ?></td>
                                    <td><?= $row->vendor_mobile; ?></td>
                                    <td><?= ($row->balance * -1); ?></td>
                                    <td>
                                        <div class="pull-right">
                                            <a href="<?= base_url('admin/addvendor/' . $row->vendor_id) ?>" class="btn btn-info">Edit</a> 
                                            <?php if($row->active == 0) { ?>
                                                <a href="<?= base_url('admin/vendormanage?deactive=' . $row->vendor_id) ?>"  class="btn btn-danger confirm-delete">Deactive</a>
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
                <?= $links_pagination ?>
            </div>
            <?php
        } else {
            ?>
            <div class ="alert alert-info">No products found!</div>
        <?php } ?>
    </div>