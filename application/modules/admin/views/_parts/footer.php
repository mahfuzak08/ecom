</div>
</div>
</div>
</div>
<?php if ($this->session->userdata('logged_in')) { ?>
<footer class="col-sm-9 col-md-9 col-lg-10 col-sm-offset-3 col-md-offset-3 col-lg-offset-2">Powered by <a href="http://absoft-bd.com/">ABSoftBD</a></footer>
<?php } ?>
</div>
<?php 
if(!isset($_COOKIE["disnoti"])) {
    $host = str_replace("www.", "", $_SERVER["HTTP_HOST"]);
    $client = $this->Public_model->getClient($host);
    if($client["notification"] !== "<p>no</p>"){
    ?>
    <style>
        #popUpDiv{
            position: absolute;
            max-width: 850px;
            max-height: 563px;
            border: 0px solid #000;
            z-index: 999999;
            top: 50% !important;
            left: 50% !important;
            transform: translate(-50%,-50%);
            width: calc(100% - 30px);
            min-height: calc(100% - 300px);
            background: #FFF;
            border-radius: 4px;
            border: 3px solid #000;
        }
        #popUpDiv .blog {
            padding: 50px;
        }
        #popUpDiv .cbwrap {
            position: absolute;
            right: -15px;
            top: -15px;
        }
        #preloaderbottomPart{
            position: absolute;
            bottom: 0;
            left: 50px;
        }
    </style>
    <div id="popUpDiv" style="position: fixed; top: 86.4375px;">
        <a class="cbwrap" href="javascript:off_layer();">
        <img src="//www.bdjobs.com/images/closebox.png" alt="Close Main popup" title="Close"></a>


        <div class="blog">
            <?= $client["notification"]; ?>
            <!-- <img class="img-responsive" src="http://localhost/tracking_system/assets/bg/wallpaperflare.jpg" alt="Searching Job :: DisAbility is not a barrier" onload="_gaq.push(['_trackEvent', 'bdjobsHomePreloader', 'Impression', 'Sales Ads Pre-Loader_PWD.png ',1.00,true]);"> -->
        </div>
        <div class="blog-content-c" id="preloaderbottomPart">
            <div class="checkbox" id="preloaderbottomPartCheckbox">					
                <input onclick="setPerpageRecord()" id="b2cpreloader" type="checkbox">
                <label for="b2cpreloader" style="color:  black  ;"> Don't show again </label>
            </div>
        </div>
    </div>
    <script>
        function setPerpageRecord(){
            const d = new Date();
            d.setTime(d.getTime() + (24*60*60*1000));
            let expires = "expires="+ d.toUTCString();
            document.cookie = "disnoti=oneday; expires="+expires;
        }
    </script>
    <?php } ?>
<?php } ?>
<!-- Modal Calculator -->
<div class="modal fade" id="modalCalculator" tabindex="-1" role="dialog" aria-labelledby="myModalLabel">
    <div class="modal-dialog" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
                <h4 class="modal-title" id="myModalLabel">Calculator</h4>
            </div>
            <div class="modal-body" id="calculator">
                <div class="hero-unit" id="calculator-wrapper">
                    <div class="row">
                        <div class="col-sm-8">
                            <div id="calculator-screen" class="form-control"></div>
                        </div>
                        <div class="col-sm-1">
                            <div class="visible-xs">
                                =
                            </div>
                            <div class="hidden-xs">
                                =
                            </div>
                        </div>
                        <div class="col-sm-3">
                            <div id="calculator-result"  class="form-control">0</div>
                        </div>
                    </div>
                </div>
                <div class="well">
                    <div id="calc-board">
                        <div class="row">
                            <a href="javascript:void(0);" class="btn btn-default" data-constant="SIN" data-key="115">sin</a>
                            <a href="javascript:void(0);" class="btn btn-default" data-constant="COS" data-key="99">cos</a>
                            <a href="javascript:void(0);" class="btn btn-default" data-constant="MOD" data-key="109">md</a>
                            <a href="javascript:void(0);" class="btn btn-danger" data-method="reset" data-key="8">C</a>
                        </div>
                        <div class="row">
                            <a href="javascript:void(0);" class="btn btn-default" data-key="55">7</a>
                            <a href="javascript:void(0);" class="btn btn-default" data-key="56">8</a>
                            <a href="javascript:void(0);" class="btn btn-default" data-key="57">9</a>
                            <a href="javascript:void(0);" class="btn btn-default" data-constant="BRO" data-key="40">(</a>
                            <a href="javascript:void(0);" class="btn btn-default" data-constant="BRC" data-key="41">)</a>
                        </div>
                        <div class="row">
                            <a href="javascript:void(0);" class="btn btn-default" data-key="52">4</a>
                            <a href="javascript:void(0);" class="btn btn-default" data-key="53">5</a>
                            <a href="javascript:void(0);" class="btn btn-default" data-key="54">6</a>
                            <a href="javascript:void(0);" class="btn btn-default" data-constant="MIN" data-key="45">-</a>
                            <a href="javascript:void(0);" class="btn btn-default" data-constant="SUM" data-key="43">+</a>
                        </div>
                        <div class="row">
                            <a href="javascript:void(0);" class="btn btn-default" data-key="49">1</a>
                            <a href="javascript:void(0);" class="btn btn-default" data-key="50">2</a>
                            <a href="javascript:void(0);" class="btn btn-default" data-key="51">3</a>
                            <a href="javascript:void(0);" class="btn btn-default" data-constant="DIV" data-key="47">/</a>
                            <a href="javascript:void(0);" class="btn btn-default" data-constant="MULT" data-key="42">*</a>
                        </div>
                        <div class="row">
                            <a href="javascript:void(0);" class="btn btn-default" data-key="46">.</a>
                            <a href="javascript:void(0);" class="btn btn-default" data-key="48">0</a>
                            <a href="javascript:void(0);" class="btn btn-default" data-constant="PROC" data-key="37">%</a>
                            <a href="javascript:void(0);" class="btn btn-primary" data-method="calculate" data-key="61">=</a>
                        </div>
                    </div>
                </div>
                <div class="well">
                    <legend>History</legend>
                    <div id="calc-panel">
                        <div id="calc-history">
                            <ol id="calc-history-list"></ol>
                        </div>
                    </div>
                </div>
                <hr>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-default" data-dismiss="modal">Close</button>
            </div>
        </div>
    </div>
