<div id="products">
    <div class="row">
        <div class="col-xs-8">
            <h1><button class="btn btn-default" onclick="javascript:location.href='<?= site_url("admin/reports"); ?>'"><i class="fa fa-arrow-left"></i></button> <?= $description; ?></h1>
            <h3><?= $report_info; ?></h3>
        </div>
    </div>
    <hr>
    <div class="row">
    <div class="col-xs-12">
                <div class="table-responsive">
                    <table class="table table-bordered">
                        <thead>
                            <tr>
                                <th>SL</th>
                                <th>Date</th>
                                <th>Order No.</th>
                                <th>Customer/ Supplier Name</th>
                                <th class="text-right">Received Amt.</th>
                                <th class="text-right">Given Amt.</th>
                                <th class="text-right">Balance</th>
                            </tr>
                        </thead>
                        <tbody>
                        <?php if($show_details){ 
                            $data = array_merge($details['sales'], $details['purchase'], $details['expenses']);
                            $key = array_column($data, 'date');
                            array_multisort($key, SORT_ASC, $data);

                            $i = 1; $running_balance=0;
                            foreach($data as $row){ 
                                $due = $dr = $cr = 0;
                                if(isset($row['order_type'])){
                                    $payment_type = @unserialize($row['payment_type']);
                                    if($payment_type !== false && isset($payment_type)){
                                        foreach($payment_type as $pay){
                                            if($pay['group'] == 'Due'){
                                                $due = $pay['payment_amount'];
                                            }
                                        }
                                    }
                                    if($row['order_type'] == 'sale'){
                                        $ref_url = site_url("admin/sale/print_inv/".$row['id']);
                                        $dr = $row['total'] - $due;
                                    }
									elseif($row['order_type'] == 'sale_return'){
                                        $ref_url = site_url("admin/sale/print_inv/".$row['id']);
										$row['total'] = -($row['total']);
                                        $dr = $row['total'] - $due;
                                    } 
									elseif($row['order_type'] == 'purchase'){
                                        $ref_url = site_url("admin/purchase/print_inv/".$row['id']);
                                        $cr = $row['total'] - $due;
                                    }
									elseif($row['order_type'] == 'purchase_return'){
                                        $ref_url = site_url("admin/purchase/print_inv/".$row['id']);
                                        $cr = $row['total'] - $due;
                                    }
                                }
                                else {
                                    $cr = $row['amount'];
                                    $ref_url = site_url("admin/expenses/print_bill/".$row['id']);
                                    $row['name'] = $row['group'] ." - ". $row['title'];
                                    $row['order_id'] = $row['id'];
                                }
                                $running_balance += $dr;
                                $running_balance -= $cr;
                                ?>
                                <tr>
                                    <td><?= $i++; ?></td>
                                    <td><?= $row['date']; ?></td>
                                    <td><a href="<?= $ref_url; ?>" target="_blank"><?= $row['order_id']; ?></a></td>
                                    <td><?= $row['name']; ?></td>
                                    <td><?= $dr; ?></td>
                                    <td><?= $cr; ?></td>
                                    <td><?= $running_balance; ?></td>
                                </tr> <?php
                            } 
                        } else { 
                                $i = 1; $running_balance=0;
                                $due = $sale = $purchase = $expense = 0;
                                foreach($details['sales'] as $row){
                                    $payment_type = @unserialize($row['payment_type']);
                                    if($payment_type !== false && isset($payment_type)){
                                        foreach($payment_type as $pay){
                                            if($pay['group'] == 'Due'){
                                                $due = $pay['payment_amount'];
                                            }
                                        }
                                    }
									if($row['order_type'] == 'sale_return'){
										$row['total'] = -($row['total']);
									}
                                    $sale += $row['total'] - $due;
                                    $running_balance += $row['total'] - $due;
                                }
                                foreach($details['purchase'] as $row){
                                    $payment_type = @unserialize($row['payment_type']);
                                    if($payment_type !== false && isset($payment_type)){
                                        foreach($payment_type as $pay){
                                            if($pay['group'] == 'Due'){
                                                $due = $pay['payment_amount'];
                                            }
                                        }
                                    }
									if($row['order_type'] == 'purchase_return'){
										$row['total'] = -($row['total']);
									}
                                    $purchase += $row['total'] - $due;
                                    $running_balance -= $row['total'] - $due;
                                }
                                foreach($details['expenses'] as $row){
                                    $expense += $row['amount'];
                                    $running_balance -= $row['amount'];
                                }
                                ?>
                                <tr>
                                    <td><?= $i++; ?></td>
                                    <td></td>
                                    <td></td>
                                    <td>Sales</td>
                                    <td><?= $sale; ?></td>
                                    <td></td>
                                    <td><?= $running_balance; ?></td>
                                </tr>
                                <tr>
                                    <td><?= $i++; ?></td>
                                    <td></td>
                                    <td></td>
                                    <td>Purchase</td>
                                    <td></td>
                                    <td><?= $purchase; ?></td>
                                    <td><?= $running_balance; ?></td>
                                </tr>
                                <tr>
                                    <td><?= $i++; ?></td>
                                    <td></td>
                                    <td></td>
                                    <td>Expenses</td>
                                    <td></td>
                                    <td><?= $expense; ?></td>
                                    <td><?= $running_balance; ?></td>
                                </tr>
                            <?php } ?>
                        </tbody>
                    </table>
                </div>
            </div>
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