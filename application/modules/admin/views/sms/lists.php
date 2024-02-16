<div id="products">
    <?php
    if ($this->session->flashdata('result_delete')) {
        ?>
        <hr>
        <div class="alert alert-success"><?= $this->session->flashdata('result_delete') ?></div>
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
    if ($this->session->flashdata('error')) {
        ?>
        <hr>
        <div class="alert alert-warning"><?= $this->session->flashdata('error') ?></div>
        <hr>
        <?php
    } 
    ?>
    <div class="row">
        <div class="col-xs-8">
        <h1><img src="<?= base_url('assets/imgs/products-img.png') ?>" class="header-img" style="margin-top:-2px;"> <?= $description; ?></h1>
        </div>
        <div class="col-xs-2 pull-right">
            <a href="javascript:void(0);" onclick="set_data('','')" data-toggle="modal" data-target="#addPage" class="btn btn-default" style="margin-bottom:10px;">New Message</a>
        </div>
    </div>
    <hr>
    <div class="row">
        <div class="col-xs-12">
            <p>Current Balance is: <b><?= $bal ?></b></p>
            <table class="table">
                <thead>
                    <tr>
                        <th>SL</th>
                        <th>Number</th>
                        <th>SMS</th>
                        <th>Status</th>
                        <th>Action</th>
                    </tr>
                </thead>
                <tbody>
                    <?php
                    $i=1; 
                    foreach($smss as $row) { ?>
                        <tr>
                            <th><?= $i++ ?></th>
                            <th><?= $row->contacts ?></th>
                            <th><?= $row->msg ?></th>
                            <th><?php 
                            if(strpos($row->response, 'SMS SUBMITTED') !== false)
                                echo 'Send';
                            elseif($row->response == 1007)
                                echo 'Balance Insufficient';
                            else 
                                echo $row->response;
                            ?>
                            </th>
                            <th><?php if(strpos($row->response, 'SMS SUBMITTED') === false) echo "Resend"; ?></th>
                        </tr>
                    <?php } ?>
                </tbody>
            </table>
            <?php $p = isset($_GET['page']) ? $_GET['page'] : 1;
             if($total_sms > 10){
                if($p-1>0){ ?>
                    <a href="?page=<?= $p-1 ?>">Previous</a>
                <?php } ?>
                <button><?= $p ?></button>
                <?php if($p+1<=ceil($total_sms/10)) { ?>
                    <a href="?page=<?= $p+1 ?>">Next</a>
                <?php } ?>
            <?php } ?>
        </div>
    </div>

    <div class="modal fade" id="addPage" tabindex="-1" role="dialog" aria-labelledby="myModalLabel">
        <div class="modal-dialog" role="document">
            <div class="modal-content">
                <form action="" method="POST">
                    <div class="modal-header">
                        <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
                        <h4 class="modal-title" id="myModalLabel">Send new message</h4>
                    </div>
                    <div class="modal-body">
                        <div class="form-group">
                            <label for="name">Number</label>
                            <input type="text" name="number" class="form-control" id="number">
                        </div>
                        <div class="form-group">
                            <label for="name">Message</label>
                            <input type="text" name="msg" class="form-control" id="msg">
                        </div>
                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-default" data-dismiss="modal">Cancel</button>
                        <button type="submit" name="sendsms" class="btn btn-primary">Add</button>
                    </div>
                </form>
            </div>
        </div>
    </div>
    <script>
        // $(document).ready( function () {
        //     $('.table').DataTable();
        // } );
        function set_data(n, m){
            $("#addPage #number").val(n);
            $("#addPage #msg").val(m);
        }
    </script>