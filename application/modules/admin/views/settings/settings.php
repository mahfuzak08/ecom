<?php if(strpos($access[0]['access'], SETTINGS)>-1) { ?>
<link href="<?= base_url('assets/css/bootstrap-toggle.min.css') ?>" rel="stylesheet">
<script src="<?= base_url('assets/ckeditor/ckeditor.js') ?>"></script>
<h1><img src="<?= base_url('assets/imgs/settings-page.png') ?>" class="header-img" style="margin-top:-3px;">Settings</h1>
<hr>
<div class="row">
	<div class="col-xs-12">
        <div class="panel panel-success col-h">
            <div class="panel-heading">Site Logo
                <div class="box-tools pull-right"><button type="button" class="btn btn-success btn-sm pull-right" data-widget="collapse" data-toggle="tooltip" title="" style="margin-right: 5px;margin-top: -5px;" data-original-title="Collapse"><i class="fa fa-minus"></i></button></div>
            </div>
            <div class="panel-collapse collapse in">
                <div class="panel-body">
                    <?php if ($this->session->flashdata('resultSiteLogoPublish')) { ?>
                        <div class="alert alert-info"><?= $this->session->flashdata('resultSiteLogoPublish') ?></div>
                    <?php } ?>
                    <img src="<?= base_url('attachments/'. SHOP_DIR .'/site_logo/' . $siteLogo) ?>" alt="Logo is deleted. Upload new!" class="img-responsive">
                    <hr>
					<p>Image dimension like as 250px X 80px</p>
                    <form accept-charset="utf-8" method="post" enctype="multipart/form-data" action="">
                        <input type="file" name="sitelogo" size="20" />
                        <input type="submit" value="Upload New" name="uploadimage" class="btn btn-default" />
                    </form>
                </div>
            </div>
        </div>
    </div>
    <div class="col-xs-12">
        <div class="panel panel-success col-h">
            <div class="panel-heading">Site Overview
                <div class="box-tools pull-right"><button type="button" class="btn btn-success btn-sm pull-right" data-widget="collapse" data-toggle="tooltip" title="" style="margin-right: 5px;margin-top: -5px;" data-original-title="Collapse"><i class="fa fa-plus"></i></button></div>
            </div>
            <div class="panel-collapse collapse">
                <div class="panel-body">
                    <?php if ($this->session->flashdata('resultSiteOverview')) { ?>
                        <div class="alert alert-info"><?= $this->session->flashdata('resultSiteOverview') ?></div>
                    <?php } ?>
                    <img src="<?= base_url('attachments/'. SHOP_DIR .'/site_overview/' . $siteOverview) ?>" alt="Site overview is deleted. Upload new!" class="img-responsive">
                    <hr>
					<p>Image dimension like as 1024px X 768px</p>
                    <form accept-charset="utf-8" method="post" enctype="multipart/form-data" action="">
                        <input type="file" name="siteoverview" size="20" />
                        <input type="submit" value="Upload New" name="uploadoverview" class="btn btn-default" />
                    </form>
                </div>
            </div>
        </div>
    </div>
    <div class="col-xs-12">
        <div class="panel panel-success col-h">
            <div class="panel-heading">Site favicon ico
                <div class="box-tools pull-right"><button type="button" class="btn btn-success btn-sm pull-right" data-widget="collapse" data-toggle="tooltip" title="" style="margin-right: 5px;margin-top: -5px;" data-original-title="Collapse"><i class="fa fa-plus"></i></button></div>
            </div>
            <div class="panel-collapse collapse">
                <div class="panel-body">
                    <?php if ($this->session->flashdata('resultSiteIco')) { ?>
                        <div class="alert alert-info"><?= $this->session->flashdata('resultSiteIco') ?></div>
                    <?php } ?>
                    <img src="<?= base_url('attachments/'. SHOP_DIR .'/site_ico/' . $siteico) ?>" alt="Site favicon ico is deleted. Upload new!" class="img-responsive">
                    <hr>
					<p>Image dimension like as 32px X 32px</p>
                    <form accept-charset="utf-8" method="post" enctype="multipart/form-data" action="">
                        <input type="file" name="siteico" size="20" />
                        <input type="submit" value="Upload New" name="uploadico" class="btn btn-default" />
                    </form>
                </div>
            </div>
        </div>
    </div>
    <div class="col-xs-12">
        <div class="panel panel-success col-h">
            <div class="panel-heading">Company Name
                <div class="box-tools pull-right"><button type="button" class="btn btn-success btn-sm pull-right" data-widget="collapse" data-toggle="tooltip" title="" style="margin-right: 5px;margin-top: -5px;" data-original-title="Collapse"><i class="fa fa-plus"></i></button></div>
            </div>
            <div class="panel-collapse collapse">
                <div class="panel-body">
                    <?php if ($this->session->flashdata('resultCompanyName')) { ?>
                        <div class="alert alert-info"><?= $this->session->flashdata('resultCompanyName') ?></div>
                    <?php } ?>
                    <form method="POST" action="">
                        <div class="input-group">
                            <input class="form-control" name="companyName" value="<?= $companyName ?>" type="text">
                            <span class="input-group-btn">
                                <button class="btn btn-default" value="" type="submit">
                                    <i class="fa fa-pencil-square-o" aria-hidden="true"></i>
                                </button>
                            </span>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
	<?php if(strpos($access[0]['access'], DBA)>-1) { ?>
    <div class="col-xs-12">
        <div class="panel panel-success col-h">
            <div class="panel-heading">Android App
                <div class="box-tools pull-right"><button type="button" class="btn btn-success btn-sm pull-right" data-widget="collapse" data-toggle="tooltip" title="" style="margin-right: 5px;margin-top: -5px;" data-original-title="Collapse"><i class="fa fa-plus"></i></button></div>
            </div>
            <div class="panel-collapse collapse">
                <div class="panel-body">
                    <?php if ($this->session->flashdata('resultSiteAPKPublish')) { ?>
                        <div class="alert alert-info"><?= $this->session->flashdata('resultSiteAPKPublish') ?></div>
                    <?php } ?>
                    <?php if(isset($siteAPK) && $siteAPK != ""){ ?>
                    <a href="<?= base_url("attachments/". SHOP_DIR ."/site_app/".$siteAPK); ?>" target="_blank">
                        <img src="<?= base_url('assets/imgs/apk.png') ?>" alt="APK file" class="img-responsive">
                    </a>
                    <hr>
                    <?php } ?>
                    <form method="post" enctype="multipart/form-data" action="">
                        <input type="file" name="siteAPK" size="20" />
                        <input type="submit" value="Upload New" name="uploadAPK" class="btn btn-default" />
                    </form>
                </div>
            </div>
        </div>
    </div>
	<div class="col-xs-12">
        <div class="panel panel-success col-h">
            <div class="panel-heading">Navigation Text
                <div class="box-tools pull-right"><button type="button" class="btn btn-success btn-sm pull-right" data-widget="collapse" data-toggle="tooltip" title="" style="margin-right: 5px;margin-top: -5px;" data-original-title="Collapse"><i class="fa fa-plus"></i></button></div>
            </div>
            <div class="panel-collapse collapse">
                <div class="panel-body">
                    <?php if ($this->session->flashdata('resultNaviText')) { ?>
                        <div class="alert alert-info"><?= $this->session->flashdata('resultNaviText') ?></div>
                    <?php } ?>
                    <form method="POST" action="">
                        <div class="input-group">
                            <input class="form-control" name="naviText" value="<?= $naviText ?>" type="text">
                            <span class="input-group-btn">
                                <button class="btn btn-default" value="" type="submit">
                                    <i class="fa fa-pencil-square-o" aria-hidden="true"></i>
                                </button>
                            </span>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
	<?php } ?>
    <div class="col-xs-12">
        <div class="panel panel-success col-h">
            <div class="panel-heading">Footer Text
                <div class="box-tools pull-right"><button type="button" class="btn btn-success btn-sm pull-right" data-widget="collapse" data-toggle="tooltip" title="" style="margin-right: 5px;margin-top: -5px;" data-original-title="Collapse"><i class="fa fa-plus"></i></button></div>
            </div>
            <div class="panel-collapse collapse">
                <div class="panel-body">
                    <?php if ($this->session->flashdata('resultFooterCopyright')) { ?>
                        <div class="alert alert-info"><?= $this->session->flashdata('resultFooterCopyright') ?></div>
                    <?php } ?>
                    <form method="POST" action="">
                        <div class="input-group">
                            <input class="form-control" name="footerCopyright" value="<?= $footerCopyright ?>" type="text">
                            <span class="input-group-btn">
                                <button class="btn btn-default" value="" placeholder="Find product.." type="submit">
                                    <i class="fa fa-pencil-square-o" aria-hidden="true"></i>
                                </button>
                            </span>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
	<?php if(strpos($access[0]['access'], DBA)>-1) { ?>
    <div class="col-xs-12">
        <div class="panel panel-success col-h">
            <div class="panel-heading">SMS API
                <div class="box-tools pull-right"><button type="button" class="btn btn-success btn-sm pull-right" data-widget="collapse" data-toggle="tooltip" title="" style="margin-right: 5px;margin-top: -5px;" data-original-title="Collapse"><i class="fa fa-plus"></i></button></div>
            </div>
            <div class="panel-collapse collapse">
                <div class="panel-body">
                    <?php if ($this->session->flashdata('resultSMSAPI')) { ?>
                        <div class="alert alert-info"><?= $this->session->flashdata('resultSMSAPI') ?></div>
                    <?php } ?>
                    <form method="POST" action="">
                        <input class="form-control" placeholder="API Key" name="smsURL" value="<?= $smsURL ?>" type="text" style="margin-bottom:10px;"><!-- apiKey -->
                        <input class="form-control" placeholder="Auth Domain" name="smsApi" value="<?= $smsApi ?>" type="text" style="margin-bottom:10px;"><!-- authDomain -->
                        <input class="form-control" placeholder="Project ID" name="smsSenderId" value="<?= $smsSenderId ?>" type="text" style="margin-bottom:10px;"><!-- projectId -->
                        <input class="form-control" placeholder="Storage Bucket" name="smsUserName" value="<?= $smsUserName ?>" type="text" style="margin-bottom:10px;"><!-- storageBucket -->
                        <input class="form-control" placeholder="Messaging Sender ID" name="smsPass" value="<?= $smsPass ?>" type="text" style="margin-bottom:10px;"><!-- messagingSenderId -->
                        <input class="form-control" placeholder="APP ID" name="appId" value="<?= $appId ?>" type="text" style="margin-bottom:10px;">
                        <input class="form-control" placeholder="Measurement ID" name="measurementId" value="<?= $measurementId ?>" type="text" style="margin-bottom:10px;">
                        <button class="btn btn-default" value="" type="submit">
                            Save
                        </button>
                    </form>
                </div>
            </div>
        </div>
    </div>
	<?php } ?>
    <div class="col-xs-12">
        <div class="panel panel-success col-h">
            <div class="panel-heading">Contacts footer
                <div class="box-tools pull-right"><button type="button" class="btn btn-success btn-sm pull-right" data-widget="collapse" data-toggle="tooltip" title="" style="margin-right: 5px;margin-top: -5px;" data-original-title="Collapse"><i class="fa fa-plus"></i></button></div>
            </div>
            <div class="panel-collapse collapse">
                <div class="panel-body">
                    <?php if ($this->session->flashdata('resultfooterContacts')) { ?>
                        <div class="alert alert-info"><?= $this->session->flashdata('resultfooterContacts') ?></div>
                    <?php } ?>
                    <form method="POST" action="">
                        <div class="form-group" style="position: relative;">
                            <input type="text" style="padding-left:25px;" class="form-control" name="footerContactAddr" value="<?= $footerContactAddr ?>">
                            <i class="fa fa-map-marker" style="position: absolute;top:10px;left:10px;"></i>
                        </div>
                        <div class="form-group" style="position: relative;">
                            <i class="fa fa-phone" style="position: absolute;top:10px;left:10px;"></i>
                            <input type="text" style="padding-left:25px;" class="form-control" name="footerContactPhone" value="<?= $footerContactPhone ?>">
                        </div>
                        <div class="form-group" style="position: relative;">
                            <i class="fa fa-envelope" style="position: absolute;top:10px;left:10px;"></i>
                            <input type="text" style="padding-left:25px;" class="form-control" name="footerContactEmail" value="<?= $footerContactEmail ?>">
                        </div>
                        <div class="form-group" style="position: relative;">
                            <i class="fa fa-lock" style="position: absolute;top:10px;left:10px;"></i>
                            <input type="password" style="padding-left:25px;" class="form-control" name="footerContactEmailPass" value="<?= $footerContactEmailPass ?>">
                        </div>
                        <div class="form-group">
                            <input type="submit" class="btn btn-default" name="footerContacts" value="Update">
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
	<?php if(strpos($access[0]['access'], DBA)>-1) { ?>
	<div class="col-xs-12">
        <div class="panel panel-success col-h">
            <div class="panel-heading">Office Time
                <div class="box-tools pull-right"><button type="button" class="btn btn-success btn-sm pull-right" data-widget="collapse" data-toggle="tooltip" title="" style="margin-right: 5px;margin-top: -5px;" data-original-title="Collapse"><i class="fa fa-plus"></i></button></div>
            </div>
            <div class="panel-collapse collapse">
                <div class="panel-body">
                    <?php if ($this->session->flashdata('resultOfficeTime')) { ?>
                        <div class="alert alert-info"><?= $this->session->flashdata('resultOfficeTime') ?></div>
                    <?php } ?>
                    <form method="POST" action="">
                        <div class="form-group" style="position: relative;">
                            <input type="text" style="padding-left:25px;" class="form-control" name="officeTimeStart" value="<?= $officeTimeStart; ?>" placeholder="Start Time">
                            <i class="fa fa-history" style="position: absolute;top:10px;left:10px;"></i>
                        </div>
                        <div class="form-group" style="position: relative;">
                            <i class="fa fa-history" style="position: absolute;top:10px;left:10px;"></i>
                            <input type="text" style="padding-left:25px;" class="form-control" name="officeTimeEnd" value="<?= $officeTimeEnd; ?>" placeholder="End Time">
                        </div>
                        <div class="form-group">
                            <input type="submit" class="btn btn-default" name="officeTime" value="Update">
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
    <div class="col-xs-12">
        <div class="panel panel-success col-h">
            <div class="panel-heading">Google Maps
                <div class="box-tools pull-right"><button type="button" class="btn btn-success btn-sm pull-right" data-widget="collapse" data-toggle="tooltip" title="" style="margin-right: 5px;margin-top: -5px;" data-original-title="Collapse"><i class="fa fa-plus"></i></button></div>
            </div>
            <div class="panel-collapse collapse">
                <div class="panel-body">
                    <?php if ($this->session->flashdata('resultGoogleMaps')) { ?>
                        <div class="alert alert-info"><?= $this->session->flashdata('resultGoogleMaps') ?></div>
                    <?php } ?>
                    <form method="POST" action="">
                        <input class="form-control" placeholder="Direction: 42.676250, 23.371063" name="googleMaps" value="<?= $googleMaps ?>" type="text" style="margin-bottom:10px;">
                        <input class="form-control" placeholder="Api key" name="googleApi" value="<?= $googleApi ?>" type="text" style="margin-bottom:10px;">
                        <button class="btn btn-default" value="" type="submit">
                            Save
                        </button>
                    </form>
                </div>
            </div>
        </div>
    </div>
	<?php } ?>
    <div class="col-xs-12">
        <div class="panel panel-success col-h">
            <div class="panel-heading">Footer about us
                <div class="box-tools pull-right"><button type="button" class="btn btn-success btn-sm pull-right" data-widget="collapse" data-toggle="tooltip" title="" style="margin-right: 5px;margin-top: -5px;" data-original-title="Collapse"><i class="fa fa-plus"></i></button></div>
            </div>
            <div class="panel-collapse collapse">
                <div class="panel-body">
                    <?php if ($this->session->flashdata('resultFooterAboutUs')) { ?>
                        <div class="alert alert-info"><?= $this->session->flashdata('resultFooterAboutUs') ?></div>
                    <?php } ?>
                    <form method="POST" action="">
                        <div class="input-group">
                            <input class="form-control" name="footerAboutUs" value="<?= $footerAboutUs ?>" type="text">
                            <span class="input-group-btn">
                                <button class="btn btn-default" value="" type="submit">
                                    <i class="fa fa-pencil-square-o" aria-hidden="true"></i>
                                </button>
                            </span>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
	<?php if(strpos($access[0]['access'], DBA)>-1) { ?>
    <div class="col-xs-12">
        <div class="panel panel-success col-h">
            <div class="panel-heading">Social media links
                <div class="box-tools pull-right"><button type="button" class="btn btn-success btn-sm pull-right" data-widget="collapse" data-toggle="tooltip" title="" style="margin-right: 5px;margin-top: -5px;" data-original-title="Collapse"><i class="fa fa-plus"></i></button></div>
            </div>
            <div class="panel-collapse collapse">
                <div class="panel-body">
                    <?php if ($this->session->flashdata('resultfooterSocial')) { ?>
                        <div class="alert alert-info"><?= $this->session->flashdata('resultfooterSocial') ?></div>
                    <?php } ?>
                    <form method="POST" action="">
                        <div class="form-group" style="position: relative;">
                            <input type="text" style="padding-left:25px;" class="form-control" name="footerSocialFacebook" value="<?= $footerSocialFacebook ?>">
                            <i class="fa fa-facebook" style="position: absolute;top:10px;left:10px;"></i>
                        </div>
                        <div class="form-group" style="position: relative;">
                            <i class="fa fa-twitter" style="position: absolute;top:10px;left:10px;"></i>
                            <input type="text" style="padding-left:25px;" class="form-control" name="footerSocialTwitter" value="<?= $footerSocialTwitter ?>">
                        </div>
                        <div class="form-group" style="position: relative;">
                            <i class="fa fa-google-plus" style="position: absolute;top:10px;left:10px;"></i>
                            <input type="text" style="padding-left:25px;" class="form-control" name="footerSocialGooglePlus" value="<?= $footerSocialGooglePlus ?>">
                        </div>
                        <div class="form-group" style="position: relative;">
                            <i class="fa fa-pinterest" style="position: absolute;top:10px;left:10px;"></i>
                            <input type="text" style="padding-left:25px;" class="form-control" name="footerSocialPinterest" value="<?= $footerSocialPinterest ?>">
                        </div>
                        <div class="form-group" style="position: relative;">
                            <i class="fa fa-youtube" style="position: absolute;top:10px;left:10px;"></i>
                            <input type="text" style="padding-left:25px;" class="form-control" name="footerSocialYoutube" value="<?= $footerSocialYoutube ?>">
                        </div>
                        <div class="form-group">
                            <input type="submit" class="btn btn-default" name="footerSocial" value="Update">
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
	<?php } ?>
    <div class="col-xs-12">
        <div class="panel panel-success col-h">
            <div class="panel-heading">Send email from contact form to
                <div class="box-tools pull-right"><button type="button" class="btn btn-success btn-sm pull-right" data-widget="collapse" data-toggle="tooltip" title="" style="margin-right: 5px;margin-top: -5px;" data-original-title="Collapse"><i class="fa fa-plus"></i></button></div>
            </div>
            <div class="panel-collapse collapse">
                <div class="panel-body">
                    <?php if ($this->session->flashdata('resultEmailTo')) { ?>
                        <div class="alert alert-info"><?= $this->session->flashdata('resultEmailTo') ?></div>
                    <?php } ?>
                    <form method="POST" action="">
                        <div class="input-group">
                            <input class="form-control" name="contactsEmailTo" value="<?= $contactsEmailTo ?>" type="text">
                            <span class="input-group-btn">
                                <button class="btn btn-default" value="" type="submit">
                                    <i class="fa fa-pencil-square-o" aria-hidden="true"></i>
                                </button>
                            </span>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
    <?php if(strpos($access[0]['access'], DBA)>-1) { ?>
	<div class="col-xs-12">
        <div class="panel panel-success col-h">
            <div class="panel-heading">Free Shipping for order equal or more than
                <div class="box-tools pull-right"><button type="button" class="btn btn-success btn-sm pull-right" data-widget="collapse" data-toggle="tooltip" title="" style="margin-right: 5px;margin-top: -5px;" data-original-title="Collapse"><i class="fa fa-plus"></i></button></div>
            </div>
            <div class="panel-collapse collapse">
                <div class="panel-body">
                    <?php if ($this->session->flashdata('shippingOrder')) { ?>
                        <div class="alert alert-info"><?= $this->session->flashdata('shippingOrder') ?></div>
                    <?php } ?>
                    <form method="POST" action="">
                        <div class="input-group">
                            <input class="form-control" name="shippingOrder" value="<?= $shippingOrder ?>" type="text">
                            <span class="input-group-btn">
                                <button class="btn btn-default" value="" type="submit">
                                    <i class="fa fa-pencil-square-o" aria-hidden="true"></i>
                                </button>
                            </span>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
	<div class="col-xs-12">
        <div class="panel panel-success col-h">
            <div class="panel-heading">Add google or other JavaScript to site
                <div class="box-tools pull-right"><button type="button" class="btn btn-success btn-sm pull-right" data-widget="collapse" data-toggle="tooltip" title="" style="margin-right: 5px;margin-top: -5px;" data-original-title="Collapse"><i class="fa fa-plus"></i></button></div>
            </div>
            <div class="panel-collapse collapse">
                <div class="panel-body">
                    <?php if ($this->session->flashdata('addJs')) { ?>
                        <div class="alert alert-info"><?= $this->session->flashdata('addJs') ?></div>
                    <?php } ?>
                    <form method="POST" action="">
                        <textarea style="margin-bottom:5px;" name="addJs" class="form-control"><?= $addJs ?></textarea>
                        <button class="btn btn-default" value="" type="submit">
                            Add the code
                        </button>
                    </form>
                </div>
            </div>
        </div>
    </div>
    <div class="col-xs-12">
        <div class="panel panel-success col-h">
            <div class="panel-heading">Add google reCAPTCHA v2 key
                <div class="box-tools pull-right"><button type="button" class="btn btn-success btn-sm pull-right" data-widget="collapse" data-toggle="tooltip" title="" style="margin-right: 5px;margin-top: -5px;" data-original-title="Collapse"><i class="fa fa-plus"></i></button></div>
            </div>
            <div class="panel-collapse collapse">
                <div class="panel-body">
                    <?php if ($this->session->flashdata('g_recaptcha_key')) { ?>
                        <div class="alert alert-info"><?= $this->session->flashdata('g_recaptcha_key') ?></div>
                    <?php } ?>
                    <form method="POST" action="">
                        <input name="g_recaptcha_site_key" class="form-control" value="<?= $g_recaptcha_site_key ?>" placeholder="Site Key" > <br />
                        <input name="g_recaptcha_secret_key" class="form-control" value="<?= $g_recaptcha_secret_key ?>" placeholder="Secret Key" > <br />
                        <button class="btn btn-default" value="" type="submit">
                            Update
                        </button>
                    </form>
                </div>
            </div>
        </div>
    </div>
    <div class="col-xs-12">
        <div class="panel panel-success col-h">
            <div class="panel-heading">Public total visitor visibility
                <div class="box-tools pull-right"><button type="button" class="btn btn-success btn-sm pull-right" data-widget="collapse" data-toggle="tooltip" title="" style="margin-right: 5px;margin-top: -5px;" data-original-title="Collapse"><i class="fa fa-plus"></i></button></div>
            </div>
            <div class="panel-collapse collapse">
                <div class="panel-body">
                    <?php if ($this->session->flashdata('publicVisitor')) { ?>
                        <div class="alert alert-info"><?= $this->session->flashdata('publicVisitor') ?></div>
                    <?php } ?>
                    <form method="POST" action="">
                        <input type="text" name="publicVisitor" class="form-control" value="<?= $publicVisitor ?>" placeholder="Start at"><br>
                        <input <?= $publicVisitor > 0 ? 'checked' : '' ?> data-toggle="toggle" data-for-field="publicVisitor" class="toggle-changer" type="checkbox">
                        <button class="btn btn-default" value="" type="submit">
                            Save
                        </button>
                    </form>
                </div>
            </div>
        </div>
    </div>
    <div class="col-xs-12">
        <div class="panel panel-success col-h">
            <div class="panel-heading">Public quantity visability
                <div class="box-tools pull-right"><button type="button" class="btn btn-success btn-sm pull-right" data-widget="collapse" data-toggle="tooltip" title="" style="margin-right: 5px;margin-top: -5px;" data-original-title="Collapse"><i class="fa fa-plus"></i></button></div>
            </div>
            <div class="panel-collapse collapse">
                <div class="panel-body">
                    <?php if ($this->session->flashdata('publicQuantity')) { ?>
                        <div class="alert alert-info"><?= $this->session->flashdata('publicQuantity') ?></div>
                    <?php } ?>
                    <form method="POST" action="">
                        <input type="hidden" name="publicQuantity" value="<?= $publicQuantity ?>">
                        <input <?= $publicQuantity == 1 ? 'checked' : '' ?> data-toggle="toggle" data-for-field="publicQuantity" class="toggle-changer" type="checkbox">
                        <button class="btn btn-default" value="" type="submit">
                            Save
                        </button>
                    </form>
                </div>
            </div>
        </div>
    </div>
    <div class="col-xs-12">
        <div class="panel panel-success col-h">
            <div class="panel-heading">Public date added visability
                <div class="box-tools pull-right"><button type="button" class="btn btn-success btn-sm pull-right" data-widget="collapse" data-toggle="tooltip" title="" style="margin-right: 5px;margin-top: -5px;" data-original-title="Collapse"><i class="fa fa-plus"></i></button></div>
            </div>
            <div class="panel-collapse collapse">
                <div class="panel-body">
                    <?php if ($this->session->flashdata('publicDateAdded')) { ?>
                        <div class="alert alert-info"><?= $this->session->flashdata('publicDateAdded') ?></div>
                    <?php } ?>
                    <form method="POST" action="">
                        <input type="hidden" name="publicDateAdded" value="<?= $publicDateAdded ?>">
                        <input <?= $publicDateAdded == 1 ? 'checked' : '' ?> data-toggle="toggle" data-for-field="publicDateAdded" class="toggle-changer" type="checkbox">
                        <button class="btn btn-default" value="" type="submit">
                            Save
                        </button>
                    </form>
                </div>
            </div>
        </div>
    </div>
    <div class="col-xs-12">
        <div class="panel panel-success col-h">
            <div class="panel-heading">Multi-Vendor Support
                <div class="box-tools pull-right"><button type="button" class="btn btn-success btn-sm pull-right" data-widget="collapse" data-toggle="tooltip" title="" style="margin-right: 5px;margin-top: -5px;" data-original-title="Collapse"><i class="fa fa-plus"></i></button></div>
            </div>
            <div class="panel-collapse collapse">
                <div class="panel-body">
                    <?php if ($this->session->flashdata('multiVendor')) { ?>
                        <div class="alert alert-info"><?= $this->session->flashdata('multiVendor') ?></div>
                    <?php } ?>
                    <form method="POST" action="">
                        <input type="hidden" name="multiVendor" value="<?= $multiVendor ?>">
                        <input <?= $multiVendor == 1 ? 'checked' : '' ?> data-toggle="toggle" data-for-field="multiVendor" class="toggle-changer" type="checkbox">
                        <button class="btn btn-default" value="" type="submit">
                            Save
                        </button>
                    </form>
                </div>
            </div>
        </div>
    </div>
    <div class="col-xs-12">
        <div class="panel panel-success col-h">
            <div class="panel-heading">Wish List Support
                <div class="box-tools pull-right"><button type="button" class="btn btn-success btn-sm pull-right" data-widget="collapse" data-toggle="tooltip" title="" style="margin-right: 5px;margin-top: -5px;" data-original-title="Collapse"><i class="fa fa-plus"></i></button></div>
            </div>
            <div class="panel-collapse collapse">
                <div class="panel-body">
                    <?php if ($this->session->flashdata('resultWish_list')) { ?>
                        <div class="alert alert-info"><?= $this->session->flashdata('resultWish_list') ?></div>
                    <?php } ?>
                    <form method="POST" action="">
                        <input type="hidden" name="wish_list" value="<?= $wish_list ?>">
                        <input <?= $wish_list == 1 ? 'checked' : '' ?> data-toggle="toggle" data-for-field="wish_list" class="toggle-changer" type="checkbox">
                        <button class="btn btn-default" value="" type="submit">
                            Save
                        </button>
                    </form>
                </div>
            </div>
        </div>
    </div>
    <div class="col-xs-12">
        <div class="panel panel-success col-h">
            <div class="panel-heading">Multi-Size Support
                <div class="box-tools pull-right"><button type="button" class="btn btn-success btn-sm pull-right" data-widget="collapse" data-toggle="tooltip" title="" style="margin-right: 5px;margin-top: -5px;" data-original-title="Collapse"><i class="fa fa-plus"></i></button></div>
            </div>
            <div class="panel-collapse collapse">
                <div class="panel-body">
                    <?php if ($this->session->flashdata('multiSize')) { ?>
                        <div class="alert alert-info"><?= $this->session->flashdata('multiSize') ?></div>
                    <?php } ?>
                    <form method="POST" action="">
                        <input type="hidden" name="multiSize" value="<?= $multiSize ?>">
                        <input <?= $multiSize == 1 ? 'checked' : '' ?> data-toggle="toggle" data-for-field="multiSize" class="toggle-changer" type="checkbox">
                        <button class="btn btn-default" value="" type="submit">
                            Save
                        </button>
                    </form>
                </div>
            </div>
        </div>
    </div>
	<div class="col-xs-12">
        <div class="panel panel-success col-h">
            <div class="panel-heading">Labour Cost
                <div class="box-tools pull-right"><button type="button" class="btn btn-success btn-sm pull-right" data-widget="collapse" data-toggle="tooltip" title="" style="margin-right: 5px;margin-top: -5px;" data-original-title="Collapse"><i class="fa fa-plus"></i></button></div>
            </div>
            <div class="panel-collapse collapse">
                <div class="panel-body">
                    <?php if ($this->session->flashdata('labourCost')) { ?>
                        <div class="alert alert-info"><?= $this->session->flashdata('labourCost') ?></div>
                    <?php } ?>
                    <form method="POST" action="">
                        <input type="hidden" name="labourCost" value="<?= $labourCost ?>">
                        <input <?= $labourCost == 1 ? 'checked' : '' ?> data-toggle="toggle" data-for-field="labourCost" class="toggle-changer" type="checkbox">
                        <button class="btn btn-default" value="" type="submit">
                            Save
                        </button>
                    </form>
                </div>
            </div>
        </div>
    </div>
	<div class="col-xs-12">
        <div class="panel panel-success col-h">
            <div class="panel-heading">Carrying Cost
                <div class="box-tools pull-right"><button type="button" class="btn btn-success btn-sm pull-right" data-widget="collapse" data-toggle="tooltip" title="" style="margin-right: 5px;margin-top: -5px;" data-original-title="Collapse"><i class="fa fa-plus"></i></button></div>
            </div>
            <div class="panel-collapse collapse">
                <div class="panel-body">
                    <?php if ($this->session->flashdata('carryingCost')) { ?>
                        <div class="alert alert-info"><?= $this->session->flashdata('carryingCost') ?></div>
                    <?php } ?>
                    <form method="POST" action="">
                        <input type="hidden" name="carryingCost" value="<?= $carryingCost ?>">
                        <input <?= $carryingCost == 1 ? 'checked' : '' ?> data-toggle="toggle" data-for-field="carryingCost" class="toggle-changer" type="checkbox">
                        <button class="btn btn-default" value="" type="submit">
                            Save
                        </button>
                    </form>
                </div>
            </div>
        </div>
    </div>
	<div class="col-xs-12">
        <div class="panel panel-success col-h">
            <div class="panel-heading">Sales Return (Return Cash)
                <div class="box-tools pull-right"><button type="button" class="btn btn-success btn-sm pull-right" data-widget="collapse" data-toggle="tooltip" title="" style="margin-right: 5px;margin-top: -5px;" data-original-title="Collapse"><i class="fa fa-plus"></i></button></div>
            </div>
            <div class="panel-collapse collapse">
                <div class="panel-body">
                    <?php if ($this->session->flashdata('salesReturn')) { ?>
                        <div class="alert alert-info"><?= $this->session->flashdata('salesReturn') ?></div>
                    <?php } ?>
                    <form method="POST" action="">
                        <input type="hidden" name="salesReturn" value="<?= $salesReturn ?>">
                        <input <?= $salesReturn == 1 ? 'checked' : '' ?> data-toggle="toggle" data-for-field="salesReturn" class="toggle-changer" type="checkbox">
                        <button class="btn btn-default" value="" type="submit">
                            Save
                        </button>
                    </form>
                </div>
            </div>
        </div>
    </div>
	<div class="col-xs-12">
        <div class="panel panel-success col-h">
            <div class="panel-heading">Barcode Scanner
                <div class="box-tools pull-right"><button type="button" class="btn btn-success btn-sm pull-right" data-widget="collapse" data-toggle="tooltip" title="" style="margin-right: 5px;margin-top: -5px;" data-original-title="Collapse"><i class="fa fa-plus"></i></button></div>
            </div>
            <div class="panel-collapse collapse">
                <div class="panel-body">
                    <?php if ($this->session->flashdata('barcodeScanner')) { ?>
                        <div class="alert alert-info"><?= $this->session->flashdata('barcodeScanner') ?></div>
                    <?php } ?>
                    <form method="POST" action="">
                        <input type="hidden" name="barcodeScanner" value="<?= $barcodeScanner; ?>">
                        <input <?= $barcodeScanner == 1 ? 'checked' : ''; ?> data-toggle="toggle" data-for-field="barcodeScanner" class="toggle-changer" type="checkbox">
                        <button class="btn btn-default" value="" type="submit">
                            Save
                        </button>
                    </form>
                </div>
            </div>
        </div>
    </div>	
    <div class="col-xs-12">
        <div class="panel panel-success col-h">
            <div class="panel-heading">Show in list out of stock products
                <div class="box-tools pull-right"><button type="button" class="btn btn-success btn-sm pull-right" data-widget="collapse" data-toggle="tooltip" title="" style="margin-right: 5px;margin-top: -5px;" data-original-title="Collapse"><i class="fa fa-plus"></i></button></div>
            </div>
            <div class="panel-collapse collapse">
                <div class="panel-body">
                    <?php if ($this->session->flashdata('outOfStock')) { ?>
                        <div class="alert alert-info"><?= $this->session->flashdata('outOfStock') ?></div>
                    <?php } ?>
                    <form method="POST" action="">
                        <input type="hidden" name="outOfStock" value="<?= $outOfStock ?>">
                        <input <?= $outOfStock == 1 ? 'checked' : '' ?> data-toggle="toggle" data-for-field="outOfStock" class="toggle-changer" type="checkbox">
                        <button class="btn btn-default" value="" type="submit">
                            Save
                        </button>
                    </form>
                </div>
            </div>
        </div>
    </div>
    <div class="col-xs-12">
        <div class="panel panel-success col-h">
            <div class="panel-heading">POS can sale out of stock products
                <div class="box-tools pull-right"><button type="button" class="btn btn-success btn-sm pull-right" data-widget="collapse" data-toggle="tooltip" title="" style="margin-right: 5px;margin-top: -5px;" data-original-title="Collapse"><i class="fa fa-plus"></i></button></div>
            </div>
            <div class="panel-collapse collapse">
                <div class="panel-body">
                    <?php if ($this->session->flashdata('hasStock')) { ?>
                        <div class="alert alert-info"><?= $this->session->flashdata('hasStock') ?></div>
                    <?php } ?>
                    <form method="POST" action="">
                        <input type="hidden" name="hasStock" value="<?= $hasStock ?>">
                        <input <?= $hasStock == 1 ? 'checked' : '' ?> data-toggle="toggle" data-for-field="hasStock" class="toggle-changer" type="checkbox">
                        <button class="btn btn-default" value="" type="submit">
                            Save
                        </button>
                    </form>
                </div>
            </div>
        </div>
    </div>
    <div class="col-xs-12">
        <div class="panel panel-success col-h">
            <div class="panel-heading">POS invoice register show the image
                <div class="box-tools pull-right"><button type="button" class="btn btn-success btn-sm pull-right" data-widget="collapse" data-toggle="tooltip" title="" style="margin-right: 5px;margin-top: -5px;" data-original-title="Collapse"><i class="fa fa-plus"></i></button></div>
            </div>
            <div class="panel-collapse collapse">
                <div class="panel-body">
                    <?php if ($this->session->flashdata('invImgShow')) { ?>
                        <div class="alert alert-info"><?= $this->session->flashdata('invImgShow') ?></div>
                    <?php } ?>
                    <form method="POST" action="">
                        <input type="hidden" name="invImgShow" value="<?= $invImgShow ?>">
                        <input <?= $invImgShow == 1 ? 'checked' : '' ?> data-toggle="toggle" data-for-field="invImgShow" class="toggle-changer" type="checkbox">
                        <button class="btn btn-default" value="" type="submit">
                            Save
                        </button>
                    </form>
                </div>
            </div>
        </div>
    </div>
    <div class="col-xs-12">
        <div class="panel panel-success col-h">
            <div class="panel-heading">POS invoice off description
                <div class="box-tools pull-right"><button type="button" class="btn btn-success btn-sm pull-right" data-widget="collapse" data-toggle="tooltip" title="" style="margin-right: 5px;margin-top: -5px;" data-original-title="Collapse"><i class="fa fa-plus"></i></button></div>
            </div>
            <div class="panel-collapse collapse">
                <div class="panel-body">
                    <?php if ($this->session->flashdata('invDesShow')) { ?>
                        <div class="alert alert-info"><?= $this->session->flashdata('invDesShow') ?></div>
                    <?php } ?>
                    <form method="POST" action="">
                        <input type="hidden" name="invDesShow" value="<?= $invDesShow ?>">
                        <input <?= $invDesShow == 1 ? 'checked' : '' ?> data-toggle="toggle" data-for-field="invDesShow" class="toggle-changer" type="checkbox">
                        <button class="btn btn-default" value="" type="submit">
                            Save
                        </button>
                    </form>
                </div>
            </div>
        </div>
    </div>
    <div class="col-xs-12">
        <div class="panel panel-success col-h">
            <div class="panel-heading">Show 'More information' button in products list
                <div class="box-tools pull-right"><button type="button" class="btn btn-success btn-sm pull-right" data-widget="collapse" data-toggle="tooltip" title="" style="margin-right: 5px;margin-top: -5px;" data-original-title="Collapse"><i class="fa fa-plus"></i></button></div>
            </div>
            <div class="panel-collapse collapse">
                <div class="panel-body">
                    <?php if ($this->session->flashdata('moreInfoBtn')) { ?>
                        <div class="alert alert-info"><?= $this->session->flashdata('moreInfoBtn') ?></div>
                    <?php } ?>
                    <form method="POST" action="">
                        <input type="hidden" name="moreInfoBtn" value="<?= $moreInfoBtn ?>">
                        <input <?= $moreInfoBtn == 1 ? 'checked' : '' ?> data-toggle="toggle" data-for-field="moreInfoBtn" class="toggle-changer" type="checkbox">
                        <button class="btn btn-default" value="" type="submit">
                            Save
                        </button>
                    </form>
                </div>
            </div>
        </div>
    </div>
    <div class="col-xs-12">
        <div class="panel panel-success col-h">
            <div class="panel-heading">Show brands
                <div class="box-tools pull-right"><button type="button" class="btn btn-success btn-sm pull-right" data-widget="collapse" data-toggle="tooltip" title="" style="margin-right: 5px;margin-top: -5px;" data-original-title="Collapse"><i class="fa fa-plus"></i></button></div>
            </div>
            <div class="panel-collapse collapse">
                <div class="panel-body">
                    <?php if ($this->session->flashdata('showBrands')) { ?>
                        <div class="alert alert-info"><?= $this->session->flashdata('showBrands') ?></div>
                    <?php } ?>
                    <form method="POST" action="">
                        <input type="hidden" name="showBrands" value="<?= $showBrands ?>">
                        <input <?= $showBrands == 1 ? 'checked' : '' ?> data-toggle="toggle" data-for-field="showBrands" class="toggle-changer" type="checkbox">
                        <button class="btn btn-default" value="" type="submit">
                            Save
                        </button>
                    </form>
                </div>
            </div>
        </div>
    </div>
    <div class="col-xs-12">
        <div class="panel panel-success col-h">
            <div class="panel-heading">Show in slider products to list
                <div class="box-tools pull-right"><button type="button" class="btn btn-success btn-sm pull-right" data-widget="collapse" data-toggle="tooltip" title="" style="margin-right: 5px;margin-top: -5px;" data-original-title="Collapse"><i class="fa fa-plus"></i></button></div>
            </div>
            <div class="panel-collapse collapse">
                <div class="panel-body">
                    <?php if ($this->session->flashdata('showInSlider')) { ?>
                        <div class="alert alert-info"><?= $this->session->flashdata('showInSlider') ?></div>
                    <?php } ?>
                    <form method="POST" action="">
                        <input type="hidden" name="showInSlider" value="<?= $showInSlider ?>">
                        <input <?= $showInSlider == 1 ? 'checked' : '' ?> data-toggle="toggle" data-for-field="showInSlider" class="toggle-changer" type="checkbox">
                        <button class="btn btn-default" value="" type="submit">
                            Save
                        </button>
                    </form>
                </div>
            </div>
        </div>
    </div>
    <div class="col-xs-12">
        <div class="panel panel-success col-h">
            <div class="panel-heading">Virtual products
                <div class="box-tools pull-right"><button type="button" class="btn btn-success btn-sm pull-right" data-widget="collapse" data-toggle="tooltip" title="" style="margin-right: 5px;margin-top: -5px;" data-original-title="Collapse"><i class="fa fa-plus"></i></button></div>
            </div>
            <div class="panel-collapse collapse">
                <div class="panel-body">
                    <?php if ($this->session->flashdata('virtualProducts')) { ?>
                        <div class="alert alert-info"><?= $this->session->flashdata('virtualProducts') ?></div>
                    <?php } ?>
                    <form method="POST" action="">
                        <input type="hidden" name="virtualProducts" value="<?= $virtualProducts ?>">
                        <input <?= $virtualProducts == 1 ? 'checked' : '' ?> data-toggle="toggle" data-for-field="virtualProducts" class="toggle-changer" type="checkbox">
                        <button class="btn btn-default" value="" type="submit">
                            Save
                        </button>
                    </form>
                </div>
            </div>
        </div>
    </div>
	<div class="col-xs-12">
        <div class="panel panel-success col-h">
            <div class="panel-heading">Cookie Law Notification
                <div class="box-tools pull-right"><button type="button" class="btn btn-success btn-sm pull-right" data-widget="collapse" data-toggle="tooltip" title="" style="margin-right: 5px;margin-top: -5px;" data-original-title="Collapse"><i class="fa fa-plus"></i></button></div>
            </div>
            <div class="panel-collapse collapse">
                <div class="panel-body">
                    <?php if ($this->session->flashdata('cookieNotificator')) { ?>
                        <div class="alert alert-info"><?= $this->session->flashdata('cookieNotificator') ?></div>
                    <?php } ?>
                    <form method="POST" action="">
                        <input type="hidden" name="visibility" value="<?= isset($cookieLawInfo['cookieInfo']['visibility']) ? $cookieLawInfo['cookieInfo']['visibility'] : '0' ?>">
                        <label>Enable:</label>
                        <input <?= isset($cookieLawInfo['cookieInfo']['visibility']) && $cookieLawInfo['cookieInfo']['visibility'] == 1 ? 'checked' : '' ?> data-toggle="toggle" data-for-field="visibility" class="toggle-changer" type="checkbox">
                        <hr>
                        <?php foreach ($languages as $language) { ?>
                            <input type="hidden" name="translations[]" value="<?= $language->abbr ?>">
                        <?php } foreach ($languages as $language) { ?>
                            <div class="form-group">
                                <label for="message-cookie-law">Message (<?= $language->name ?><img src="<?= base_url('attachments/lang_flags/' . $language->flag) ?>" alt="">)</label>
                                <input type="text" name="message[]" value="<?= isset($cookieLawInfo['cookieTranslate'][$language->abbr]['message']) ? $cookieLawInfo['cookieTranslate'][$language->abbr]['message'] : '' ?>" class="form-control" id="message-cookie-law">
                            </div>
                        <?php } foreach ($languages as $language) {
                            ?>
                            <div class="form-group">
                                <label for="btn-cookie-law">Button Text (<?= $language->name ?><img src="<?= base_url('attachments/lang_flags/' . $language->flag) ?>" alt="">)</label>
                                <input type="text" name="button_text[]" value="<?= isset($cookieLawInfo['cookieTranslate'][$language->abbr]['button_text']) ? $cookieLawInfo['cookieTranslate'][$language->abbr]['button_text'] : '' ?>" class="form-control" id="btn-cookie-law">
                            </div>
                        <?php } foreach ($languages as $language) { ?>
                            <div class="form-group">
                                <label for="learn_more">Learn More (<?= $language->name ?><img src="<?= base_url('attachments/lang_flags/' . $language->flag) ?>" alt="">):</label>
                                <input type="text" name="learn_more[]" value="<?= isset($cookieLawInfo['cookieTranslate'][$language->abbr]['learn_more']) ? $cookieLawInfo['cookieTranslate'][$language->abbr]['learn_more'] : '' ?>" class="form-control" id="learn_more">
                            </div>
                        <?php } ?>
                        <div class="form-group">
                            <label for="link-cookie-law"><i class="fa fa-link" aria-hidden="true"></i> Link to learn more (the law):</label>
                            <input type="text" name="link" value="<?= isset($cookieLawInfo['cookieInfo']['link']) ? $cookieLawInfo['cookieInfo']['link'] : '' ?>" class="form-control" id="link-cookie-law">
                        </div>
                        <div class="form-group">
                            <label>Theme choose:</label>
                            <input type="hidden" name="theme" value="<?= isset($cookieLawInfo['cookieInfo']['theme']) ? $cookieLawInfo['cookieInfo']['theme'] : '' ?>">
                            <div class="row cookie-law-themes bg-info">
                                <?php foreach ($law_themes as $theme) { ?>
                                    <div class="col-sm-6">
                                        <a href="javascript:void(0);" class="select-law-theme" data-law-theme="<?= str_replace('.png', '', $theme) ?>">
                                            <img src="<?= base_url('assets/imgs/cookie-law-themes/' . $theme) ?>" class="img-responsive theme" alt="<?= $theme ?>">
                                            <img src="<?= base_url('assets/imgs/ok-themes.png') ?>" <?=
                                            isset($cookieLawInfo['cookieInfo']['theme']) &&
                                            $cookieLawInfo['cookieInfo']['theme'] == str_replace('.png', '', $theme) ? 'style="display:block;"' : ''
                                            ?> class="ok" alt="CHOOSED">
                                        </a>
                                    </div>
                                <?php } ?>
                            </div>
                        </div>
                        <button class="btn btn-default" name="setCookieLaw" value="" type="submit">
                            Save
                        </button>
                    </form>
                </div>
            </div>
        </div>
    </div>
    <?php } ?>
	<div class="col-xs-12">
        <div class="panel panel-success col-h">
            <div class="panel-heading">Contacts page
                <div class="box-tools pull-right"><button type="button" class="btn btn-success btn-sm pull-right" data-widget="collapse" data-toggle="tooltip" title="" style="margin-right: 5px;margin-top: -5px;" data-original-title="Collapse"><i class="fa fa-plus"></i></button></div>
            </div>
            <div class="panel-collapse collapse">
                <div class="panel-body">
                    <?php if ($this->session->flashdata('resultContactspage')) { ?>
                        <div class="alert alert-info"><?= $this->session->flashdata('resultContactspage') ?></div>
                    <?php } ?>
                    <form method="POST" action="">
                        <div class="form-group">
                            <textarea name="contactsPage" id="contacts-page"><?= $contactsPage ?></textarea></div>
                        <div class="form-group">
                            <button class="btn btn-default" value="" placeholder="Find product.." type="submit">
                                Update <i class="fa fa-pencil-square-o" aria-hidden="true"></i>
                            </button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
        <script>
            CKEDITOR.replace('contacts-page');
            CKEDITOR.config.entities = false;
        </script>
    </div>
</div>
<script src="<?= base_url('assets/js/bootstrap-toggle.min.js') ?>"></script>
<script>
    $(".panel-heading").on("click", function(e){
        var ele = $(e.target).closest(".panel");
        if($(ele).find(".panel-collapse").hasClass("in")) {$(ele).find(".panel-collapse").removeClass("in"); $(ele).find(".panel-heading .fa").addClass("fa-plus").removeClass("fa-minus");}
        else {$(ele).find(".panel-collapse").addClass("in"); $(ele).find(".panel-heading .fa").removeClass("fa-plus").addClass("fa-minus");}
    })
</script>
<?php } else { echo "<h1>404</h1><h3>Page not  found</h3>"; } ?>