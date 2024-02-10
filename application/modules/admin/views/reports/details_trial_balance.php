<div id="products">
    <div class="row">
        <div class="col-xs-8">
            <h1><button class="btn btn-default" onclick="javascript:location.href='<?= site_url("admin/reports"); ?>'"><i class="fa fa-arrow-left"></i></button> <?= $description; ?></h1>
            <h3><?= $report_info; ?></h3>
        </div>
    </div>
    <hr>
    <div class="row">
        <?php if($details){ $dr = 0; $cr = 0; ?>
            <div class="col-xs-12">
                <div class="table-responsive">
                    <table class="table table-bordered">
                        <thead>
                            <tr>
                                <th>Account Name</th>
                                <th>Ref</th>
                                <th class="text-right">Debit</th>
                                <th class="text-right">Credit</th>
                            </tr>
                        </thead>
                        <tbody>
                            <?php foreach($details['accountsSum'] as $row){ $dr += $row['amount']; ?>
                                <tr>
                                    <td><?= $row['name']; ?></td>
                                    <td></td>
                                    <td class="text-right"><?= $row['amount'] > 0 ? $row['amount'] : ''; ?></td>
                                    <td class="text-right"><?= $row['amount'] < 0 ? $row['amount'] : ''; ?></td>
                                </tr>
                            <?php } ?>
                            <tr>
                                <td>Capital/ Liability</td>
                                <td></td>
                                <td class="text-right"></td>
                                <td class="text-right"><?php $cr += $details['capitals']; echo $details['capitals']; ?></td>
                            </tr>
                            <tr>
                                <td>Sales</td>
                                <td></td>
                                <td class="text-right"><?php $dr += $details['salesTotal']; echo $details['salesTotal']; ?></td>
                                <td class="text-right"></td>
                            </tr>
                            <tr>
                                <td>Accounts Receivable</td>
                                <td></td>
                                <td class="text-right"><?php $dr += $details['accountsReceivable']; echo $details['accountsReceivable']; ?></td>
                                <td class="text-right"></td>
                            </tr>
                            <tr>
                                <td>Inventory</td>
                                <td></td>
                                <td class="text-right"><?php $dr += $details['stocks']; echo $details['stocks']; ?></td>
                                <td class="text-right"></td>
                            </tr>
                            <?php $total_expense = 0; foreach($details['expenseSum'] as $row){ $total_expense+= $row['amount']; $dr += $row['amount'];  ?>
                                <tr>
                                    <td><?= $row['title']. " Expense"; ?></td>
                                    <td></td>
                                    <td class="text-right"><?= $row['amount'] > 0 ? $row['amount'] : ''; ?></td>
                                    <td class="text-right"><?= $row['amount'] < 0 ? $row['amount'] : ''; ?></td>
                                </tr>
                            <?php } ?>
                            <tr>
                                <td>Purchase</td>
                                <td></td>
                                <td class="text-right"></td>
                                <td class="text-right"><?php $cr += $details['purchaseTotal']; echo $details['purchaseTotal']; ?></td>
                            </tr>
                            <tr>
                                <td>Accounts Payable</td>
                                <td></td>
                                <td class="text-right"></td>
                                <td class="text-right"><?php $cr += $details['accountsPayable']; echo $details['accountsPayable']; ?></td>
                            </tr>
                            <tr>
                                <td>Sales Revenues</td>
                                <td></td>
                                <td class="text-right"></td>
                                <td class="text-right"><?= $details['salesTotal'] - $details['purchaseTotal'] - $total_expense; ?></td>
                            </tr>
                            <tr>
                                <td></td>
                                <td class="text-right">Total</td>
                                <td class="text-right"><?= $dr; ?></td>
                                <td class="text-right"><?= $dr; ?></td>
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