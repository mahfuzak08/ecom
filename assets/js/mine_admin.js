if ($(window).width() > 767) {
    var left_side_width = $('.left-side').width();
    $("#brand").css("width", left_side_width - 1);
}

$(window).resize(function () {
    if ($(window).width() > 767) {
        var left_side_width = $('.left-side').width();
        $("#brand").css("width", left_side_width - 1);
    }
});

$(document).ready(function () {
    $(".h-settings").click(function () {
        $(".settings").toggle("slow", function () {
            $("i.fa.fa-cogs").addClass('fa-spin');
            if ($(".settings").is(':visible')) {
                $("i.fa.fa-cogs").addClass('fa-spin');
            } else {
                $("i.fa.fa-cogs").removeClass('fa-spin');
            }
        });
    });
});

$("#dev-zone").click(function () {
    $(".toggle-dev").slideToggle("slow");
});

$('.btn-publish').click(function (e) {
    var shop_category = $('[name="shop_categorie"]').val();
    if (shop_category == null) {
        e.preventDefault();
        alert('There is no create and selected shop category!');
    }
});

$("a.confirm-delete").click(function (e) {
    e.preventDefault();
    var lHref = $(this).attr('href');
    var str = $(e.target).hasClass("return_item") ? "Are you sure, you want to return this item(s)?" : "Are you sure want to delete?";
    bootbox.confirm({
        message: str,
        buttons: {
            confirm: {
                label: 'Yes',
                className: 'btn-success'
            },
            cancel: {
                label: 'No',
                className: 'btn-danger'
            }
        },
        callback: function (result) {
            if (result) {
                window.location.href = lHref;
            }
        }
    });
});

$("a.confirm-save").click(function (e) {
    e.preventDefault();
    var formId = $(this).data('form-id');
    bootbox.confirm({
        message: "Are you sure want to save?",
        buttons: {
            confirm: {
                label: 'Yes',
                className: 'btn-success'
            },
            cancel: {
                label: 'No',
                className: 'btn-danger'
            }
        },
        callback: function (result) {
            if (result) {
                document.getElementById(formId).submit();
            }
        }
    });
});

$("button.confirm-btn").click(function (e) {
    e.preventDefault();
    var formId = $(this).data('form-id');
    var msg = $(this).data('msg');
    bootbox.confirm({
        message: msg,
        buttons: {
            confirm: {
                label: 'Yes',
                className: 'btn-success'
            },
            cancel: {
                label: 'No',
                className: 'btn-danger'
            }
        },
        callback: function (result) {
            if (result) {
                document.getElementById(formId).submit();
            }
        }
    });
});

$(function () {
    $('[data-toggle="tooltip"]').tooltip()
});

//xs hidden categories
$("#show-xs-nav").click(function () {
    $(".sidebar-menu").toggle("slow", function () {
        if ($(this).is(":visible") == true) {
            $("#show-xs-nav .hidde-sp").show();
            $("#show-xs-nav .show-sp").hide();
        } else {
            $("#show-xs-nav .hidde-sp").hide();
            $("#show-xs-nav .show-sp").show();
        }
    });
});

