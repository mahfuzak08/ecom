<h1><img src="<?= base_url('assets/imgs/brands.jpg') ?>" class="header-img" style="margin-top:-3px;"> Delivery Location</h1>
<hr>
<div class="row">
    <div class="col-sm-6 col-md-4">
        <a href="javascript:void(0);" onclick="set_data(0,'',0)" data-toggle="modal" data-target="#addPage" class="btn btn-default" style="margin-bottom:10px;">Add location</a>
        <?php if (!empty($location)) {
            ?>
            <ul class="list-group list-none">
                <?php
                foreach ($location as $row) {
                    ?>
                    <li class="list-group-item">
                        <?= $row['name'] ?> = <?= $row['cost'] ?> <?= CURRENCY ?>
                        <a href="?delete=<?= $row['id'] ?>" class="pull-right confirm-delete"><i class="fa fa-trash"></i></a>
						<a href="javascript:void(0);" onclick="set_data(<?= $row['id'] ?>, '<?= $row['name'] ?>', <?= $row['cost'] ?>)" data-toggle="modal" data-target="#addPage" class="pull-right" style="margin-right:10px"><i class="fa fa-edit"></i></a>
                    </li>
                <?php }
                ?>
            </ul>
        <?php } else {
        ?>
		<div class="alert alert-info">No location added!</div>
		<?php } ?>
    </div>
</div>
<div class="modal fade" id="addPage" tabindex="-1" role="dialog" aria-labelledby="myModalLabel">
    <div class="modal-dialog" role="document">
        <div class="modal-content">
            <form action="" method="POST">
                <div class="modal-header">
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
                    <h4 class="modal-title" id="myModalLabel">Add new location</h4>
                </div>
                <div class="modal-body">
                    <div class="form-group">
                        <label for="name">Location name</label>
                        <input type="text" name="name" class="form-control" id="name">
                    </div>
                    <div class="form-group">
                        <label for="cost">Location cost</label>
                        <input type="text" name="cost" class="form-control" id="cost">
                    </div>
                </div>
                <div class="modal-footer">
                    <input type="hidden" name="id" id="id" value="0">
                    <button type="button" class="btn btn-default" data-dismiss="modal">Cancel</button>
                    <button type="submit" class="btn btn-primary">Add</button>
                </div>
            </form>
        </div>
    </div>
</div>
<script>
    function set_data(id, name, cost){
        $("#addPage #id").val(id);
        $("#addPage #name").val(name);
        $("#addPage #cost").val(cost);
    }
</script>