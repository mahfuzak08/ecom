<style>
    .verify{
        margin-left: 10px;
        font-size: 20px;
        color: green;
    }
    .fa-recycle.verify{
        color: red;
        cursor: pointer;
    }
</style>
<link href="<?= base_url('assets/css/bootstrap-datepicker.min.css') ?>" rel="stylesheet">
<div id="products">
    <?php
    if ($this->session->flashdata('error')) {
        ?>
        <hr>
        <div class="alert alert-danger"><?= $this->session->flashdata('error') ?></div>
        <hr>
        <?php
    }
    ?>
    <h1><button class="btn btn-default" onclick="javascript:location.href='<?= site_url("admin/customer"); ?>'"><i class="fa fa-arrow-left"></i></button> <?= $description; ?></h1>
    <hr>
    <div class="row">
        <?php if($customers){ ?>
            <div class="col-xs-12 col-md-6">
                <h4>Name: <?= $customers[0]->name; ?></h4>
                <h4>
                    Phone: <?= $customers[0]->phone; ?><br>
                    Email: <?= $customers[0]->email; ?><br>
                    Address: <?= $customers[0]->email; ?><br>
                    <b><?= $customers[0]->balance >= 0 ? 'Total Due: ' : 'Advance Amount: '; ?><?= $customers[0]->balance .' '. CURRENCY; ?></b>
                </h4>
            </div>
            <div class="col-xs-12 col-md-6">
            <?php if($customers[0]->balance > 0){?>
                <?= form_open('admin/customer/add_payment' , array('id'=>'add_payment')); ?>
                    <?= form_hidden("id", $customers[0]->id); ?>
                    <table width="100%">
                        <tr>
                            <td><h4>Payment Date</h4></td>
                            <td><input type="text" name="payment_date" class="form-control datepicker"></td>
                        </tr>
                        <tr>
                            <td><h4>Payment Type</h4></td>
                            <td>
                                <select name="payment_type" class="form-control payment_type">
                                    <?php foreach($payment_type as $pt) { if($pt->type == "Due") continue; ?>
                                    <option value="<?= $pt->id; ?>"><?= $pt->name; ?></option>
                                    <?php } ?>
                                </select>
                            </td>
                        </tr>
                        <tr>
                            <td><h4><?= $customers[0]->balance > 0 ? 'Collection Receive' : 'Advance Receive'; ?></h4></td>
                            <td><input type="number" name="amount_tendered" class="form-control"></td>
                        </tr>
                        <tr>
                            <td><h4>Details</h4></td>
                            <td><input type="text" name="details" value="Collect old due" class="form-control"></td>
                        </tr>
                        <tr>
                            <td></td>
                            <td><button type="submit" name="addp" class="btn btn-sm btn-flat btn-block btn-success">Add Payment</button></td>
                        </tr>
                    </table>
                <?= form_close(); ?>
            <?php } ?>
            </div>
            <div class="col-xs-12" style="margin-top: 10px;">
                <div class="table-responsive">
                    <table class="table table-bordered">
                        <thead>
                            <tr>
                                <th>SL</th>
                                <th>Date</th>
                                <th>Order No</th>
                                <th>Type</th>
                                <th>Order Amount</th>
                                <th>Payment Amount</th>
                                <th>Due Amount</th>
                                <th>Balance</th>
                            </tr>
                        </thead>
                        <tbody>
                            <?php
                            $i = 1; $running_balance = 0;
                            foreach ($trans as $row) {
                                // if($row['trans_no'] != NULL && $row['total'] != NULL && $row['note'] == 'Due Collection') continue;
                                ?>
                                <tr>
                                    <td><?= $i++; ?></td>
                                    <td><?= $row['created_at']; ?></td>
                                    <td>
                                        <?php if($row['trans_no'] != NULL){ ?>
                                            <a href="<?= site_url("admin/sale/print_inv/".$row['trans_no']); ?>" target="_blank"><?= $row['order_id']; ?></a>
                                        <?php } ?>
                                        <?php if($row['trans_no'] === NULL){ ?>
                                            <a href="<?= site_url("admin/customer/print_receipt/".$row['id']); ?>" target="_blank"><?= $row['id']; ?></a>
                                        <?php } ?>
                                    </td>
                                    <td style="text-transform: capitalize;">
                                        <?= $row['order_type']!= NULL ? $row['order_type'].' '.$row['note'] : 'Direct '.$row['note']; ?>
                                    </td>
                                    <td><?= ($row['total'] != NULL && $row['note'] != 'Due Collection') ? $row['total'] : 0; ?></td>
                                    <td><?php 
                                        $row['pay_amt'] = floatval($row['amt']);
                                        $row['due_amt'] = 0;
                                        if($row['order_type'] != NULL && $row['note'] != 'Due Collection'){
                                            $paylist = @unserialize($row['payment_type']); 
                                            if($paylist){
                                                foreach ($paylist as $value) {
                                                    if($value['group'] == 'Due'){
                                                        $row['due_amt'] += floatval($value['payment_amount']);
                                                    }
                                                }
                                            }else{
                                                $row['pay_amt'] = floatval($row['total']);
                                                $row['due_amt'] = 0;
                                            }
                                        }
                                        $row['pay_amt'] -= $row['due_amt'];
                                        echo floatval($row['pay_amt']);
                                        ?>
                                    </td>
                                    <td><?= floatval($row['due_amt']); ?></td>
                                    <td>
                                        <?php 
                                            if($row['asof_date_due']==NULL){
                                                $running_balance -= floatval($row['amt']);
                                            }else{
                                                $running_balance = floatval($row['asof_date_due']); 
                                            }
                                            echo $running_balance;
                                        ?>
                                    </td>
                                </tr>
                                <?php
                            }
                            ?>
                        </tbody>
                    </table>
                </div>
            </div>
        <?php
        } else {
            ?>
            <div class ="alert alert-info">No customer found!</div>
        <?php } ?>
    </div>
    <script src="<?= base_url('assets/js/bootstrap-datepicker.min.js') ?>"></script>
    <script>
        $(document).ready( function () {
            $('.table').DataTable();
            $('.datepicker').datepicker({ format: "yyyy-mm-dd" }).datepicker("setDate", new Date());
        } );
    </script>