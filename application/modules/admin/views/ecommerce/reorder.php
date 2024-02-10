<link href="<?= base_url('assets/css/bootstrap-datepicker.min.css') ?>" rel="stylesheet">
<style>
    .size_row{
        width:100%;
        float: left;
    }
    .col_cus_100px{
        width: 60px;
        float: left;
    }
</style>
<h1><img src="<?= base_url('assets/imgs/shop-cart-add-icon.png') ?>" class="header-img" style="margin-top:-3px;"> <?= $description; ?></h1>
<hr>
<?php
$timeNow = time();
if (validation_errors()) {
    ?>
    <hr>
    <div class="alert alert-danger"><?= validation_errors() ?></div>
    <hr>
    <?php
}
if ($this->session->flashdata('result_publish')) {
    ?>
    <hr>
    <div class="alert alert-success"><?= $this->session->flashdata('result_publish') ?></div>
    <hr>
    <?php
}
if(isset($_GET['id'])){
    $form = '<form method="POST" action="">';
    $label = "Title";
    $input_name = "search_title";
}else{
    $form = '<form method="GET" action="'.base_url("admin/products").'"><input type="hidden" name="category">';
    $label = "Search Item";
    $input_name = "title";
}
?>
<?= $form; ?>
    <div class="form-group for-shop col-md-6">
        <label><?= $label; ?></label>
        <input type="text" name="search_title" <?= isset($_GET['id']) ? "disabled" : ""; ?> value="<?= $trans_load != null && isset($trans_load['en']['title']) ? $trans_load['en']['title'] : '' ?>" class="form-control">
    <?php if(isset($_GET['id'])){ ?>
            <?php
            if (isset($_POST['image']) && $_POST['image'] != null) {
                $image = 'attachments/'. SHOP_DIR .'/shop_images/' . $_POST['image'];
                if (!file_exists($image)) {
                    $image = 'attachments/no-image.png';
                }
                ?>
                <p>Current image:</p>
                <div>
                    <img src="<?= base_url($image) ?>" class="img-responsive img-thumbnail" style="max-width:250px; margin-bottom: 5px;">
                </div>
                <input type="hidden" name="old_image" value="<?= $_POST['image'] ?>">
                <?php if (isset($_GET['to_lang'])) { ?>
                    <input type="hidden" name="image" value="<?= $_POST['image'] ?>">
                    <?php
                }
            }
            ?>
        </div>
        <?php //print_r($lp); ?>
        <!--
        <div class="form-group for-shop col-md-6">
            <label>Supplier info</label>
            <input type="text" name="supplierName" disabled value="<?php // $supplierName->name; ?>" placeholder="Supplier info" class="form-control">
        </div>
        -->
        <div class="form-group for-shop col-md-3">
            <label>Date</label>
            <input type="text" name="date" value="<?= date("Y-m-d"); ?>" placeholder="YYYY-MM-DD" class="form-control datepicker">
        </div>
        <div class="form-group for-shop col-md-3">
            <label>Price</label>
            <input type="hidden" name="old_price" value="<?= $trans_load != null && isset($trans_load['en']['price']) ? $trans_load['en']['price'] : '' ?>">
            <input type="text" name="price" placeholder="without currency at the end" value="<?= $trans_load != null && isset($trans_load['en']['price']) ? $trans_load['en']['price'] : '' ?>" class="form-control">
        </div>
        <div class="form-group for-shop col-md-3">
            <label>Purchase Price</label>
            <input type="text" name="buy_price" placeholder="without currency at the end" value="<?= $trans_load != null && isset($trans_load['en']['buy_price']) ? $trans_load['en']['buy_price'] : '' ?>" class="form-control">
        </div>
        <?php if($multiSize == 1) { ?>
            <div class="form-group for-shop col-md-3">
                <label>Size</label>
                <div class="sizes">
                    <?php if(isset($_POST['size']) && $_POST['size'] != 'N' && $_POST['size'] != '') {
                        $sizes = explode(";", $_POST['size']);
                        for($n=0; $n<count($sizes); $n++){ 
                            $sq = explode("x", $sizes[$n]); ?>    
                            <div class="size_row">
                                <input type="text" placeholder="Size" name="size[]" value="<?= $sq[0]; ?>" class="form-control col_cus_100px">
                                <input type="text" placeholder="Quantity" name="qty[]" value="" class="form-control col_cus_100px">&nbsp;
                                <input type="hidden" name="oldqty[]" value="<?= $sq[1]; ?>" class="form-control col_cus_100px">&nbsp;
                                <?php if($n==0) { ?> 
                                    <div class="btn btn-sm btn-default" onclick="addmoresize()">More</div>
                                <?php } ?>
                            </div>
                        <?php } 
                    } else { ?>
                    <div class="size_row">
                        <input type="text" placeholder="Size" name="size[]" value="" class="form-control col_cus_100px">
                        <input type="text" placeholder="Quantity" name="qty[]" value="" class="form-control col_cus_100px">&nbsp;
                        <input type="hidden" name="oldqty[]" value="" class="form-control col_cus_100px">&nbsp;
                        <div class="btn btn-sm btn-default" onclick="addmoresize()">More</div>
                    </div>
                    <?php } ?>
                </div>
            </div>    
        <?php } ?>
        <div class="form-group for-shop col-md-3">
            <label>Quantity</label>
            <?= form_hidden("old_quantity", @$_POST['quantity']); ?>
            <input type="number" min="1" placeholder="Quantity" name="quantity" value="" class="form-control" id="quantity">
        </div>
        <div class="col-md-12">
            <input type="hidden" value="<?= $_POST['id']; ?>" name="id">
            <button type="submit" name="reorder_submit" class="btn btn-lg btn-success">Add in Stock</button>
            <a href="<?= base_url('admin/products/reorder') ?>" class="btn btn-lg btn-default">Cancel</a>
        </div>
    <?php } else { ?>
        </div>
        <div class="col-md-12">
            <button type="submit" name="submit" class="btn btn-lg btn-default">Search Item</button>
        </div>
    <?php } ?>
</form>
<script src="<?= base_url('assets/js/bootstrap-datepicker.min.js') ?>"></script>
<script>
    function addmoresize(){
        var html = '<div class="size_row">'+
                    '<input type="text" placeholder="Size" name="size[]" value="" class="form-control col_cus_100px">'+
                    '<input type="text" placeholder="Quantity" name="qty[]" value="" class="form-control col_cus_100px">&nbsp;'+
                    '<input type="hidden" name="oldqty[]" value="" class="form-control col_cus_100px">&nbsp;'+
                    '<div class="btn btn-sm btn-warning" onclick="removesize(event)">Remove</div>'+
                '</div>';
        $(".sizes").append(html);
    }
    function removesize(e){
        $(e.target).closest(".size_row").remove();
    }
    $('.datepicker').datepicker({ 
        format: "yyyy-mm-dd", 
        todayHighlight: true,
        autoclose: true
    });
</script>