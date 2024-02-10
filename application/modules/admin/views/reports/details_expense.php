<div id="products">
    <div class="row">
        <div class="col-xs-8">
            <h1><button class="btn btn-default" onclick="javascript:location.href='<?= site_url("admin/reports"); ?>'"><i class="fa fa-arrow-left"></i></button> <?= $description; ?></h1>
            <h3><?= $report_info; ?></h3>
        </div>
    </div>
    <hr>
    <div class="row">
        <?php if($details){ ?>
            <div class="col-xs-12">
                <div class="table-responsive">
                    <table class="table table-bordered">
                        <thead>
                            <tr>
                                <th>SL</th>
                                <th>Expense Type</th>
                                <th>Date</th>
                                <th>Expense Title</th>
                                <th>Details</th>
                                <th class="text-right">Amount</th>
                            </tr>
                        </thead>
                        <tbody>
                            <?php
                            $i = 1; $footer_total = 0;
                            foreach ($details as $row) {
                                $footer_total += $row['amount'];
                                ?>
                                <tr>
                                    <td><?= $i++; ?></td>
                                    <td><?= $row['group']; ?></td>
                                    <td><?= $row['date']; ?></td>
                                    <td><?= $row['title']; ?></td>
                                    <td><?= $row['details']; ?></td>
                                    <td class="text-right"><?= $row['amount']; ?></td>
                                </tr>
                                <?php
                            } ?>
                            <tr>
                                <td><?= $i++; ?></td>
                                <td></td>
                                <td></td>
                                <td></td>
                                <td class="text-right">Total</td>
                                <td class="text-right"><?= $footer_total; ?></td>
                            </tr>
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
            $('.table').DataTable({
                dom: 'Bftlpr',
                buttons: [
                    {
                        extend: 'print',
                        text: 'Print',
                        title: '<?= $description; ?>',
                        messageTop: 'Date: <?= $report_info; ?>'
                    },
                    {
                        extend: 'pdf',
                        text: 'PDF',
                        title: '<?= $description; ?>',
                        messageTop: 'Date: <?= $report_info; ?>'
                    },
                    {
                        extend: 'excel',
                        text: 'Excel',
                        title: '<?= $description; ?>',
                        messageTop: 'Date: <?= $report_info; ?>'
                    }
                ]
            });
        } );
    </script>