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
                                <th>Transection Type</th>
                                <th>Trnx ID</th>
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
                                    <td><?= $row['trans_date']; ?></td>
                                    <td><?= ucwords(implode(" ", explode("_", $row['type']))); ?></td>
                                    <td>
                                        <?php if(strpos(strtolower($row['type']), 'sale') !== FALSE){?>
                                            <a href="<?= site_url("admin/sale/print_inv/".$row['trans_no']); ?>" target="_blank">#<?= $row['trans_no']; ?></a>
                                        <?php } elseif(strpos(strtolower($row['type']), ('purchase')) !== FALSE){?>
                                            <a href="<?= site_url("admin/purchase/print_inv/".$row['trans_no']); ?>" target="_blank">#<?= $row['trans_no']; ?></a>
                                        <?php } elseif(strpos(strtolower($row['type']), ('customer_payment')) !== FALSE){?>
                                            <a href="<?= site_url("admin/customer/print_receipt/".$row['trans_no']); ?>" target="_blank">#<?= $row['trans_no']; ?></a>
                                        <?php } elseif(strpos(strtolower($row['type']), 'expense') !== FALSE){?>
                                            <a href="<?= site_url("admin/expenses/print_bill/".$row['trans_no']); ?>" target="_blank">#<?= $row['trans_no']; ?></a>
                                        <?php } elseif(strpos($access[0]['access'], ACCOUNTS_TRANS_EDIT)>-1) { ?>
                                            <a href="?edit=<?= $row['id']; ?>" style="color:red" >Edit</a>
                                        <?php } ?>
                                    </td>
                                    <td><?= $row['details']; ?></td>
                                    <td class="text-right"><?= number_format($row['amount'], 2); ?></td>
                                </tr>
                                <?php
                            } ?>
                            <tr>
                                <td><?= $i++; ?></td>
                                <td></td>
                                <td></td>
                                <td></td>
                                <td class="text-right">Total</td>
                                <td class="text-right"><?= number_format($footer_total, 2); ?></td>
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