// Calculator starts here
var Calculator = {

    results_id: 'calculator-result',
    results_value: '0',
    memory_id: 'calculator-screen',
    memory_value: '',
    history_id: 'calc-history-list',
    history_value: [],

    SUM: ' + ',
    MIN: ' - ',
    DIV: ' / ',
    MULT: ' * ',
    PROC: '%',
    SIN: 'sin(',
    COS: 'cos(',
    MOD: ' mod ',
    BRO: '(',
    BRC: ')',

    calculate: function () {
        this.history_value.push(this.memory_value);
        this.results_value = this.engine.exec(this.memory_value);
        this.add_to_history();
        this.refresh();
    },

    put: function (value) {
        this.memory_value += value;
        this.update_memory();
    },

    reset: function () {
        this.memory_value = '';
        this.results_value = '0';
        this.clear_history();
        this.refresh();
    },

    refresh: function () {
        this.update_result();
        this.update_memory();
    },

    update_result: function () {
        document.getElementById(this.results_id).innerHTML = this.results_value;
    },

    update_memory: function () {
        document.getElementById(this.memory_id).innerHTML = this.memory_value;
    },

    add_to_history: function () {
        if (isNaN(this.results_value) == false) {
            var div = document.createElement('li');
            div.innerHTML = this.memory_value + ' = ' + this.results_value;

            var tag = document.getElementById(this.history_id);
            tag.insertBefore(div, tag.firstChild);
        }
    },

    clear_history: function () {
        $('#' + this.history_id + '> li').remove();
    },

    engine: {
        exec: function (value) {
            try {
                return eval(this.parse(value))
            } catch (e) {
                return e
            }
        },

        parse: function (value) {
            if (value != null && value != '') {
                value = this.replaceFun(value, Calculator.PROC, '/100');
                value = this.replaceFun(value, Calculator.MOD, '%');
                value = this.addSequence(value, Calculator.PROC);

                value = this.replaceFun(value, 'sin', 'Math.sin');
                value = this.replaceFun(value, 'cos', 'Math.cos');
                return value;
            } else
                return '0';
        },

        replaceFun: function (txt, reg, fun) {
            return txt.replace(new RegExp(reg, 'g'), fun);
        },

        addSequence: function (txt, fun) {
            var list = txt.split(fun);
            var line = '';

            for (var nr in list) {
                if (line != '') {
                    line = '(' + line + ')' + fun + '(' + list[nr] + ')';
                } else {
                    line = list[nr];
                }
            }
            return line;
        }
    }
}

$(document).ready(function () {
    $("#calculator .btn").click(function (e) {
        e.preventDefault();
        if ($(this).data('constant') != undefined) {
            return Calculator.put(Calculator[$(this).data('constant')]);
        }
        if ($(this).data('method') != undefined) {
            return Calculator[$(this).data('method')]();
        }
        return Calculator.put($(this).html());
    });
});
// Calculator code finish here

// Password strenght starts here
$(document).ready(function () {
    //PassStrength 
    checkPass();
    $(".new-pass-field").on('keyup', function () {
        checkPass();
    });

    //PassGenerator
    $('.generate-pwd').pGenerator({
        'bind': 'click',
        'passwordLength': 9,
        'uppercase': true,
        'lowercase': true,
        'numbers': true,
        'specialChars': false,
        'onPasswordGenerated': function (generatedPassword) {
            $(".new-pass-field").val(generatedPassword);
            checkPass();
        }
    });
});

//toggle in settings
$(document).ready(function () {
    $('.toggle-changer').change(function () {
        var myValue;
        if ($(this).prop('checked') == false) {
            myValue = '0';
        } else {
            myValue = '1';
        }
        var myData = $(this).data('for-field');
        $('[name="' + myData + '"]').val(myValue);
    });
});

//themes in settings
$(document).ready(function () {
    $('.select-law-theme').click(function () {
        $('.ok').hide();
        $(this).children('.ok').show();
        var theme_name = $(this).data('law-theme');
        $('[name="theme"]').val(theme_name);
    });
});

//templates chooser
$('.choose-template').click(function () {
    var template_name = $(this).data('template-name');
    $('#saveTemplate .template-name').val(template_name);
});

