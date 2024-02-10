// Bootstrap Confirmation
$('[data-toggle=confirmation]').confirmation({
    rootSelector: '[data-toggle=confirmation]',
    title: lang.are_you_sure,
    btnOkLabel: lang.yes,
    btnCancelLabel: lang.no
});

//Tootip activator
$(function () {
    $('[data-toggle="tooltip"]').tooltip()
});

//xs hidden categories
$("#show-xs-nav").click(function () {
    $("#nav-categories").toggle("slow", function () {
        if ($(this).is(":visible") == true) {
            $("#show-xs-nav .hidde-sp").show();
            $("#show-xs-nav .show-sp").hide();
        } else {
            $("#show-xs-nav .hidde-sp").hide();
            $("#show-xs-nav .show-sp").show();
        }
    });
});

// toggle catagories tree
$('#nav-categories li').click(function(event){
    event.stopImmediatePropagation();
    $(this).closest('li').find('>.children').toggle();
});

// selected li parents display block
$('div.filter-sidebar ul li a.selected').parents('ul').show();
$('div.filter-sidebar ul li a.selected').closest('li').find('>.children').show();
//////////////////////////////////////////////////////////////////////////////////
$(".phoneInput").keyup(function () {
    if($(this).val().length > 1){
        var str= $(this).val();
        $.ajax({
            type:"POST",
            url: $("#phoneInput").attr("data-uri") + "/find_user",
            data: {str: str},
            dataType: "JSON"
        }).done(function(res){
            if(res.length>0){
                $("#user_lists ul").html("");
                var html = "";
                $.each(res, function(k, v){
                    var bg = (k%2)?"#DEDEDE":"#CDCDCD";
                    html = "<li onclick='set_name(event)' style='background:"+ bg +"' data-id='"+ v.id +"' data-name='"+ v.name +"' data-email='"+ v.email +"' data-phone='"+ v.phone +"'>"+ v.phone +" ("+ v.name +")</li>";
                    $("#user_lists ul").append(html);
                });
                $("#user_lists").show();
            }
        }).fail(function(error){
            console.log(error);
        });
    }
    else{
        $("#user_lists ul").html("");
        $("#user_lists").hide();
    }
});
function set_name(event){
    $('#phoneInput').val($(event.target).attr('data-phone'));
    $('#firstNameInput').val($(event.target).attr('data-name'));
    $('#emailAddressInput').val($(event.target).attr('data-email'));
    $('#user_id').val($(event.target).attr('data-id'));
    $("#notesInput").val("Get order by phone");
    $("#user_lists").hide();
}
function toggle_type(){
    if($(".logpass").attr("type") == "password"){
        $(".logpass").attr("type", "text");
        $(".fa-eye").addClass("fa-eye-slash").removeClass("fa-eye");
    }
    else{
        $(".logpass").attr("type", "password");
        $(".fa-eye-slash").addClass("fa-eye").removeClass("fa-eye-slash");
    }
}

$('.product-pic-zoom').zoom();