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
                    <?php print_r($details); ?>
                    <!-- <table style="border: 1px solid #CCC; width: 100%; border-collapse: collapse; line-height: 30px;" class="table table-bordered">
                        <thead>
                            <tr style="background-color: #CCC;">
                                <th style="text-align: left; padding-left: 10px;">Account Name</th>
                                <th>Ref</th>
                                <th style="text-align: right; padding-right: 10px;">Amount</th>
                            </tr>
                        </thead>
                        <tbody>
                            
                        </tbody>
                    </table> -->
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