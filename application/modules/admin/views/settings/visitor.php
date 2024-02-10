<h1><img src="<?= base_url('assets/imgs/template-admin-logo.png') ?>" class="header-img" style="margin-top:-2px;"> <?= $description; ?></h1>
<hr>
<form id="saveTemplate" method="POST" action="">
    <input type="hidden" name="template" class="template-name" value="">
</form>
<div class="row">
    <div class="col-xs-12">
        <?php
        if ($visitors) {
            ?>
            <div class="table-responsive">
                <table class="table table-bordered">
                    <thead>
                        <tr>
                            <th>SL</th>
                            <th>Date</th>
                            <th>IP Address</th>
                            <th>Operating System</th>
                            <th>Browser</th>
                            <th>Version</th>
                            <th>Robot</th>
                            <th>Mobile</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php
                        $i = 1;
                        foreach ($visitors as $row) {
                            ?>

                            <tr>
                                <td><?= $i++; ?></td>
                                <td><?= $row->date; ?></td>
                                <td><?= $row->ip_address; ?></td>
                                <td><?= $row->os; ?></td>
                                <td><?= $row->browser; ?></td>
                                <td><?= $row->browser_version; ?></td>
                                <td><?= $row->is_robot == 0 ? 'No' : 'Yes'; ?></td>
                                <td><?= $row->is_mobile == 0 ? 'No' : 'Yes'; ?></td>
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