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
    <h1><img src="<?= base_url('assets/imgs/products-img.png') ?>" class="header-img" style="margin-top:-2px;"> <?= $description; ?></h1>
    <hr>
    <div class="row">
        <div class="col-xs-12">
			<?php
            if ($wishs) {
                ?>
                <div class="table-responsive">
                    <table class="table table-bordered">
                        <thead>
                            <tr>
                                <th>SL</th>
                                <th>Date</th>
                                <th>Product Name</th>
                                <th>Vendor Name</th>
                                <th>Stock</th>
                                <th>Customer Name</th>
                                <th>Phone Number</th>
                                <th>Req. Qty.</th>
                                <th>Bid Amount</th>
                                <th>Status</th>
                                <th class="text-right">Action</th>
                            </tr>
                        </thead>
                        <tbody>
                            <?php
                            $i = 1;
                            foreach ($wishs as $row) {
                                $u_path = 'attachments/'. SHOP_DIR .'/shop_images/';
                                if ($row->image != null && file_exists($u_path . $row->image)) {
                                    $image = base_url($u_path . $row->image);
                                } else {
                                    $image = base_url('attachments/no-image.png');
                                }
                                ?>
                                <tr>
                                    <td><?= $i++; ?></td>
                                    <td><?= $row->date; ?></td>
                                    <td>
                                        <img src="<?= $image; ?>" alt="No Image" class="img-thumbnail" style="height:50px;width:50px;">
                                        <a href="<?= site_url($row->url); ?>" target="_blank"><?= $row->title; ?></a>
                                    </td>
                                    <td><?= $row->vendor_name; ?></td>
                                    <td><?= $row->stock; ?></td>
                                    <td><?= $row->name; ?></td>
                                    <td><?= $row->phone; ?></td>
                                    <td><?= $row->quantity; ?></td>
                                    <td><?= $row->amount; ?></td>
                                    <td><?php if($row->status == 1)
                                                echo "Open";
                                            elseif($row->status == 2) 
                                                echo "Completed";
                                            else echo "Rejected"; 
                                        ?>
                                    </td>
                                    <td>
                                        <div class="pull-right">
                                            <?php if($row->status == 1) { ?>
                                            <a href="<?= base_url("admin/wishs/accept/". $row->id); ?>" class="btn btn-success">Accept</a>
                                            <a href="<?= base_url("admin/wishs/reject/". $row->id); ?>" class="btn btn-warning">Reject</a>
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

    <script>
        $(document).ready( function () {
            $('.table').DataTable();
        } );
    </script>