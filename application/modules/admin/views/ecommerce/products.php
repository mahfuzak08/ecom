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
    <h1><img src="<?= base_url('assets/imgs/products-img.png') ?>" class="header-img" style="margin-top:-2px;"> Products</h1>
    <hr>
    <div class="row">
        <div class="col-xs-12">
            <div class="well hidden-xs"> 
                <div class="row">
                    <form method="GET" id="searchProductsForm" action="">
                        <div class="col-sm-4">
                            <label>Order:</label>
                            <select name="order_by" class="form-control selectpicker change-products-form">
                                <option <?= isset($_GET['order_by']) && $_GET['order_by'] == 'id=desc' ? 'selected=""' : '' ?> value="id=desc">Newest</option>
                                <option <?= isset($_GET['order_by']) && $_GET['order_by'] == 'id=asc' ? 'selected=""' : '' ?> value="id=asc">Latest</option>
                                <option <?= isset($_GET['order_by']) && $_GET['order_by'] == 'quantity=asc' ? 'selected=""' : '' ?> value="quantity=asc">Low Quantity</option>
                                <option <?= isset($_GET['order_by']) && $_GET['order_by'] == 'quantity=desc' ? 'selected=""' : '' ?> value="quantity=desc">High Quantity</option>
                            </select>
                        </div>
                        <div class="col-sm-4">
                            <label>Title:</label>
                            <div class="input-group">
                                <input class="form-control" placeholder="Product Title" type="text" value="<?= isset($_GET['search_title']) ? $_GET['search_title'] : '' ?>" name="search_title">
                                <span class="input-group-btn">
                                    <button class="btn btn-default" type="submit" value="">
                                        <i class="fa fa-search"></i>
                                    </button>
                                </span>
                            </div>
                        </div>
                        <div class="col-sm-4">
                            <label>Category:</label>
                            <select name="category" class="form-control selectpicker change-products-form">
                                <option value="">None</option>
                                <?php foreach ($shop_categories as $key_cat => $shop_categorie) { ?>
                                    <option <?= isset($_GET['category']) && $_GET['category'] == $key_cat ? 'selected=""' : '' ?> value="<?= $key_cat ?>">
                                        <?php
                                        foreach ($shop_categorie['info'] as $nameAbbr) {
                                            if ($nameAbbr['abbr'] == $this->config->item('language_abbr')) {
                                                echo $nameAbbr['name'];
                                            }
                                        }
                                        ?>
                                    </option>
                                <?php } ?>
                            </select>
                        </div>
                    </form>
                </div>
            </div>
            <hr>
            <?php
            if ($products) {
                ?>
                <div class="table-responsive">
                    <table class="table table-bordered">
                        <thead>
                            <tr>
                                <th>Image</th>
                                <th>Title</th>
                                <th>Purchase Price</th>
                                <th>Old Price</th>
                                <th>Price</th>
                                <?php if ($wholesalePrice == 1) { ?>
                                    <th>Wholesale Price</th>
                                <?php } ?>
                                <th>Quantity</th>
                                <?php if ($multiSize == 1) { ?>
                                    <th>Size</th>
                                <?php } ?>
                                <?php if ($multiVendor == 1) { ?>
                                    <th>Vendor</th>
                                <?php } ?>
                                <th>Position</th>
                                <th class="text-right">Action</th>
                            </tr>
                        </thead>
                        <tbody>
                            <?php
                            // print_r($products[0]);
                            foreach ($products as $row) {
                                $u_path = 'attachments/'. SHOP_DIR .'/shop_images/';
                                if ($row->image != null && file_exists($u_path . $row->image)) {
                                    $image = base_url($u_path . $row->image);
                                } else {
                                    $image = base_url('attachments/no-image.png');
                                }
                                ?>

                                <tr>
                                    <td>
                                        <img src="<?= $image; ?>" alt="No Image" class="img-thumbnail" style="height:50px;width:50px;">
                                    </td>
                                    <td><?= $row->title; ?></td>
                                    <td><?= $row->buy_price; ?></td>
                                    <td><?= $row->old_price; ?></td>
                                    <td><?= $row->price; ?></td>
                                    <?php if ($wholesalePrice == 1) { ?>
                                        <td><?= $row->wholesale_price; ?></td>
                                    <?php } ?>
                                    <td>
                                        <?php
                                        if ($row->quantity == 0) {
                                            $color = 'label-danger';
                                        }
                                        elseif ($row->quantity <= $row->reorder_level) {
                                            $color = 'label-warning';
                                        }
                                        else {
                                            $color = 'label-success';
                                        }
                                        ?>
                                        <span style="font-size:12px;" class="label <?= $color ?>">
                                            <?= $row->quantity ?>
                                        </span>
                                    </td>
                                    <?php if ($multiSize == 1) { ?>
                                        <td>
                                            <?php if($row->size != 'N' && $row->size != '') {
                                                $sizes = explode(";", $row->size);
                                                for($n=0; $n<count($sizes); $n++){ 
                                                    echo $sizes[$n].'<br>'; 
                                                }
                                            } ?>
                                        </td>
                                    <?php } ?>
                                    <?php if ($multiVendor == 1) { ?>
                                        <td><?= $row->vendor_id > 0 ? '<a href="?show_vendor=' . $row->vendor_id . '">' . $row->vendor_name . '</a>' : 'No vendor' ?></td>
                                    <?php } ?>
                                    <td><?= $row->position ?></td>
                                    <td>
                                        <div class="pull-right">
                                            <a href="<?= base_url('admin/publish/' . $row->id) ?>" class="btn btn-info">Edit</a>
                                            <?php if($barcodeScanner == 1) { ?>
                                            <a href="<?= base_url('admin/barcode?bc=' . $row->barcode) ?>" class="btn btn-default">Print Barcode</a>
											<?php } ?>
                                            <a href="<?= base_url('admin/products/reorder?id=' . $row->id) ?>" class="btn btn-success">Reorder</a>
                                            <a href="<?= base_url('admin/products?delete=' . $row->id) ?>"  class="btn btn-danger confirm-delete">Delete</a>
                                            <?php if($row->visibility == 1){?>
                                                <a href="<?= base_url('admin/products?inactive=' . $row->id) ?>" class="btn btn-warning">Inactive</a>
                                            <?php } else { ?>
                                                <a href="<?= base_url('admin/products?active=' . $row->id) ?>" class="btn btn-success">Active</a>
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
                <?= $links_pagination ?>
            </div>
            <?php
        } else {
            ?>
            <div class ="alert alert-info">No products found!</div>
        <?php } ?>
    </div>