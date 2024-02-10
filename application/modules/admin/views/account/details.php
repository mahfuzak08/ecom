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
    if ($this->session->flashdata('success')) {
        ?>
        <hr>
        <div class="alert alert-success"><?= $this->session->flashdata('success') ?></div>
        <hr>
        <?php
    } 
    ?>
    <div class="row">
        <div class="col-xs-8">
            <h1><button class="btn btn-default" onclick="javascript:location.href='<?= site_url("admin/accounts"); ?>'"><i class="fa fa-arrow-left"></i></button> <?= $description; ?></h1>
        </div>
        <div class="col-xs-2 pull-right">
        <?php if(strpos($access[0]['access'], ACCOUNTS_TRANS_ADD)>-1) { ?>
            <a data-toggle="modal" data-target="#addPage" class="btn btn-default" style="margin-bottom:10px;cursor:pointer;">Add Transection</a>
        <?php } ?>
        </div>
    </div>
    <hr>
    <div class="row">
        <?php if($accounts){ ?>
            <div class="col-xs-12 table-responsive">
                <h4><?= $accounts[0]->name; ?></h4>
                <table class="table">
                    <tr>
                        <th class="col-xs-6 col-md-2">Account Number</td><td class="col-xs-6 col-md-3"><?= $accounts[0]->acc_no; ?></td>
                        <th class="col-xs-6 col-md-2">Bank Name</td><td class="col-xs-6 col-md-5"><?= $accounts[0]->bank_name; ?></td>
                    </tr>
                    <tr>
                        <th class="col-xs-6 col-md-2">Group</td><td class="col-xs-6 col-md-3"><?= $accounts[0]->type; ?></td>
                        <th class="col-xs-6 col-md-2">Bank Address</td><td class="col-xs-6 col-md-5"><?= $accounts[0]->bank_address; ?></td>
                    </tr>
                </table>
            </div>
            <div class="col-xs-12" style="margin-top: 10px;">
                <div class="table-responsive">
                    <table class="table table-bordered">
                        <thead>
                            <tr>
                                <th>SL</th>
                                <th>Date</th>
                                <th>Type</th>
                                <th>Trans No.</th>
                                <th>Details</th>
                                <th class="text-right">Deposit</th>
                                <th class="text-right">Withdrawal</th>
                                <th class="text-right">Balance</th>
                            </tr>
                        </thead>
                        <tbody>
                            <?php
                            $i = 1; $running_balance=0;
                            foreach ($account_trans as $row) {
                                
                                ?>
                                <tr>
                                    <td><?= $i++; ?></td>
                                    <td><?= $row['trans_date']; ?></td>
                                    <td style="text-transform: capitalize;"><?= str_replace('_', ' ', $row['type']); ?></td>
                                    <td>
                                        <?php if(strpos(strtolower($row['type']), 'sale') !== FALSE){?>
                                            <a href="<?= site_url("admin/sale/print_inv/".$row['trans_no']); ?>" target="_blank">#<?= $row['trans_no']; ?></a>
                                        <?php } elseif(strpos(strtolower($row['type']), ('purchase')) !== FALSE){?>
                                            <a href="<?= site_url("admin/purchase/print_inv/".$row['trans_no']); ?>" target="_blank">#<?= $row['trans_no']; ?></a>
                                        <?php } elseif(strpos(strtolower($row['type']), ('customer_payment')) !== FALSE){?>
                                            <a href="<?= site_url("admin/customer/print_receipt/".$row['trans_no']); ?>" target="_blank">#<?= $row['trans_no']; ?></a>
                                        <?php } elseif(strpos(strtolower($row['type']), 'expense') !== FALSE){?>
                                            <a href="<?= site_url("admin/expenses/print_bill/".$row['trans_no']); ?>" target="_blank">#<?= $row['trans_no']; ?></a>
                                        <?php } elseif(strpos($access[0]['access'], ACCOUNTS_TRANS_EDIT)>-1) { ?>
                                            <a href="?edit=<?= $row['id']; ?>" style="color:red" >Edit</a>
                                        <?php } ?>
                                    </td>
                                    <td><?= $row['details']; ?></td>
                                    <td class="text-right"><?= ((float)$row['amount'] > 0) ? number_format($row['amount'], $nf) : 0.00; ?></td>
                                    <td class="text-right"><?= ((float)$row['amount'] < 0) ? number_format($row['amount']*-1, $nf) : 0.00; ?></td>
                                    <td class="text-right"><?php $running_balance+=$row['amount']; echo number_format($running_balance, $nf); ?></td>
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
            <div class ="alert alert-info">No transection found!</div>
        <?php } ?>
    </div>
    <?php if(strpos($access[0]['access'], ACCOUNTS_TRANS_ADD)>-1) { ?>
    <div class="modal fade" id="addPage" tabindex="-1" role="dialog" aria-labelledby="myModalLabel">
        <div class="modal-dialog" role="document">
            <div class="modal-content">
                <form action="<?= site_url("admin/accounts/add_trans"); ?>" method="POST">
                    <div class="modal-header">
                        <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
                        <h4 class="modal-title" id="myModalLabel">Add Transection</h4>
                    </div>
                    <div class="modal-body">
                        <div class="form-group">
                            <label for="name">Account Name</label>
                            <input type="text" value="<?= $accounts[0]->name; ?>" class="form-control" id="name" disabled>
                            <input type="hidden" value="<?= $accounts[0]->id; ?>" class="form-control" name="id" id="id">
                            <input type="hidden" value="<?= @$edit_trans[0]['id']; ?>" class="form-control" name="trnxid" id="trnxid">
                        </div>
                        <div class="form-group">
                            <label for="type">Type of Transection</label>
                            <select name="type" class="form-control" id="type">
                                <option value="Deposit" <?php if(isset($edit_trans) && $edit_trans[0]['amount'] > 0) echo 'selected'; ?>>Deposit</option>
                                <option value="Withdrawal" <?php if(isset($edit_trans) && $edit_trans[0]['amount'] < 0) echo 'selected'; ?>>Withdrawal</option>
                            </select>
                        </div>
                        <div class="form-group">
                            <label for="date">Date</label>
                            <input type="text" name="date" value="<?= date("Y-m-d") ?>" class="form-control datepicker" id="date">
                        </div>
                        <div class="form-group">
                            <label for="amount">Amount</label>
                            <input type="text" name="amount" value="<?= abs(@$edit_trans[0]['amount']); ?>" class="form-control" id="amount">
                        </div>
                        <div class="form-group">
                            <label for="note">Note</label>
                            <?php if(isset($edit_trans)) { ?>
                                <input type="text" disabled=true class="form-control" value="Transection edit. Previous Data was: (Amount: <?= $edit_trans[0]['amount']; ?>, Date: <?= $edit_trans[0]['trans_date']; ?>)">
                                <input type="hidden" name="note" value="Transection edit. Previous Data was: (Amount: <?= $edit_trans[0]['amount']; ?>, Date: <?= $edit_trans[0]['trans_date']; ?>)">
                            <?php } else { ?>
                                <input type="text" name="note" class="form-control" id="note">
                            <?php } ?>
                        </div>
                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-default" data-dismiss="modal">Cancel</button>
                        <button type="submit" name="add_trans" class="btn btn-primary"><?= isset($edit_trans) ? 'Update' : 'Add'; ?></button>
                    </div>
                </form>
            </div>
        </div>
    </div>
    <?php } ?>
    <script src="<?= base_url('assets/js/bootstrap-datepicker.min.js') ?>"></script>
    <script>
        $(document).ready( function () {
            $('.table.table-bordered').DataTable();
            $('.datepicker').datepicker({ format: "yyyy-mm-dd" }).datepicker("setDate", new Date());
            <?php if(isset($edit_trans)){ ?>
                $('#addPage').modal('show');
            <?php } ?>
        } );
    </script>