//Edit Shop Categories
var indicEditCategorie;
var forIdEditCategorie;
var abbrEditCategorie;
$('.editCategorie').click(function () {
    indicEditCategorie = $(this).data('indic');
    forIdEditCategorie = $(this).data('for-id');
    abbrEditCategorie = $(this).data('abbr');
    var position = $(this).position();
    $('#categorieEditor').css({top: position.top, left: position.left, display: 'block'});
    $('#categorieEditor input').val($('#indic-' + indicEditCategorie).text());
});
$('.closeEditCategorie').click(function () {
    $('#categorieEditor').hide();
});
$('.saveEditCategorie').click(function () {
    $('#categorieEditor .noSaveEdit').hide();
    $('#categorieEditor .yesSaveEdit').css({display: 'inline-block'});
    var newValueFromEdit = $('[name="new_value"]').val();
    $.ajax({
        type: "POST",
        url: urls.editShopCategorie,
        data: {for_id: forIdEditCategorie, abbr: abbrEditCategorie, type: 'shop_categorie', name: newValueFromEdit}
    }).done(function (data) {
        $('#categorieEditor .noSaveEdit').show();
        $('#categorieEditor .yesSaveEdit').hide();
        $('#categorieEditor').hide();
        $('#indic-' + indicEditCategorie).text(newValueFromEdit);
    });
});

$('.editCategorieSub').click(function () {
    var position = $(this).position();
    var subForId = $(this).data('sub-for-id');
    $('[name="editSubId"]').val(subForId);
    $('#categorieSubEdit').css({top: position.top, left: position.left, display: 'block'});
});
$('[name="newSubIs"]').change(function () {
    $('#categorieEditSubChanger').submit();
});

// textual pages
function changeTextualPageStatus(id) {
    var myI = $('li[data-id="' + id + '"] i');
    if (myI.hasClass('red')) {
        myI.removeClass('red').addClass('green');
        var status = 1;
    } else if (myI.hasClass('green')) {
        myI.removeClass('green').addClass('red');
        var status = 0;
    }
    $.post(urls.changeTextualPageStatus, {id: id, status: status}, function (data) {
        if (data == '1') {
            return true;
        }
        return false;
    });
}

//products publish
function removeSecondaryProductImage(image, folder, container) {
    $.ajax({
        type: "POST",
        url: urls.removeSecondaryImage,
        data: {image: image, folder: folder}
    }).done(function (data) {
        $('#image-container-' + container).remove();
    });
}

$('#modalConvertor').on('hidden.bs.modal', function (e) {
    $("#new_currency").empty();
});


$(".showSliderDescrption").click(function () {
    var desc_id = $(this).data('descr');
    $("#theSliderDescrption-" + desc_id).slideToggle("slow", function () {});
});

// Products
$(".change-products-form").change(function () {
    $('#searchProductsForm').submit();
});

$(".changeOrder").change(function () {
    window.location.href = urls.ordersOrderBy + $(this).val();
});

$(document).ready(function () {
    $('.more-info').click(function () {
        $('#preview-info-body').empty();
        var order_id = $(this).data('more-info');
        var text = $('#order_id-id-' + order_id).text();
        $("#client-name").empty().append(text);
        var html = $('#order-id-' + order_id).html();
        $("#preview-info-body").append(html);
    });
    $('.bill-info').click(function(){
        $('#bill-info-body').empty();
        var order_id = $(this).data('more-info');
        var text = $('#order_id-id-' + order_id).text();
        $("#bill-id").empty().append(text);
        var html = $('#order-id-bill-' + order_id).html();
        $("#bill-info-body").append(html);
    });
});

// Admin Login
var username_login = $("input[name=username]");
var password_login = $("input[name=password]");
$('button[type="submit"]').click(function (e) {
    if (username_login.val() == "" || password_login.val() == "") {
        e.preventDefault();
        $("#output").addClass("alert alert-danger animated fadeInUp").html("Please.. enter all fields ;)");
    }
});

// Upload More Images on publish product
$('.finish-upload').click(function () {
    $('.finish-upload .finish-text').hide();
    $('.finish-upload .loadUploadOthers').show();
    var someFormElement = document.getElementById('uploadImagesForm');
    var formData = new FormData(someFormElement);
    $.ajax({
        url: urls.uploadOthersImages,
        type: "POST",
        data: formData,
        contentType: false,
        cache: false,
        processData: false,
        success: function (data)
        {
            $('.finish-upload .finish-text').show();
            $('.finish-upload .loadUploadOthers').hide();
            reloadOthersImagesContainer();
            $('#modalMoreImages').modal('hide');
            document.getElementById("uploadImagesForm").reset();
        }
    });
});

