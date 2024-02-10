<div id="products">
    <div class="row">
        <div class="col-xs-8">
            <h1><button class="btn btn-default" onclick="javascript:location.href='<?= site_url("admin/reports"); ?>'"><i class="fa fa-arrow-left"></i></button> <?= $description; ?></h1>
            <h3><?= $report_info; ?></h3>
            <!--<h2>This report is under maintenance. please don't be afraid to see the result</h2>-->
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
                                <th style="width:120px !important">Date</th>
                                <th>Product Name</th>
                                <th>Reference</th>
                                <th>Description</th>
                                <th width="150px" class="text-right">Oty In</th>
                                <th width="150px" class="text-right">Oty Out</th>
                                <?php if($show_running_balance): ?>
                                <th width="150px" class="text-right">Balance</th>
                                <?php endif; ?>
                            </tr>
                        </thead>
                        <tbody>
                            <?php
                            $i = 1; $running_balance=0; $total_stock_price = 0;
                            $has_bf = false; $bf_running_balance = 0;
                            $total_sale_q = 0; $total_buy_q = 0;
                            $total_sale_p = 0; $total_buy_p = 0;
                            $total_p_p = 0;
                            for($n=0; $n<count($details); $n++) {
                                $row = $details[$n];
                                $buy_price = (float) $row['buy_price'];
                                $sale_price = (float) $row['price'];
                                if($row['sales_price'] != ''){
                                    foreach(unserialize($row['sales_price']) as $line=>$item) {
                                        if($item['product_info']['id'] == $row['item_id']){
                                            $sale_price = (float) $item['product_info']['price'];
                                            break;
                                        }
                                    }
                                    // $sp = unserialize($row['sales_price']);
                                    // file_put_contents('asdf.txt', json_encode($sp));
                                }
								if($row['trans_date'] < $start_date){
								    $has_bf = true;
								    $bf_running_balance += $row['ref_type'] == 'in' || $row['ref_type'] == 'sale_return' ? $row['quantity'] : 0;
                                    $bf_running_balance -= $row['ref_type'] == 'out' || $row['ref_type'] == 'purchase_return' ? $row['quantity'] : 0;
								}
								else {
								    $has_bf = false;
								    
								    if(! $has_bf && $bf_running_balance > 0 && $row['trans_date'] >= $start_date){ ?>
                                        <tr class="custom_head">
        				 					<td><?= $i++; ?></td>
        				 					<td><?= $start_date; ?></td>
        				 					<td><a href="<?= site_url($row['url']); ?>" target="_blank"><?= $row['title']; ?></a></td>
        				 					<td>BF</td>
        				 					<td></td>
        				 					<td></td>
        				 					<td></td>
        				 					<td><?= $bf_running_balance; ?></td>
        				 				</tr><?php 
        								$running_balance = $bf_running_balance;
    								    $bf_running_balance = 0;
    								}
    								
                                    $running_balance += $row['ref_type'] == 'in' || $row['ref_type'] == 'sale_return' ? $row['quantity'] : 0;
                                    $running_balance -= $row['ref_type'] == 'out' || $row['ref_type'] == 'purchase_return' ? $row['quantity'] : 0;
                                    
                                    if($row['ref_type'] == 'in' || $row['ref_type'] == 'sale_return'){
                                        $total_buy_q += $row['quantity'];
                                        $total_buy_p += $buy_price * $row['quantity'];
                                    }elseif($row['ref_type'] == 'out' || $row['ref_type'] == 'purchase_return'){
                                        $total_sale_q += $row['quantity'];
                                        $total_p_p += $buy_price * $row['quantity'];
                                        $total_sale_p += $sale_price * $row['quantity'];
                                    }
                                    
                                    ?>
                                    <tr>
                                        <td><?= $i++; ?></td>
                                        <td style="width:120px !important"><?= $row['trans_date']; ?></td>
                                        <td><a href="<?= site_url($row['url']); ?>" target="_blank"><?= $row['title']; ?></a></td>
                                        <td><?php 
                                            if($row['ref_id'] != 0){
                                                if($row['ref_type'] == 'in' || $row['ref_type'] == 'purchase_return'){?>
                                                    <a href="<?= site_url("admin/purchase/print_inv/". $row['ref_id']); ?>" target="_blank">Purchase <?= $row['ref_type'] == 'purchase_return' ? 'Return' : ''; ?> #<?= $row['ref_id']; ?></a>
                                                <?php } elseif($row['ref_type'] == 'out' || $row['ref_type'] == 'sale_return') { ?>
                                                    <a href="<?= site_url("admin/sale/print_inv/". $row['ref_id']); ?>" target="_blank">Sale <?= $row['ref_type'] == 'sale_return' ? 'Return' : ''; ?> #<?= $row['ref_id']; ?></a>
                                                <?php }
                                            } ?>
                                        </td>
                                        <td><?= $row['description']; ?></td>
                                        <td><?= $row['ref_type'] == 'in' || $row['ref_type'] == 'sale_return' ? $row['quantity'] : ''; ?></td>
                                        <td><?= $row['ref_type'] == 'out' || $row['ref_type'] == 'purchase_return' ? $row['quantity'] : ''; ?></td>
                                        <?php if($show_running_balance): ?>
                                        <td><?= $running_balance; ?></td>
                                        <?php endif; ?>
                                    </tr>
                                    <?php
                                    if($stock_type>0 && ! empty($details[$n+1])){
                                        if($row['item_id'] != $details[$n+1]['item_id']){
        									if($stock_type == 2){ ?>
        									<tr class="custom_head">
        										<td><?= $i++; ?></td>
        										<td>Summery</td>
        										<td>Stock in Balance</td><td align="right"><?= $running_balance; ?></td>
        										<td>Purchase Price</td><td align="right">Tk. <?= number_format((float)$buy_price, 2, '.', ','); ?></td>
        										<td>Total</td><td align="right">Tk. <?= number_format((float)$running_balance*$buy_price, 2, '.', ','); $total_stock_price+=$running_balance*$buy_price; ?></td>
        									</tr>
        									<?php } else { ?>
        									<tr class="custom_head">
        										<td><?= $i++; ?></td>
        										<td></td>
        										<td></td>
        										<td></td>
        										<td></td>
        										<td></td>
        										<td></td>
        										<td></td>
        									</tr>
        									<?php } 
        									$running_balance = 0;
                                        }
    								}
    								
    								// Total summery of the report, print at the end of the report rows
    								if($stock_type==0 && empty($details[$n+1])){?>
    									<tr class="custom_head">
    										<td><?= $i++; ?></td>
    										<td>Total</td>
    										<td></td>
    										<td></td>
    										<td></td>
    										<td>In <?= $total_buy_q; ?> items<br>Price <?= number_format((float)$total_buy_p, 2, '.', ','); ?> Tk.</td>
    										<td>Out <?= $total_sale_q; ?> items <br>Purchase Price <?= number_format((float)$total_p_p, 2, '.', ','); ?> Tk. <br> Sales Price <?= number_format((float)$total_sale_p, 2, '.', ','); ?> Tk.</td>
    									</tr>
    									<?php 
    								}
    								elseif($stock_type>0 && empty($details[$n+1])){
    									if($stock_type == 2){ ?>
    									<tr class="custom_head">
    										<td><?= $i++; ?></td>
    										<td></td>
    										<td></td><td></td>
    										<td></td><td></td>
    										<td>Total Stock </td><td align="right">Tk. <?= number_format((float)$total_stock_price, 2, '.', ',');?></td>
    									</tr>
    									<?php 
    									}
    								}
								}
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