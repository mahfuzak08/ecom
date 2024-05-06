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
							<td style="vertical-align: top;padding:5px 10px;text-align:center; font-size: 20px;">Sales Revenue Report<br> <?= $report_info; ?></td>
						</tr>
					</table>
                    <?php // print_r($details); ?>
                    <table style="border: 1px solid #CCC; width: 100%; border-collapse: collapse; line-height: 30px;" class="table table-bordered">
                        <thead>
                            <tr style="background-color: #CCC;">
                                <th style="text-align: left; padding-left: 10px;">Products/ Categories Name</th>
                                <th>Quantity</th>
                                <th style="text-align: right; padding-right: 10px;">Purchase (BDT)</th>
                                <th style="text-align: right; padding-right: 10px;">Sales (BDT)</th>
                                <th style="text-align: right; padding-right: 10px;">Profit or Loss (BDT)</th>
                            </tr>
                        </thead>
                        <tbody>
                            <?php if($category_id>0){
                                foreach($getAllCategory as $row){
                                    if($row['id'] == $category_id) { ?>
                                        <tr>
                                            <td><?= $row['name']; ?></td>
                                            <td style="text-align: right; padding-right: 10px;">
                                                <?php 
                                                $sale_amt = 0;
                                                $buy_amt = 0;
                                                $qty = 0;
                                                for($i=0; $i<count($details['sales_result']); $i++){
                                                    // $sale_amt += $details['sales_result'][$i]['total'];
                                                    foreach(unserialize($details['sales_result'][$i]['products']) as $line=>$item) {
                                                        if($item['product_info']['shop_categorie'] == $row['id']){
                                                            $qty+= $item['product_info']['quantity'];
                                                            $sale_amt += (float) $item['product_info']['price'] * (float) $item['product_info']['quantity'];
                                                            if((float) $item['product_info']['cost_price'] > 0)
                                                                $buy_amt += (float) $item['product_info']['cost_price'] * (float) $item['product_info']['quantity'];
                                                            else{
                                                                for($ii = 0; $ii<count($details['p_buy_prices']); $ii++){
                                                                    if($details['p_buy_prices'][$ii]['for_id'] == $item['product_info']['id']){
                                                                        $buy_amt += (float) $details['p_buy_prices'][$ii]['buy_price'] * (float) $item['product_info']['quantity'];
                                                                        break;
                                                                    }
                                                                }
                                                            }
                                                        }
                                                    }
                                                }
                                                echo $qty;
                                                ?>
                                            </td>
                                            <td style="text-align: right; padding-right: 10px;"><?= $buy_amt; ?></td>
                                            <td style="text-align: right; padding-right: 10px;"><?= $sale_amt; ?></td>
                                            <td style="text-align: right; padding-right: 10px;"><?= $sale_amt - $buy_amt; ?></td>
                                        </tr>
                                    <?php }
                                }
                            } elseif($product_id>0) { 
                                $sale_amt = 0;
                                $buy_amt = 0;
                                $qty = 0;
                                $pname = '';
                                for($i=0; $i<count($details['sales_result']); $i++){
                                    foreach(unserialize($details['sales_result'][$i]['products']) as $line=>$item) {
                                        if($item['product_info']['id'] == $product_id){
                                            $qty+= $item['product_info']['quantity'];
                                            $sale_amt += (float) $item['product_info']['price'] * (float) $item['product_info']['quantity'];
                                            if((float) $item['product_info']['cost_price'] > 0)
                                                $buy_amt += (float) $item['product_info']['cost_price'] * (float) $item['product_info']['quantity'];
                                            else{
                                                for($ii = 0; $ii<count($details['p_buy_prices']); $ii++){
                                                    if($details['p_buy_prices'][$ii]['for_id'] == $item['product_info']['id']){
                                                        $buy_amt += (float) $details['p_buy_prices'][$ii]['buy_price'] * (float) $item['product_info']['quantity'];
                                                        break;
                                                    }
                                                }
                                            }
                                            for($ii = 0; $ii<count($details['p_buy_prices']); $ii++){
                                                if($details['p_buy_prices'][$ii]['for_id'] == $item['product_info']['id']){
                                                    $pname = $details['p_buy_prices'][$ii]['title'];
                                                    break;
                                                }
                                            }
                                        }
                                    }
                                } ?>
                                <tr>
                                    <td><?= $pname; ?></td>
                                    <td><?= $qty; ?></td>
                                    <td><?= $buy_amt; ?></td>
                                    <td><?= $sale_amt; ?></td>
                                    <td><?= $sale_amt - $buy_amt; ?></td>
                                </tr> <?php 
                            } else {
                                foreach($getAllCategory as $row){
                                    if(array_search($row['id'], $details['catids']) !== false) { ?>
                                        <tr>
                                            <td><?= $row['name']; ?></td>
                                            <td style="text-align: right; padding-right: 10px;">
                                                <?php 
                                                $sale_amt = 0;
                                                $buy_amt = 0;
                                                $qty = 0;
                                                for($i=0; $i<count($details['sales_result']); $i++){
                                                    // $sale_amt += $details['sales_result'][$i]['total'];
                                                    foreach(unserialize($details['sales_result'][$i]['products']) as $line=>$item) {
                                                        if($item['product_info']['shop_categorie'] == $row['id']){
                                                            $qty+= $item['product_info']['quantity'];
                                                            $sale_amt += (float) $item['product_info']['price'] * (float) $item['product_info']['quantity'];
                                                            if((float) $item['product_info']['cost_price'] > 0)
                                                                $buy_amt += (float) $item['product_info']['cost_price'] * (float) $item['product_info']['quantity'];
                                                            else{
                                                                for($ii = 0; $ii<count($details['p_buy_prices']); $ii++){
                                                                    if($details['p_buy_prices'][$ii]['for_id'] == $item['product_info']['id']){
                                                                        $buy_amt += (float) $details['p_buy_prices'][$ii]['buy_price'] * (float) $item['product_info']['quantity'];
                                                                        break;
                                                                    }
                                                                }
                                                            }
                                                        }
                                                    }
                                                }
                                                echo $qty;
                                                ?>
                                            </td>
                                            <td style="text-align: right; padding-right: 10px;"><?= $buy_amt; ?></td>
                                            <td style="text-align: right; padding-right: 10px;"><?= $sale_amt; ?></td>
                                            <td style="text-align: right; padding-right: 10px;"><?= $sale_amt - $buy_amt; ?></td>
                                        </tr>
                                    <?php }
                                }
                            }  ?>
                            
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