// Edit Categories Positions
var editPositionField;
$('.editPosition').click(function () {
    var editId = $(this).data('position-for-id');
    editPositionField = editId;
    $('[name="positionEditId"]').val(editId);
    var myPosition = $(this).data('my-position');
    var position = $(this).position();
    $('#positionEditor').css({top: position.top, left: position.left, display: 'block'});
    $('[name="new_position"]').val(myPosition);
});

$('.closePositionCategorie').click(function () {
    $('#positionEditor').hide();
});

$('.savePositionCategorie').click(function () {
    var new_val = $('[name="new_position"]').val();
    var editId = $('[name="positionEditId"]').val();
    $.ajax({
        type: "POST",
        url: urls.editPositionCategorie,
        data: {editid: editId, new_pos: new_val}
    }).done(function (data) {
        $('#positionEditor').hide();
        $('#position-' + editPositionField).text(new_val);
    });
});

$('.locale-change').click(function () {
    var toLocale = $(this).data('locale-change');
    $('.locale-container').hide();
    $('.locale-container-' + toLocale).show();
    $('.locale-change').removeClass('active');
    $(this).addClass('active');
});

function reloadOthersImagesContainer() {
    $('.others-images-container').empty();
    $('.others-images-container').load(urls.loadOthersImages, {"folder": $('[name="folder"]').val()});
}

// Orders
function changeOrdersOrderStatus(id, to_status) {
    $('[data-action-id="' + id + '"]>b').text('Please wait...');
    // $('.actiontbl' + id).hide();
    $.post(urls.changeOrdersOrderStatus, {the_id: id, to_status: to_status}, function (data) {
        console.log(data);
        if (data == '1') {
            if (to_status == 0) {
                $('[data-action-id="' + id + '"]>b').text('No processed');
                $('[data-action-id="' + id + '"]').removeClass().addClass('bg-danger text-center');
            }
            if (to_status == 1) {
                $('[data-action-id="' + id + '"]>b').text('Processed');
                $('[data-action-id="' + id + '"]').removeClass().addClass('bg-success  text-center');
            }
            if (to_status == 2) {
                $('[data-action-id="' + id + '"]>b').text('Rejected');
                $('[data-action-id="' + id + '"]').removeClass().addClass('bg-warning  text-center');
            }
            if (to_status == 3) {
                $('[data-action-id="' + id + '"]>b').text('Processing');
                $('[data-action-id="' + id + '"]').removeClass().addClass('bg-default  text-center');
            }
            // $('#new-order-alert-' + id).remove();
            $('[data-action-id="' + id + '"]').closest('tr').css('background', 'unset');
        } else {
            alert('Error with status change. Please check logs!');
        }
    });
}

function changeProductStatus(id) {
    var to_status = $("#to-status").val();
    $.ajax({
        type: "POST",
        url: urls.productStatusChange,
        data: {id: id, to_status: to_status}
    }).done(function (data) {
        if (data == '1') {
            if (to_status == 1) {
                $('[data-article-id="' + id + '"] .staus-is').text('Visible');
                $('[data-article-id="' + id + '"] .status-is-icon').html('<i class="fa fa-unlock"></i>');
                $('[data-article-id="' + id + '"]').removeClass('invisible-status');
                $("#to-status").val(0);
            } else {
                $('[data-article-id="' + id + '"] .staus-is').text('Invisible');
                $('[data-article-id="' + id + '"]').addClass('invisible-status');
                $('[data-article-id="' + id + '"] .status-is-icon').html('<i class="fa fa-lock"></i>');
                $("#to-status").val(1)
            }
        } else {
            alert('Error change status!');
        }
    });
}

