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
                                <th>Date</th>
                                <th>Order No.</th>
                                <th>Supplier Name</th>
                                <th>Purchase From</th>
                                <th class="text-right">Order Amt.</th>
                                <th class="text-right">Due Amt.</th>
                                <th class="text-right">Given Amt.</th>
                                <?php if($show_running_balance): ?>
                                <th class="text-right">Total Purchase</th>
                                <?php endif; ?>
                            </tr>
                        </thead>
                        <tbody>
                            <?php
                            $i = 1; $running_balance=0;
                            foreach ($details as $row) {
                                $due = 0;
                                $payment_type = @unserialize($row['payment_type']);
                                if($payment_type !== false && isset($payment_type)){
                                    foreach($payment_type as $pay){
                                        if($pay['group'] == 'Due'){
                                            $due = $pay['payment_amount'];
                                        }
                                    }
                                }
                                $running_balance += $row['total'] - $due;
                                ?>
                                <tr>
                                    <td><?= $i++; ?></td>
                                    <td><?= $row['date']; ?></td>
                                    <td><a href="<?= site_url("admin/purchase/print_inv/".$row['id']); ?>" target="_blank"><?= $row['order_id']; ?></a></td>
                                    <td><?= $row['name']; ?></td>
                                    <td><?= $row['clean_referrer']; ?></td>
                                    <td><?= $row['total']; ?></td>
                                    <td><?= $due; ?></td>
                                    <td><?= $row['total'] - $due; ?></td>
                                    <?php if($show_running_balance): ?>
                                    <td><?= $running_balance; ?></td>
                                    <?php endif; ?>
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