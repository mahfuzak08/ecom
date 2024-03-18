<?php
function isSerialized($value) {
    if (!is_string($value)) {
        return false;
    }

    $data = @unserialize($value);
    if ($data === false) {
        // Check if the string was serialized with PHP 7.0's serialize_precision
        $value = preg_replace('/\bs:\d+:"[^"]+"\b/', 's:0:""', $value);
        $data = @unserialize($value);
        if ($data === false) {
            return false;
        }
    }

    return true;
}
?>
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
                <div id="report" class="table-responsive">
					<table width="100%">
						<tr>
							<td style="vertical-align: top;text-align:center">
								<img src="<?= base_url('attachments/'. SHOP_DIR .'/site_logo/' . $sitelogo) ?>" alt="<?= $_SERVER['HTTP_HOST'] ?> Logo" style="max-width:200px;max-height:50px;">
							</td>
						</tr>
						<tr>
							<td style="vertical-align: top;padding:5px 10px;text-align:center">
								<b style="font-size:18px;"><?= $companyName; ?></b><br>
								<b style="font-size:16px;">Mobile: <?= $footerContactPhone; ?></b><br>
							</td>
						</tr>
						<tr>
							<td style="vertical-align: top;padding:5px 10px;text-align:center; font-size: 20px;">Report from <?= $report_info; ?></td>
						</tr>
					</table>
                    <table style="border: 1px solid #CCC; width: 100%; border-collapse: collapse; line-height: 30px;" class="table table-bordered">
                        <thead>
                            <tr style="background-color: #CCC;">
                                <th style="text-align: left; padding-left: 10px;">Account Name</th>
                                <th>Ref</th>
                                <th style="text-align: right; padding-right: 10px;">Amount</th>
                            </tr>
                        </thead>
                        <tbody>
                            <?php foreach($details['accountsSum'] as $row){ ?>
                                <tr style="border-bottom: 1px solid #CCC;">
                                    <td style="padding-left: 10px;"><?= $row['name']; ?></td>
                                    <td></td>
                                    <td style="text-align: right; padding-right: 10px;">
										<?= $row['amount'] > 0 ? number_format($row['amount'], 2, '.', ',') : ''; ?>
										<?= $row['amount'] < 0 ? "(-)" . number_format($row['amount'], 2, '.', ',') : ''; ?>
									</td>
                                </tr>
                            <?php } ?>
                            <tr style="border-bottom: 1px solid #CCC;">
                                <td style="padding-left: 10px;">Sales</td>
                                <td></td>
                                <td style="text-align: right; padding-right: 10px;"><?php 
									$sale_amt = 0; $buy_amt = 0; $order_amt = 0;
									$file = 'usdata.txt';
									$due_payment = 0; $collection_payment = 0; $cash_payment = 0;
									for($i=0; $i<count($details['salesTotal']); $i++){
									   // $sale_amt += $details['salesTotal'][$i]['total'];
										foreach(unserialize($details['salesTotal'][$i]['products']) as $line=>$item) {
										  	$sale_amt += (float) $item['product_info']['price'] * (float) $item['product_info']['quantity'];
											if((float) $item['product_info']['cost_price'] > 0)
											    $buy_amt += (float) $item['product_info']['cost_price'] * (float) $item['product_info']['quantity'];
											else{
											    for($ii = 0; $ii<count($details['salesTotal_buyinfo']); $ii++){
											        if($details['salesTotal_buyinfo'][$ii]['pid'] == $item['product_info']['id']){
											            $buy_amt += (float) $details['salesTotal_buyinfo'][$ii]['buy_price'] * (float) $item['product_info']['quantity'];
											            break;
											        }
											    }
											}
										}
										$order_amt += $details['salesTotal'][$i]['total'];
										if(isSerialized($details['salesTotal'][$i]['payment_type'])){
										    $sp = unserialize($details['salesTotal'][$i]['payment_type']);
										    if(is_array($sp)){
    										    foreach($sp as $pay){
        											$due_payment += $pay["group"] == 'Due' ? $pay["payment_amount"] : 0;
        											$collection_payment += $pay["group"] == 'Collection' ? $pay["payment_amount"] : 0;
        											$cash_payment += $pay["group"] == 'Cash' ? $pay["payment_amount"] : 0;
        										}
										    }
										}
									}
								// 	if($buy_amt < $details['purchaseTotal']) $buy_amt = $details['purchaseTotal'];
                                    $sales_revenue = $sale_amt - $buy_amt;
									echo number_format($sale_amt, 2, '.', ',');
                                    echo "<hr>";
                                    print_r($details['salesDiscount']);
								?></td>
                            </tr>
                            <tr style="border-bottom: 1px solid #CCC;">
                                <td style="padding-left: 10px;">Accounts Receivable as of (<?= $end_date; ?>)</td>
                                <td></td>
                                <td style="text-align: right; padding-right: 10px;">
                                    <?php // echo number_format($due_payment, 2, '.', ','); ?>
                                    <?php // echo number_format($collection_payment, 2, '.', ','); ?>
                                    <?php // echo number_format($cash_payment, 2, '.', ','); ?>
                                    <?php echo number_format($details['accountsReceivable'], 2, '.', ','); ?>
                                </td>
                            </tr>
                            <tr style="border-bottom: 1px solid #CCC;">
                                <td style="padding-left: 10px;">Total Stock Balance</td>
                                <td></td>
                                <td style="text-align: right; padding-right: 10px;"><?php echo number_format($details['stocks'], 2, '.', ','); ?></td>
                            </tr>
                            <?php foreach($details['expenseSum'] as $row){
                                $sales_revenue -= $row['amount'] > 0 ? $row['amount'] : 0;
                            ?>
                                <tr>
                                    <td style="padding-left: 10px;"><?= $row['title']. " Expense"; ?></td>
                                    <td></td>
                                    <td style="text-align: right; padding-right: 10px;">
										<?= $row['amount'] > 0 ? number_format($row['amount'], 2, '.', ',') : ''; ?>
										<?= $row['amount'] < 0 ? "(-)". number_format($row['amount'], 2, '.', ',') : ''; ?>
									</td>
                                </tr>
                            <?php } ?>
                            <tr style="border-bottom: 1px solid #CCC;">
                                <td style="padding-left: 10px;">Purchase</td>
                                <td></td>
                                <td style="text-align: right; padding-right: 10px;"><?php echo number_format($details['purchaseTotal'], 2, '.', ','); ?></td>
                            </tr>
                            <tr style="border-bottom: 1px solid #CCC;">
                                <td style="padding-left: 10px;">Accounts Payable</td>
                                <td></td>
                                <td style="text-align: right; padding-right: 10px;"><?php echo number_format($details['accountsPayable'], 2, '.', ','); ?></td>
                            </tr>
                            <tr style="border-bottom: 1px solid #CCC;">
                                <td style="padding-left: 10px;">Sales Revenues (Sales - Purchase)</td>
                                <td></td>
                                <td style="text-align: right; padding-right: 10px;"><?php echo number_format($sales_revenue, 2, '.', ','); ?></td>
                            </tr>
                        </tbody>
                    </table>
                </div>
            </div>
			<div class="col-xs-12">
				<button class="btn btn-success btn-block btn-flat" onclick="printDiv('report')">Print Report</button><br>
			</div>
        <?php
        } else {
            ?>
            <div class ="alert alert-info">No <?= $description; ?> found!</div>
        <?php } ?>
    </div>