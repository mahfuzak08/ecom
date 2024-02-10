<div class="auth-page">
    <div class="row">
        <div class="col-sm-6 col-sm-offset-3 col-md-4 col-md-offset-4"> 
            <div class="panel panel-default">
              <div class="panel-body">
                <div class="text-center">
                  <h3><i class="fa fa-lock fa-4x"></i></h3>
                  <h2 class="text-center">Forgot Password?</h2>
                  
                  <?php
                    if($this->session->flashdata('userError')){
                        echo "<p style='color: #900'>".$this->session->flashdata('userError')."</p>";
                    }
                    else
                        echo "<p>You can reset your password here.</p>";
                  ?>
                  <div class="panel-body">
                    <form id="register-form" role="form" autocomplete="off" class="form" method="post">
                      <div class="form-group">
                        <div class="input-group">
                          <span class="input-group-addon"><i class="fa fa-phone"></i></span>
                          <input id="phone" name="phone" placeholder="Enter your phone number" class="form-control"  type="text">
                        </div>
                      </div>
                      <div class="form-group">
                        <input name="recover-submit" class="btn btn-lg btn-primary btn-block" value="Reset Password" type="submit">
                      </div>
                    </form>
                    <!-- (Under Maintenance)
                    <form autocomplete="off" class="form" method="post">
                        <div class="formcontainer" id="form1">
                            <div class="form-group">
                                <div class="input-group">
                                  <span class="input-group-addon"><i class="fa fa-phone"></i></span>
                                  <input id="number" name="phone" placeholder="Enter your phone number" class="form-control"  type="text">
                                </div>
                            </div>
                            <div id="recaptcha-container"></div>
                            <div class="form-group">
                                <input onclick="phoneAuth()" class="btn btn-lg btn-primary btn-block" value="Reset Password" type="button">
                            </div>
                        </div>
                        <div class="formcontainer" id="form2">
                            <div class="form-group">
                                <div class="input-group">
                                  <span class="input-group-addon"><i class="fa fa-phone"></i></span>
                                  <input id="verificationCode" name="phone" placeholder="Enter verification code" class="form-control"  type="text">
                                </div>
                            </div>
                            <div class="form-group">
                                <input onclick="codeverify()" class="btn btn-lg btn-primary btn-block" value="Verify Code" type="button">
                            </div>
                        </div>
                    </form>
                    -->
                  </div>
                </div>
              </div>
            </div>
        </div>
    </div>
</div>