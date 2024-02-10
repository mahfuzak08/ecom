<?php if(strpos($access[0]['access'], ADMIN_USERS)>-1) { ?>
<div id="users">
    <h1><img src="<?= base_url('assets/imgs/admin-user.png') ?>" class="header-img" style="margin-top:-3px;"> Admin Users</h1> 
    <hr>
    <?php if (validation_errors()) { ?>
        <hr>
        <div class="alert alert-danger"><?= validation_errors() ?></div>
        <hr>
        <?php
    }
    if ($this->session->flashdata('result_add')) {
        ?>
        <hr>
        <div class="alert alert-success"><?= $this->session->flashdata('result_add') ?></div>
        <hr>
        <?php
    }
    if ($this->session->flashdata('result_delete')) {
        ?>
        <hr>
        <div class="alert alert-success"><?= $this->session->flashdata('result_delete') ?></div>
        <hr>
        <?php
    }
    ?>
    <?php if(strpos($access[0]['access'], ADMIN_USERS)>-1) { ?>
    <a href="javascript:void(0);" data-toggle="modal" data-target="#add_edit_users" class="btn btn-primary btn-xs pull-right" style="margin-bottom:10px;"><b>+</b> Add new user</a>
    <div class="clearfix"></div>
    <?php
    if ($users->result()) {
        ?>
        <div class="table-responsive">
            <table class="table table-striped custab">
                <thead>
                    <tr>
                        <th>#ID</th>
                        <th>Username</th>
                        <th>Password</th>
                        <th>Email</th>
                        <th>Notifications</th>
                        <th>Last login</th>
                        <th>Login failed</th>
                        <th class="text-center">Action</th>
                    </tr>
                </thead>
                <?php foreach ($users->result() as $user) { ?>
                    <tr>
                        <td><?= $user->id ?></td>
                        <td><?= $user->username ?></td>
                        <td><b>hidden ;)</b></td>
                        <td><?= $user->email ?></td>
                        <td><?= $user->notify ?></td>
                        <td><?= date('d.m.Y - H:i:s', $user->last_login) ?></td>
                        <td><?= $user->login_attempts_failed; ?></td>
                        <td class="text-center">
                            <div>
                                <a href="?delete=<?= $user->id ?>" class="confirm-delete">Delete</a> | 
                                <a href="?edit=<?= $user->id ?>">Edit</a> | 
                                <a href="?reactive=<?= $user->id ?>">Re-Active</a>
                            </div>
                        </td>
                    </tr>
                <?php } ?>
            </table>
        </div>
    <?php } else { ?>
        <div class="clearfix"></div><hr>
        <div class="alert alert-info">No users found!</div>
    <?php } ?>

    <!-- add edit users -->
    <div class="modal fade" id="add_edit_users" tabindex="-1" role="dialog" aria-labelledby="myModalLabel">
        <div class="modal-dialog" role="document">
            <div class="modal-content">
                <form action="" method="POST" enctype="multipart/form-data">
                    <div class="modal-header">
                        <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
                        <h4 class="modal-title" id="myModalLabel">Add Administrator</h4>
                    </div>
                    <div class="modal-body">
                        <input type="hidden" name="edit" value="<?= isset($_GET['edit']) ? $_GET['edit'] : '0' ?>">
                        <div class="form-group">
                            <label for="username">Username</label>
                            <input type="text" name="username" value="<?= isset($_POST['username']) ? $_POST['username'] : '' ?>" class="form-control" id="username">
                        </div>
                        <div class="form-group">
                            <label for="password">Password</label>
                            <input type="password" name="password" class="form-control" value="" id="password">
                        </div>
                        <div class="form-group">
                            <label for="email">Email</label>
                            <input type="text" name="email" class="form-control" value="<?= isset($_POST['email']) ? $_POST['email'] : '' ?>" id="email">
                        </div>
                        <div class="form-group">
                            <label for="notify">Notifications</label>
                            <input type="text" name="notify" class="form-control" value="<?= isset($_POST['notify']) ? $_POST['notify'] : '' ?>" placeholder="Get notifications by email: 1 / 0 (yes or no)" id="notify">
                        </div>
                        <div class="form-group">
                            <?php @$access = explode(",", $_POST["access"]); ?>
                            <label for="access">Access</label>
                            <table class="table access">
                                <tr>
                                    <td>
                                        <input type="checkbox" name="access[]" value="100" <?= (array_search("100", $access) === false)? '': 'checked'; ?>>INVENTORY<br>
                                        <input type="checkbox" name="access[]" value="101" <?= (array_search("101", $access) === false)? '': 'checked'; ?>>Publish product<br>
                                        <input type="checkbox" name="access[]" value="102" <?= (array_search("102", $access) === false)? '': 'checked'; ?>>Products<br>
                                        <input type="checkbox" name="access[]" value="103" <?= (array_search("103", $access) === false)? '': 'checked'; ?>>Product Edit<br>
                                        <input type="checkbox" name="access[]" value="104" <?= (array_search("104", $access) === false)? '': 'checked'; ?>>Product Delete<br>
                                        <input type="checkbox" name="access[]" value="105" <?= (array_search("105", $access) === false)? '': 'checked'; ?>>Product Active Inactive<br>
                                        <input type="checkbox" name="access[]" value="110" <?= (array_search("110", $access) === false)? '': 'checked'; ?>>Categories<br>
                                        <input type="checkbox" name="access[]" value="111" <?= (array_search("111", $access) === false)? '': 'checked'; ?>>Categories Add<br>
                                        <input type="checkbox" name="access[]" value="112" <?= (array_search("112", $access) === false)? '': 'checked'; ?>>Categories Delete<br><hr>

                                        <input type="checkbox" name="access[]" value="150" <?= (array_search("150", $access) === false)? '': 'checked'; ?>>ECOMMERCE<br>
                                        <input type="checkbox" name="access[]" value="151" <?= (array_search("151", $access) === false)? '': 'checked'; ?>>Delivery Location<br>
                                        <input type="checkbox" name="access[]" value="152" <?= (array_search("152", $access) === false)? '': 'checked'; ?>>Delivery Location Add<br>
                                        <input type="checkbox" name="access[]" value="153" <?= (array_search("153", $access) === false)? '': 'checked'; ?>>Delivery Location Edit<br>
                                        <input type="checkbox" name="access[]" value="154" <?= (array_search("154", $access) === false)? '': 'checked'; ?>>Delivery Location Delete<br>
                                        <input type="checkbox" name="access[]" value="155" <?= (array_search("155", $access) === false)? '': 'checked'; ?>>Wish List<br>
                                        <input type="checkbox" name="access[]" value="160" <?= (array_search("160", $access) === false)? '': 'checked'; ?>>Orders<br>
                                        <input type="checkbox" name="access[]" value="161" <?= (array_search("161", $access) === false)? '': 'checked'; ?>>Get Order<br>
                                        <input type="checkbox" name="access[]" value="162" <?= (array_search("162", $access) === false)? '': 'checked'; ?>>Confirm Order<br>
                                    </td>
                                    <td>
                                        <input type="checkbox" name="access[]" value="163" <?= (array_search("163", $access) === false)? '': 'checked'; ?>>Reject Order<br>
                                        <input type="checkbox" name="access[]" value="164" <?= (array_search("164", $access) === false)? '': 'checked'; ?>>Process Order<br>
                                        <input type="checkbox" name="access[]" value="165" <?= (array_search("165", $access) === false)? '': 'checked'; ?>>Orders Settings<br>
                                        <input type="checkbox" name="access[]" value="190" <?= (array_search("190", $access) === false)? '': 'checked'; ?>>Discount Codes<br><hr>

                                        <input type="checkbox" name="access[]" value="200" <?= (array_search("200", $access) === false)? '': 'checked'; ?>>Sales<br>
                                        <input type="checkbox" name="access[]" value="201" <?= (array_search("201", $access) === false)? '': 'checked'; ?>>Sales Update<br>
                                        <input type="checkbox" name="access[]" value="214" <?= (array_search("214", $access) === false)? '': 'checked'; ?>>Sales Invoice Setup<br>
                                        <input type="checkbox" name="access[]" value="215" <?= (array_search("215", $access) === false)? '': 'checked'; ?>>Customer<br>
                                        <input type="checkbox" name="access[]" value="216" <?= (array_search("216", $access) === false)? '': 'checked'; ?>>Customer Edit<br>
                                        <input type="checkbox" name="access[]" value="217" <?= (array_search("217", $access) === false)? '': 'checked'; ?>>Customer Delete<br>
                                        <input type="checkbox" name="access[]" value="220" <?= (array_search("220", $access) === false)? '': 'checked'; ?>>Customer Tran. Delete<br><hr>

                                        <input type="checkbox" name="access[]" value="230" <?= (array_search("230", $access) === false)? '': 'checked'; ?>>Purchase<br>
                                        <input type="checkbox" name="access[]" value="240" <?= (array_search("240", $access) === false)? '': 'checked'; ?>>New Vendor<br>
                                        <input type="checkbox" name="access[]" value="241" <?= (array_search("241", $access) === false)? '': 'checked'; ?>>Vendor Manage<br><hr>
                                        
                                        <input type="checkbox" name="access[]" value="250" <?= (array_search("250", $access) === false)? '': 'checked'; ?>>Reports<br><hr>
                                        
                                        <input type="checkbox" name="access[]" value="270" <?= (array_search("270", $access) === false)? '': 'checked'; ?>>OFFICE<br>
                                        <input type="checkbox" name="access[]" value="271" <?= (array_search("271", $access) === false)? '': 'checked'; ?>>Accounts<br>
                                        <input type="checkbox" name="access[]" value="272" <?= (array_search("272", $access) === false)? '': 'checked'; ?>>Account Delete<br>
                                        <input type="checkbox" name="access[]" value="273" <?= (array_search("273", $access) === false)? '': 'checked'; ?>>Account Transection Add<br>
                                        <input type="checkbox" name="access[]" value="274" <?= (array_search("274", $access) === false)? '': 'checked'; ?>>Account Transection Edit<br>
                                        <input type="checkbox" name="access[]" value="275" <?= (array_search("275", $access) === false)? '': 'checked'; ?>>Account Transection Delete<br>
                                        <input type="checkbox" name="access[]" value="280" <?= (array_search("280", $access) === false)? '': 'checked'; ?>>Expenses<br>
                                    </td>
                                    <td>
                                        <input type="checkbox" name="access[]" value="281" <?= (array_search("281", $access) === false)? '': 'checked'; ?>>Expenses Add<br>
                                        <input type="checkbox" name="access[]" value="282" <?= (array_search("282", $access) === false)? '': 'checked'; ?>>Expenses Edit<br>
                                        <input type="checkbox" name="access[]" value="283" <?= (array_search("283", $access) === false)? '': 'checked'; ?>>Expenses Delete<br>
                                        <input type="checkbox" name="access[]" value="284" <?= (array_search("284", $access) === false)? '': 'checked'; ?>>Expense Transection Add<br>
                                        <input type="checkbox" name="access[]" value="285" <?= (array_search("285", $access) === false)? '': 'checked'; ?>>Expense Transection Edit<br>
                                        <input type="checkbox" name="access[]" value="286" <?= (array_search("286", $access) === false)? '': 'checked'; ?>>Expense Transection Delete<br>
                                        <input type="checkbox" name="access[]" value="290" <?= (array_search("290", $access) === false)? '': 'checked'; ?>>Subscribed Emails<br>
                                        <input type="checkbox" name="access[]" value="295" <?= (array_search("295", $access) === false)? '': 'checked'; ?>>Templates<br>
                                        <input type="checkbox" name="access[]" value="300" <?= (array_search("300", $access) === false)? '': 'checked'; ?>>Users<br>
                                        <input type="checkbox" name="access[]" value="310" <?= (array_search("310", $access) === false)? '': 'checked'; ?>>Activity History<br>
                                        <input type="checkbox" name="access[]" value="311" <?= (array_search("311", $access) === false)? '': 'checked'; ?>>Visitor History<br><hr>
                                        
                                        <input type="checkbox" name="access[]" value="320" <?= (array_search("320", $access) === false)? '': 'checked'; ?>>Settings<br>
                                        <input type="checkbox" name="access[]" value="340" <?= (array_search("340", $access) === false)? '': 'checked'; ?>>DBA<br>
                                        <input type="checkbox" name="access[]" value="350" <?= (array_search("350", $access) === false)? '': 'checked'; ?>>Styling<br>
                                        <input type="checkbox" name="access[]" value="351" <?= (array_search("351", $access) === false)? '': 'checked'; ?>>Language<br>
                                        <input type="checkbox" name="access[]" value="352" <?= (array_search("352", $access) === false)? '': 'checked'; ?>>Titles/ Descriptions<br>
                                        <input type="checkbox" name="access[]" value="353" <?= (array_search("353", $access) === false)? '': 'checked'; ?>>Active Pages<br>
                                        <input type="checkbox" name="access[]" value="354" <?= (array_search("354", $access) === false)? '': 'checked'; ?>>File Manager<br>
                                        <input type="checkbox" name="access[]" value="360" <?= (array_search("360", $access) === false)? '': 'checked'; ?>>Clients<br>
                                    </td>
                                </tr>
                            </table>
                        </div>
                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-default" data-dismiss="modal">Cancel</button>
                        <input type="submit" class="btn btn-primary" value="Save">
                    </div>
                </form>
            </div>
        </div>
    </div>
    <?php } else {
        echo "You are not authorized to access this page!";
    } ?>
</div>
<script>
<?php if (isset($_GET['edit'])) { ?>
        $(document).ready(function () {
            $("#add_edit_users").modal('show');
        });
<?php } ?>
</script>
<?php } else { echo "<h1>404</h1><h3>Page not  found</h3>"; } ?>