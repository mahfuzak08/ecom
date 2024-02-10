<?php if(strpos($access[0]['access'], CLIENTS)>-1) { ?>
<script src="<?= base_url('assets/ckeditor/ckeditor.js') ?>"></script>
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
            <a href="javascript:void(0);" onclick="set_data(0,'')" data-toggle="modal" data-target="#addClient" class="btn btn-default" style="margin-bottom:10px;">Add Client</a>
        </div>
    </div>
    <hr>
    <div class="row">
        <div class="col-xs-12">
			<?php
            if ($clients) {
                ?>
                <div class="table-responsive">
                    <table class="table table-bordered">
                        <thead>
                            <tr>
                                <th>SL</th>
                                <th>Created Date</th>
                                <th>Base URL</th>
                                <th>Status</th>
                                <th class="text-right">Action</th>
                            </tr>
                        </thead>
                        <tbody>
                            <?php
                            $i = 1;
                            foreach ($clients as $row) {?>
                                <tr>
                                    <td><?= $i++; ?></td>
                                    <td><?= $row["created_at"]; ?></td>
                                    <td><?= $row["base_url"]; ?></td>
                                    <td><?= $row["is_active"] == 1 ? "Active" : "Inactive"; ?></td>
                                    <td>
                                        <div class="pull-right">
                                            <a href="<?= base_url("admin/clients?ecomId=".$row["id"] ."&status=". $row["has_ecom"]); ?>" class="btn btn-<?= $row["has_ecom"] == 1 ? "default" : "success"; ?>">Ecom <?= $row["has_ecom"] == 1 ? "Off" : "On"; ?></a>
                                            <a href="<?= base_url("admin/clients?edit=".$row["id"]); ?>" class="btn btn-info">Edit</a>
                                            <a href="<?= base_url("admin/clients?noti=".$row["id"]); ?>" class="btn <?= $row["notification"] == '<p>no</p>' ? 'btn-default' : 'btn-warning'; ?>">Notify</a>
                                            <a href="<?= base_url("admin/clients?id=". $row["id"] ."&status=". $row["is_active"]); ?>" class="btn btn-<?= $row["is_active"] == 1 ? "warning" : "default"; ?>"><?= $row["is_active"] == 1 ? "Inactive" : "Active"; ?></a>
                                            <a href="<?= base_url("admin/clients?delete=".$row["id"]); ?>" class="btn btn-danger confirm-delete">Delete</a>
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
    <div class="modal fade" id="addClient" tabindex="-1" role="dialog" aria-labelledby="myModalLabel">
        <div class="modal-dialog" role="document">
            <div class="modal-content">
                <form action="" method="POST">
                    <div class="modal-header">
                        <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
                        <h4 class="modal-title" id="myModalLabel"><?= isset($_GET['edit']) ? "Update shop": "Add new shop"; ?></h4>
                    </div>
                    <div class="modal-body">
                        <div class="form-group">
                            <label for="name">Base URL</label>
                            <input type="text" name="base_url" value="<?= @$client[0]["base_url"]; ?>" class="form-control" id="name">
                        </div>
                    </div>
                    <div class="modal-footer">
                        <input type="hidden" name="id" id="id" value="<?= isset($client[0]["id"]) ? $client[0]["id"]: "0"; ?>">
                        <button type="button" class="btn btn-default" data-dismiss="modal">Cancel</button>
                        <button type="submit" name="add_client" class="btn btn-primary">Save</button>
                    </div>
                </form>
            </div>
        </div>
    </div>
    <div class="modal fade" id="addNoti" tabindex="-1" role="dialog" aria-labelledby="myModalNoti">
        <div class="modal-dialog" role="document">
            <div class="modal-content">
                <form action="" method="POST">
                    <div class="modal-header">
                        <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
                        <h4 class="modal-title" id="myModalNoti">Notification</h4>
                    </div>
                    <div class="modal-body">
                        <div class="form-group">
                            <label for="name">Base URL</label>
                            <textarea name="notification" id="notification" class="form-control"><?= @$client[0]["notification"]; ?></textarea>
                            <script>
                                CKEDITOR.replace('notification');
                                CKEDITOR.config.entities = false;
                            </script>
                        </div>
                    </div>
                    <div class="modal-footer">
                        <input type="hidden" name="id" value="<?= isset($client[0]["id"]) ? $client[0]["id"]: "0"; ?>">
                        <button type="button" class="btn btn-default" data-dismiss="modal">Cancel</button>
                        <button type="submit" name="add_notification" class="btn btn-primary">Save</button>
                    </div>
                </form>
            </div>
        </div>
    </div>
    <script>
        $(document).ready( function () {
            $('.table').DataTable();
            <?php if (isset($_GET['edit'])) { ?>
                $("#addClient").modal('show');
            <?php } ?>
            <?php if (isset($_GET['noti'])) { ?>
                $("#addNoti").modal('show');
            <?php } ?>
        } );
        function set_data(id, name){
            $("#addClient .id").val(id);
            $("#addClient .name").val(name);
        }
    </script>
<?php } else { echo "<h1>404</h1><h3>Page not  found</h3>"; } ?>