</div>
<script>
$(document).ready(function () {
    $("ul.clild").each(function(){
        if($(this).find("li").length == 0)
            $(this).parent().hide();  
    });
});
function off_layer(){
    document.getElementById("popUpDiv").style.display = "none";
}
</script>
<script src="<?= base_url('assets/bootstrap-select-1.12.1/js/bootstrap-select.min.js') ?>"></script>
<script src="<?= base_url('assets/js/bootstrap.min.js') ?>"></script>
<script src="<?= base_url('assets/js/bootbox.min.js') ?>"></script>
<script src="<?= base_url('assets/js/zxcvbn.js') ?>"></script>
<script src="<?= base_url('assets/js/zxcvbn_bootstrap3.js') ?>"></script>
<script src="<?= base_url('assets/js/pGenerator.jquery.js') ?>"></script>
<script>
    // $(window).on('resize', function () {
    //     if($(window).width() < 768)
    //         $('.container-fluid .navbar-default').removeClass('left-side');
    //     else
    //         $('.container-fluid .navbar-default').addClass('left-side');
    // });
    var urls = {
        changePass: '<?= base_url('admin/changePass') ?>',
        editShopCategorie: '<?= base_url('admin/editshopcategorie') ?>',
        changeTextualPageStatus: '<?= base_url('admin/changePageStatus') ?>',
        removeSecondaryImage: '<?= base_url('admin/removeSecondaryImage') ?>',
        productstatusChange: '<?= base_url('admin/productstatusChange') ?>',
        productsOrderBy: '<?= base_url('admin/products?orderby=') ?>',
        productStatusChange: '<?= base_url('admin/productStatusChange') ?>',
        changeOrdersOrderStatus: '<?= base_url('admin/changeOrdersOrderStatus') ?>',
        ordersOrderBy: '<?= base_url('admin/orders?order_by=') ?>',
        uploadOthersImages: '<?= base_url('admin/uploadOthersImages') ?>',
        loadOthersImages: '<?= base_url('admin/loadOthersImages') ?>',
        editPositionCategorie: '<?= base_url('admin/changePosition') ?>',
        verified: '<?= base_url('admin/verified') ?>',
        send_sms: "<?= base_url('send_sms') ?>"
    };
</script>
<script src="<?= base_url('assets/js/mine_admin.js') ?>"></script>
<script src="<?= base_url('assets/js/print.js') ?>"></script>
<link rel="stylesheet" type="text/css" href="<?= base_url('assets/DataTables/datatables.min.css'); ?>"/>
<link rel="stylesheet" type="text/css" href="<?= base_url('assets/DataTables/buttons.dataTables.min.css'); ?>"/>
<script type="text/javascript" src="<?= base_url('assets/DataTables/datatables.min.js'); ?>"></script>
<script type="text/javascript" src="<?= base_url('assets/DataTables/dataTables.buttons.min.js'); ?>"></script>
<script type="text/javascript" src="<?= base_url('assets/DataTables/jszip.min.js'); ?>"></script>
<script type="text/javascript" src="<?= base_url('assets/DataTables/pdfmake.min.js'); ?>"></script>
<script type="text/javascript" src="<?= base_url('assets/DataTables/vfs_fonts.js'); ?>"></script>
<script type="text/javascript" src="<?= base_url('assets/DataTables/buttons.html5.min.js'); ?>"></script>
<script type="text/javascript" src="<?= base_url('assets/DataTables/buttons.print.min.js'); ?>"></script>
<script type="text/javascript" src="<?= base_url('assets/DataTables/sum.js'); ?>"></script>

<script>
    // setInterval(function(){
    //     window.location.reload();
    // }, 120000);
</script>
</body>
</html>