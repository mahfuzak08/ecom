<h1><img src="<?= base_url('assets/imgs/barcode.jpg') ?>" class="header-img" style="margin-top:-3px;"> <?= $description; ?></h1>
<hr>
<div class="row">
    <div class="col-xs-12">
		<form method="POST" action="">
			<!--<div class="row mb-3">
				<label class="col-form-label col-sm-2 pt-0">Page Type</label>
				<div class="col-sm-10">
					<div class="form-check">
						<input class="form-check-input" type="radio" name="page_type" id="page_type1" value="hr" <?= isset($_POST['page_type']) && $_POST['page_type'] == 'hr' ?  'checked' : ''; ?>>
						<label class="form-check-label" for="page_type1"> Horizontal way</label>
					</div>
					<div class="form-check">
						<input class="form-check-input" type="radio" name="page_type" id="page_type2" value="vr" <?= isset($_POST['page_type']) && $_POST['page_type'] == 'vr' ?  'checked' : ''; ?>>
						<label class="form-check-label" for="page_type2"> Vertical way</label>
					</div>
				</div>
			</div>
			<div class="row mb-3">
				<label class="col-form-label col-sm-2 pt-0">Number of Copy</label>
				<div class="col-sm-10">
					<div class="form-check">
						<input class="form-check-input" type="radio" name="nocopy" id="nocopy1" value="6" <?= isset($_POST['nocopy']) && $_POST['nocopy'] == 6 ?  'checked' : ''; ?>>
						<label class="form-check-label" for="nocopy1"> 6 Copy</label>
					</div>
					<div class="form-check">
						<input class="form-check-input" type="radio" name="nocopy" id="nocopy2" value="12" <?= isset($_POST['nocopy']) && $_POST['nocopy'] == 12 ?  'checked' : ''; ?>>
						<label class="form-check-label" for="nocopy2"> 12 Copy</label>
					</div>
				</div>
			</div>-->
			<div class="row mb-3">
				<label for="inputEmail3" class="col-sm-2 col-form-label">Barcode Number</label>
				<div class="col-sm-3">
					<input class="form-control" type="text" name="barcode" value="<?= isset($_POST['barcode']) ? $_POST['barcode'] : '';?>">
				</div>
			</div>
			<div class="row mb-3">
				<label class="col-sm-2"></label>
				<div class="col-sm-3">
					<br>
					<button type="submit" name="print_preview" class="btn btn-primary btn-flat">Print Preview</button>
					<?php if(isset($barcode)) { ?>
					<button type="button" name="print" onclick="printDiv('barcode_print_div')" class="btn btn-success btn-flat">Print</button>
					<br><br>
					<?php } ?>
				</div>
			</div>
		</form>
		<div id="barcode_print_div">
		<?php 
		if(isset($barcode)) {
			//for($i=0; $i<$_POST['nocopy']; $i++){ 
			?>
				<div style="padding: 15px 0px;width: 210px;height: 90px;text-align: center;">
					<div style="padding: 0px 20px;font-size:14px;white-space: nowrap;overflow: hidden;text-overflow: ellipsis;text-align: center;">
						<b><?= $companyName; ?><br>
						Price: <?= @$product["price"]; ?> BDT</b>
					</div>
					<img src="data:image/png;base64,<?= $barcode; ?>" style="max-width:200px;margin-right: auto;display: block;">
					<?= @$product["barcode"]; ?>
				</div>
				<?php 
			//}
		} 
		?>
		</div>
    </div>
</div>