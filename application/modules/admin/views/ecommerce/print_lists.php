<div id="print-order">
    <div style="text-align:center">
        <img src="<?= base_url('attachments/'. SHOP_DIR .'/site_logo/'.$sitelogo) ?>" style="width: 250px;"><br>
        <h2>Orders From <?= date('d.M.Y', $fd) ?> to <?= date('d.M.Y', $td) ?></h2>
    </div>
    <hr>
    
    <?php
    if (!empty($orders)) {
        ?>
        <div style="width: 90%; margin:auto;">
            <table style="width: 100%;border-collapse: collapse;">
                <thead>
                    <tr style="background:#AAA;font-weight:bold;">
                        <td style="padding:10px">Order ID</td>
                        <td style="padding:10px">Date</td>
                        <td style="padding:10px">Customer Name</td>
                        <td style="padding:10px">Restaurant</td>
                        <td style="padding:10px">Status</td>
                        <td style="padding:10px" align=right>Product Total</td>
                        <td style="padding:10px" align=right>Cost</td>
                        <td style="padding:10px" align=right>Order Total</td>
                    </tr>
                </thead>
                <tbody>
                <?php
                $tc = 0; $tsc = 0; $tpc = 0; $tpsc = 0;$i=0;
                foreach ($orders as $tr) {
                    $i++;
                    $arr_products = unserialize($tr['products']);
                    $row_amt = 0;$vendor=array();
                    foreach ($arr_products as $product) {
                        $product_amt = $product['product_info']['price'];
                        $row_amt += $product_amt * $product['product_quantity'];
                        $vendor[] = $product['product_info']['vendor_name'];
                    }
                    
                    $tsc += $tr['shipping_cost'];
                    $tc += $row_amt;
                        
                    if ($tr['processed'] == 0) {
                        $type = 'No processed';
                    }
                    if ($tr['processed'] == 1) {
                        $type = 'Processed';
                        $tpsc += $tr['shipping_cost'];
                        $tpc += $row_amt;
                    }
                    if ($tr['processed'] == 2) {
                        $type = 'Rejected';
                    }
                    if ($tr['processed'] == 3) {
                        $type = 'Confirmed';
                    }
                ?>
                    <tr style="border-bottom:1px solid #000; background:<?= ($i%2)?'#DEDEDE':''; ?>">
                        <td style="padding:10px"><?= $tr['order_id'] ?></td>
                        <td style="padding:10px"><?= date('d.M.Y / H:i:s', $tr['date']); ?></td>
                        <td style="padding:10px"><?= $tr['first_name'] ?></td>
                        <td style="padding:10px"><?php foreach(array_unique($vendor) as $v) echo $v.'<br>'; ?></td>
                        <td style="padding:10px"><?= $type ?></td>
                        <td style="padding:10px;text-align:right;"><?= $row_amt .' '.$this->config->item('currency') ?></td>
                        <td style="padding:10px;text-align:right;"><?= $tr['shipping_cost'] .' '.$this->config->item('currency') ?></td>
                        <td style="padding:10px;text-align:right; font-weight:bold"><?= ($row_amt + $tr['shipping_cost']) .' '.$this->config->item('currency') ?></td>
                    </tr>
                <?php } ?>
                </tbody>
                <tfooter>
                    <tr>
                        <td style="padding:10px" colspan=5 align=right>Total</td>
                        <td style="padding:10px" align=right><?= $tc .' '.$this->config->item('currency') ?></td>
                        <td style="padding:10px" align=right><?= $tsc .' '.$this->config->item('currency') ?></td>
                        <td style="padding:10px" align=right><?= ($tsc + $tc) .' '.$this->config->item('currency') ?></td>
                    </tr>
                    <tr>
                        <td style="padding:10px" colspan=5 align=right>Only Processed Order Total</td>
                        <td style="padding:10px" align=right><?= $tpc .' '.$this->config->item('currency') ?></td>
                        <td style="padding:10px" align=right><?= $tpsc .' '.$this->config->item('currency') ?></td>
                        <td style="padding:10px" align=right><?= ($tpsc + $tpc) .' '.$this->config->item('currency') ?></td>
                    </tr>
                </tfooter>
            </table>
        </div>
    <?php } else { ?>
        <div class="alert alert-info">No orders to the moment!</div>
    <?php }
    ?>
</div>
<script src="<?= base_url('assets/js/jquery.min.js') ?>"></script>
<script src="<?= base_url('assets/js/print.js') ?>"></script>
<script>
    $(function () {
        printDiv('print-order');
        window.close();
    });
</script>