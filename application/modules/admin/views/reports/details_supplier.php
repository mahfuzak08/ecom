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
                        <?php if($show_running_balance): ?>
                        <thead>
                            <tr>
                                <th>SL</th>
                                <th>Date</th>
                                <th>PO No</th>
                                <th>Type</th>
                                <th>PO Amount</th>
                                <th>Payment Amount</th>
                                <th>Due Amount</th>
                                <th>Balance</th>
                            </tr>
                        </thead>
                        <tbody>
                        <?php
                            $i = 1; $running_balance = 0;
                            foreach ($details as $row) {
                                ?>
                                <tr>
                                    <td><?= $i++; ?></td>
                                    <td><?= $row['created_at']; ?></td>
                                    <td>
                                        <?php if($row['trans_no'] != NULL){ ?>
                                            <a href="<?= site_url("admin/purchase/print_inv/".$row['trans_no']); ?>" target="_blank"><?= $row['order_id']; ?></a>
                                        <?php } ?>
                                    </td>
                                    <td style="text-transform: capitalize;">
                                        <?= $row['order_type']!= NULL ? $row['order_type'].' '.$row['note'] : 'Direct '.$row['note']; ?>
                                    </td>
                                    <td><?= ($row['total'] != NULL && $row['note'] != 'Due Payment') ? $row['total'] : 0; ?></td>
                                    <td><?php 
                                        $row['pay_amt'] = $row['amt'];
                                        $row['due_amt'] = 0;
                                        if($row['order_type'] != NULL && $row['note'] != 'Due Payment'){
                                            $paylist = @unserialize($row['payment_type']); 
                                            if($paylist !== false){
                                                foreach ($paylist as $value) {
                                                    if($value['group'] == 'Due'){
                                                        $row['due_amt'] += $value['payment_amount'];
                                                    }
                                                }
                                            }else{
                                                $row['pay_amt'] = $row['total'];
                                                $row['due_amt'] = 0;
                                            }
                                        }
                                        $row['pay_amt'] -= $row['due_amt'];
                                        echo $row['pay_amt'];
                                        ?>
                                    </td>
                                    <td><?= $row['due_amt']; ?></td>
                                    <td><?= $row['asof_date_due']; ?></td>
                                </tr>
                                <?php
                            }
                            ?>
                        </tbody>
                        <?php else: ?>
                        <thead>
                            <tr>
                                <th>SL</th>
                                <th>Supplier Name</th>
                                <th>Phone No.</th>
                                <th>Email</th>
                                <th>PO(s)</th>
                                <th class="text-right">PO Amount</th>
                                <th class="text-right">Total Dues</th>
                            </tr>
                        </thead>
                        <tbody>
                            <?php
                            $i = 1; $footer_toamt = 0; $footer_balance = 0;
                            foreach ($details as $row) {
                                $footer_toamt += $row['toamt'];
                                $footer_balance += $row['balance'];
                                ?>
                                <tr>
                                    <td><?= $i++; ?></td>
                                    <td><?= $row['name']; ?></td>
                                    <td><?= $row['mobile']; ?></td>
                                    <td><?= $row['email']; ?></td>
                                    <td><?= $row['noorder']; ?></td>
                                    <td class="text-right"><?= $row['toamt']; ?></td>
                                    <td class="text-right"><?= $row['balance']; ?></td>
                                </tr>
                                <?php
                            } ?>
                            <tr>
                                <td><?= $i++; ?></td>
                                <td></td>
                                <td></td>
                                <td></td>
                                <td class="text-right">Total</td>
                                <td class="text-right"><?= $footer_toamt; ?></td>
                                <td class="text-right"><?= $footer_balance; ?></td>
                            </tr>
                        </tbody>
                        <?php endif; ?>
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