function changePass() {
    var new_pass = $('[name="new_pass"]').val();
    if (jQuery.trim(new_pass).length > 3) {
        $.ajax({
            type: "POST",
            url: urls.changePass,
            data: {new_pass: new_pass}
        }).done(function (data) {
            if (data == '1') {
                $("#pass_result").fadeIn(500).delay(2000).fadeOut(500);
            } else {
                alert('Password cant change!');
            }
        });
    } else {
        alert('Too short pass!');
    }
}

///////////////////////////////////////////////////////////////////////////////
function toggle_div(id){
    $(id).toggle("slow");
}

$(".parent").on("click", function(event){
    // $('ul.sidebar-menu.clild li').hide();
    $(this).closest('li').find('li').toggle('slow');
    $(this).closest('li').find('.fa-angle-left, .fa-angle-down').toggleClass('fa-angle-left, fa-angle-down');
});

function verified(e, to) {
    // $.ajax({
    //     type: "POST",
    //     url: urls.send_sms,
    //     data: {to: to, str: "Your otp is 1010"}
    // }).done(function (data) {
    //     $(e.target).removeClass('fa-recycle').addClass('fa-check');
    //     $(e.target).attr('title', 'Verified').attr('onclick', '');
    // }).fail(function (err) {
    //     alert('Manually customer verification error...');
    //     return 0;
    // });
    $.ajax({
        type: "POST",
        url: urls.verified,
        data: {to: to}
    }).done(function (data) {
        $(e.target).removeClass('fa-recycle').addClass('fa-check');
        $(e.target).attr('title', 'Verified').attr('onclick', '');
    }).fail(function (err) {
        alert('Manually customer verification error...');
        return 0;
    });
}

// $("#product_name").keyup(function () {
//     if($(this).val().length > 1){
//         var str= $(this).val();
//         $.ajax({
//             type:"POST",
//             url: $("#modalMakeOrder form").attr("action") + "/find_product",
//             data: {str: str},
//             dataType: "JSON"
//         }).done(function(res){
//             if(res.length>0){
//                 $("#product_lists ul").html("");
//                 var html = "";
//                 $.each(res, function(k, v){
//                     var bg = (k%2)?"#DEDEDE":"#CDCDCD";
//                     html = "<li onclick='set_product(event)' data-id='"+ v.id +"' data-title='"+ v.title +"' data-image='"+ v.image +"'  data-price='"+ v.price +"' data-vendor_name='"+ v.vendor_name +"'>"+ v.title + " = Tk " + v.price + " (" + v.vendor_name +")</li>";
//                     $("#product_lists ul").append(html);
//                 });
//                 $("#product_lists").show();
//             }
//         }).fail(function(error){
//             console.log(error);
//         });
//     }
//     else{
//         $("#product_lists ul").html("");
//         $("#product_lists").hide();
//     }
// });
// var temp_cart = [];
// function set_product(event){
//     event.stopImmediatePropagation();
//     var ele = $(event.target);
//     temp_cart.push({
//         id: ele.data('id'),
//         title: ele.data('title'),
//         image: ele.data('image'),
//         price: ele.data('price'),
//         qty: 1,
//         vendor_name: ele.data('vendor_name')
//     });
//     update_shoping_cart();
//     $("#product_name").val("");
//     $("#product_lists ul").html("");
// }
// function update_shoping_cart(){
//     $("#shoping_cart").html("<table></table>");
//     $.each(temp_cart, function(k, v){
//         var html = '<tr><td>'+v.title+'<br>';
//         html    += v.vendor_name +'</td>';
//         html    += '<td>'+v.qty+'</td>';
//         html    += '<td>'+v.price+'</td>';
//         html    += '<td>'+ (v.price * v.qty) +'</td></tr>';
//         $("#shoping_cart table").append(html);
//     });
// }
