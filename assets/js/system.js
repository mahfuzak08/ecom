/**
 * Price formater
 */
function price_formate(val){
    if(val != ""){
        val = val = val.toLocaleString();
        if(langabbr == 'bn'){
            val = val.replace(/1/g, "১");
            val = val.replace(/2/g, "২");
            val = val.replace(/3/g, "৩");
            val = val.replace(/4/g, "৪");
            val = val.replace(/5/g, "৫");
            val = val.replace(/6/g, "৬");
            val = val.replace(/7/g, "৭");
            val = val.replace(/8/g, "৮");
            val = val.replace(/9/g, "৯");
            val = val.replace(/0/g, "০");
        }
    }
    else{ 
        val = 0;
    }
    return val;
}

/* 
 * There are functions who needs to load in every template.
 * Shopping cart managing is here and etc.
 */

function setCookie(cname, cvalue='', exdays=1) {
    var d = new Date();
    d.setTime(d.getTime() + (exdays * 24 * 60 * 60 * 1000));
    var expires = "expires=" + d.toUTCString();
    document.cookie = cname + "=" + cvalue + ";" + expires + ";path=/";
}

function getCookie(cname){
    var name = cname + "=";
    var decodedCookie = decodeURIComponent(document.cookie);
    var ca = decodedCookie.split(';');
    for (var i = 0; i < ca.length; i++) {
        var c = ca[i];
        while (c.charAt(0) == ' ') {
            c = c.substring(1);
        }
        if (c.indexOf(name) == 0) {
            return c.substring(name.length, c.length);
        }
    }
    return "";
}
function reverse(s){
    return s.split("").reverse().join("");
}
// Shopping Cart Manager
function getCart()
{
    if(getCookie('shopping_cart') != ""){
        return JSON.parse(getCookie('shopping_cart'));
    }
    else 
        return [];
}
function setCart(cart)
{
    setCookie('shopping_cart', JSON.stringify(cart));
}
function removeCart()
{
    setCookie('shopping_cart');
}
function manageCart(type, data){
    console.log(74, data);
    var cart = getCart();
    if(type == 'add'){
        if(data.size != 'N' && data.size != ''){
            let a = data.size.split(";");
            data.size = a[0].split("x")[0];
        }else{
            data.size = 0;
        }
		data.qty = data.qty !== undefined ? data.qty : 1;
        var item = [window.atob(data.id), data.title, data.url, data.img, data.price, data.size, data.qty];
        if(cart.length > 0){
            var n = cart.length;
            for(var i=0; i<n; i++){
                // console.log(cart[i].id, item[0]);
                if(cart[i].id == item[0]){
					console.log("old old", item[6]);
					cart[i].qty = parseInt(cart[i].qty) + parseInt(item[6]);
                    break;
                }
                if(cart.length == i+1){
					console.log("old new", item[6]);
                    cart.push({"id":item[0], "name":item[1], "url":item[2], "img":item[3], "qty": parseInt(item[6]), "price": item[4], "size": item[5]});
                }
            }
        }
        else{
			console.log("new new", item[6]);
            cart.push({"id":item[0], "name":item[1], "url":item[2], "img":item[3], "qty": parseInt(item[6]), "price": item[4], "size": item[5]});
        }
    }
    else if(type == 'remove'){
        var item = window.atob(data).split("@");
        if(cart.length > 0){
            var n = cart.length;
            for(var i=0; i<n; i++){
                if(cart[i].id == item[0]){
                    if(cart[i].qty > 1 && cart[i].qty != item[1])
                        cart[i].qty -= item[1];
                    else{
                        cart.splice(i, 1);
                    }
                    
                    break;
                }
            }
        }
    }
    else if(type == 'size_update'){
        var item = window.atob(data).split("@");
        if(cart.length > 0){
            var n = cart.length;
            for(var i=0; i<n; i++){
                if(cart[i].id == item[0]){
                    cart[i].size = item[1];
                    break;
                }
            }
        }
    }
    if(cart.length > 0) setCart(cart);
    else clearCart();
    $(".sumOfItems").text(cart.length);

    // reload the shoping cart/ checkout page
    if($('.table-products').is(':visible')){
        location.reload();
    }
    // reload the product details page
    if($('#view-product').is(':visible') && $('#view-product').attr('data-id') == item[0]){
        location.reload();
    }
}

function drawCart(){
	$("#popupcart").html("");
	var baseurl = path = window.location.protocol + "//" + window.location.host + "/";
	if(langabbr != 'en') baseurl+=langabbr+"/";
	var cart = getCart();
	var total = 0;
	var html = "";
	if(cart.length > 0){
		$("#popupcart").html("");
		$(".sumOfItems").text(cart.length);
		html += '<li class="cleaner text-right">';
		html +=     '<a href="javascript:void(0);" class="btn-blue-round" onclick="clearCart()">' + lang.clear_all + '</a>';
		html += '</li>';
		html += '<li class="divider"></li>';
		for(var i=0; i<cart.length; i++) {
			html += '<li class="shop-item" data-artticle-id="'+ cart[i].id +'">';
			html +=     '<span class="num_added hidden">'+ cart[i].id +'</span>';
			html +=     '<div class="item">';
			html +=         '<div class="item-in">';
			html +=             '<div class="left-side">';
			html +=                 '<img src="'+ path + 'attachments/'+ SHOP_DIR +'/shop_images/' + cart[i].img +'" onerror="javascript:this.src=\'attachments/no-image.png\'" alt="" />';
			html +=             '</div>';
			html +=             '<div class="right-side">';
			html +=                 '<a href="'+ baseurl + cart[i].url +'" class="item-info">';
			html +=                 '<span>'+ cart[i].name +'</span>';
			html +=                 '<span class="prices">';
			html +=                     '<span class="num-added-single">'+ price_formate(cart[i].qty) +
										'</span> x <span class="price-single">'+ price_formate(cart[i].price) +
										'</span> = <span class="sum-price-single">' + price_formate(cart[i].qty * cart[i].price) +
										'</span>';
			html +=                 '</span>';
			html +=                 '<span class="currency"> BDT</span>';
			html +=                 '</a>';
			html +=             '</div>';
			html +=         '</div>';
			html +=         '<div class="item-x-absolute">';
			html +=             '<button class="btn btn-xs btn-danger pull-right" onclick="removeProduct(\''+ window.btoa(cart[i].id +'@'+ cart[i].qty) +'\')">x</button>';
			html +=         '</div>';
			html +=     '</div>';
			html += '</li>';
		
			total += (Number(cart[i].qty) * Number(cart[i].price));
		}
		
		html += '<li class="divider"></li>';
		html += '<li class="text-center">';
		html +=     '<a class="go-checkout btn btn-default btn-sm" href="'+baseurl+'checkout">';
		html +=         '<i class="fa fa-check"></i> '+ lang.checkout +' - <span class="finalSum">'+ price_formate(total) +'</span> BDT';
		html +=     '</a>';
		html += '</li>';
	}
	else{
		html += '<li class="text-center">' + lang.no_products + '</li>';
	}
	$("#popupcart").html(html);
}
function removeProduct(id) {
    manageCart('remove', id);
	$("#popupcart").hide();
}
function update_item_size(data){
    manageCart('size_update', data);
}
function clearCart() {
    // $.ajax({type: "POST", url: variable.clearShoppingCartUrl});
    removeCart();
    $('ul.dropdown-cart').empty();
    $('ul.dropdown-cart').append('<li class="text-center">' + lang.no_products + '</li>');
    $('.sumOfItems').text(0);
    ShowNotificator('alert-info', lang.cleared_cart);
    if($('.table-products').is(':visible') || $('#view-product').is(':visible')){
        location.reload();
    }
	else
		$("#popupcart").hide();
}
$(".size").click(function(){
    if($("#view-product").is(":visible")){
        $(".size").removeClass("select");
        $(this).addClass("select");
        $("a.add2cart").attr("data-size", Number($(this).text()));
        $("a.buy-now").attr("data-size", Number($(this).text()));
    }
});
$("#openpopupcart").click(function(){
	console.log(230, $("#popupcart").is(":visible"));
    if(! $("#popupcart").is(":visible")){
        drawCart();
		$("#popupcart").show();
    }else{
		$("#popupcart").hide();
	}
})
$('a.add2cart').click(function () {
    var data = this.dataset;
    // console.log(174, data);
    manageCart('add', data);
});
$('a.buy-now').click(function () {
    var data = this.dataset;
    manageCart('add', data);
    var goto_site = $(this).data('goto');
    location.href = goto_site;
});
// Wish Cart Manager
function getWishCart()
{
    if(getCookie('wish_cart') != ""){
        return JSON.parse(getCookie('wish_cart'));
    }
    else 
        return [];
}
function setWishCart(cart)
{
    setCookie('wish_cart', JSON.stringify(cart));
}
function removeWishCart()
{
    setCookie('wish_cart');
}
function manageWishCart(type, data){
    var reload = false;
    var cart = getWishCart();
    if(type == 'add'){
        if(data.size != 'N' && data.size != ''){
            let a = data.size.split(";");
            data.size = a[0].split("x")[0];
        }else{
            data.size = 0;
        }
        var item = [window.atob(data.id), data.title, data.url, data.img, data.price, data.size];
        if(cart.length > 0){
            var n = cart.length;
            for(var i=0; i<n; i++){
                // console.log(cart[i].id, item[0]);
                if(cart[i].id == item[0]){
                    cart[i].qty += 1;
                    break;
                }
                if(cart.length == i+1){
                    cart.push({"id":item[0], "name":item[1], "url":item[2], "img":item[3], "qty":1, "price": item[4], "size": item[5]});
                }
            }
        }
        else{
            cart.push({"id":item[0], "name":item[1], "url":item[2], "img":item[3], "qty":1, "price": item[4], "size": item[5]});
        }
    }
    else if(type == 'remove'){
        var item = window.atob(data).split("@");
        if(cart.length > 0){
            var n = cart.length;
            for(var i=0; i<n; i++){
                if(cart[i].id == item[0]){
                    if(cart[i].qty > 1 && cart[i].qty != item[1])
                        cart[i].qty -= item[1];
                    else{
                        cart.splice(i, 1);
                    }
                    reload = true;
                    break;
                }
            }
        }
    }
    if(cart.length > 0) setWishCart(cart);
    else clearWishCart();
    $(".sumOfWish").text(cart.length);

    // reload the shoping cart/ checkout page
    if($('.table-products').is(':visible') || reload){
        location.reload();
    }


}
function drawWishCartCount(){
    var cart = getWishCart();
    if(cart.length > 0){
        $(".sumOfWish").text(cart.length);
    }
}
function drawWishCart(){
    $("#popupWishCart>ul").html("");
    var baseurl = path = window.location.protocol + "//" + window.location.host + "/";
    if(langabbr != 'en') baseurl+=langabbr+"/";
    var cart = getWishCart();
    var total = 0;
    var html = "";
    if(cart.length > 0){
        $(".sumOfWish").text(cart.length);
        html += '<li class="cleaner text-right">';
        html +=     '<a href="javascript:void(0);" class="btn-blue-round" onclick="clearWishCart()">' + lang.clear_all + '</a>';
        html += '</li>';
        html += '<li class="divider"></li>';
        for(var i=0; i<cart.length; i++) {
            html += '<li class="shop-item" data-artticle-id="'+ cart[i].id +'">';
            html +=     '<span class="num_added hidden">'+ cart[i].id +'</span>';
            html +=     '<div class="item">';
            html +=         '<div class="item-in">';
            html +=             '<div class="left-side">';
            html +=                 '<img src="'+ path + 'attachments/'+ SHOP_DIR+'/shop_images/' + cart[i].img +'" onerror="javascript:this.src=\'attachments/no-image.png\'" alt="" />';
            html +=             '</div>';
            html +=             '<div class="right-side">';
            html +=                 '<a href="'+ baseurl + cart[i].url +'" class="item-info">';
            html +=                 '<span>'+ cart[i].name +'</span>';
            html +=                 '<span class="prices">';
            html +=                     '<span class="num-added-single">'+ price_formate(cart[i].qty) +
                                        '</span> x <span class="price-single">'+ price_formate(cart[i].price) +
                                        '</span> = <span class="sum-price-single">' + price_formate(cart[i].qty * cart[i].price) +
                                        '</span>';
            html +=                 '</span>';
            html +=                 '<span class="currency"> BDT</span>';
            html +=                 '</a>';
            html +=             '</div>';
            html +=         '</div>';
            html +=         '<div class="item-x-absolute">';
            html +=             '<button class="btn btn-xs btn-danger pull-right" onclick="removeWishProduct(\''+ window.btoa(cart[i].id +'@'+ cart[i].qty) +'\')">x</button>';
            html +=         '</div>';
            html +=     '</div>';
            html += '</li>';
        
            total += (Number(cart[i].qty) * Number(cart[i].price));
        }
        
        html += '<li class="divider"></li>';
        html += '<li class="text-center"><br>';
        html +=     '<a class="btn btn-success btn-sm" href="'+baseurl+'wish-list">';
        html +=         '<i class="fa fa-check"></i> '+ lang.wish_lish;
        html +=     '</a>';
        html += '</li>';
    }
    else{
        html += '<li class="text-center">' + lang.no_products + '</li>';
    }
    $("#popupWishCart>ul").html(html);
    $("#popupWishCart").toggle();
}
function removeWishProduct(id) {
    manageWishCart('remove', id);
}
function clearWishCart() {
    // $.ajax({type: "POST", url: variable.clearShoppingCartUrl});
    removeWishCart();
    $('#popupWishCart>ul').empty();
    $('#popupWishCart>ul').append('<li class="text-center">' + lang.no_products + '</li>');
    $('.sumOfWish').text(0);
    ShowNotificator('alert-info', lang.cleared_cart);
    if($('.table-products').is(':visible') || $('#view-product').is(':visible')){
        location.reload();
    }
}
$('a.wish-it').click(function () {
    var data = this.dataset;
    manageWishCart('add', data);
    // var goto_site = $(this).data('goto');
    // location.href = goto_site;
});
$("#openWishCart").click(function(){
    drawWishCart();
})
//DatePicker
if (typeof datepicker !== 'undefined') {
    $('.input-group.date').datepicker({
        format: "dd/mm/yy"
    });
}

//Filters Technique
$('.go-category').click(function () {
    var category = $(this).data('categorie-id');
    $('[name="category"]').val(category);
    submitForm();
});
$('.in-stock').click(function () {
    var in_stock = $(this).data('in-stock');
    $('[name="in_stock"]').val(in_stock);
    submitForm()
});
$(".order").change(function () {
    var order_type = $(this).val();
    var order_to = $(this).data('order-to');
    $('[name="' + order_to + '"]').val(order_type);
    submitForm();
});
$('.brand').click(function () {
    var brand_id = $(this).data('brand-id');
    $('[name="brand_id"]').val(brand_id);
    submitForm()
});
$('.vendor').click(function () {
    var vendor_id = $(this).data('vendor-id');
    $('[name="vendor_id"]').val(vendor_id);
    submitForm()
});
$("#search_in_title").keyup(function () {
    $('[name="search_in_title"]').val($(this).val());
    
    if($(this).val().length > 1){
        $.ajax({
            type:"POST",
            url: $("#bigger-search").attr("action") + "/suggestions",
            data: {str: $(this).val()},
            dataType: "JSON"
        }).done(function(res){
            if(res.length>0){
                $("#suggestions ul").html("");
                var html = "";
                $.each(res, function(k, v){
                    html = "<li onclick='set_title(event)'>"+ v.title +"</li>";
                    $("#suggestions ul").append(html);
                });
                $("#suggestions").show();
            }
        });
    }
    else{
        $("#suggestions ul").html("");
        $("#suggestions").hide();
    }
});
function set_title(event){
    $('[name="search_in_title"]').val($(event.target).text());
    submitForm()
}
$('#clear-form').click(function () {
    $('#search_in_title, [name="search_in_title"]').val('');
    $('#bigger-search .form-control').each(function () {
        $(this).val('');
    });
    submitForm();
});
$('.clear-filter').click(function () { //clear filter in right col
    var type_clear = $(this).data('type-clear');
    $('[name="' + type_clear + '"]').val('');
    submitForm();
});
/*
 * Submit search form in home page
 */
function submitForm() {
    document.getElementById("bigger-search").submit();
}
/*
 * Discount code checker
 */
var is_discounted = false;
function checkDiscountCode() {
    var enteredCode = $('[name="discountCode"]').val();
    $.ajax({
        type: "POST",
        url: variable.discountCodeChecker,
        data: {enteredCode: enteredCode}
    }).done(function (data) {
        if (data == 0) {
            ShowNotificator('alert-danger', lang.discountCodeInvalid);
        } else {
            if (is_discounted == false) {
                var obj = jQuery.parseJSON(data);
                var shipping_cost = parseFloat($('#shipping').val());
                var final_amount_before = parseFloat($('.final-amount').text()) - shipping_cost;
                var discountAmoun;
                if (obj.type == 'percent') {
                    var substract_num = (obj.amount / 100) * final_amount_before;
                    var final_amount = final_amount_before - substract_num;
                    discountAmoun = substract_num;
                }
                if (obj.type == 'float') {
                    var final_amount = final_amount_before - obj.amount;
                    discountAmoun = obj.amount;
                }
                final_amount += shipping_cost;
                $('.final-amount').text(final_amount.toFixed(2));
                $('.final-amount').val(final_amount.toFixed(2));
                $('[name="discountAmount"]').val(discountAmoun);
                $('.discountrow').show();
                $('.discountAmount').text(discountAmoun.toFixed(2));
                is_discounted = true;
            }
        }
    });
}

function manageShoppingCart(action, article_id, reload) {
    var action_error_msg = lang.error_to_cart;
    if (action == 'add') {
        $('.add-to-cart a[data-id="' + article_id + '"] span').hide();
        $('.add-to-cart a[data-id="' + article_id + '"] img').show();
        var action_success_msg = lang.added_to_cart;
    }
    if (action == 'remove') {
        var action_success_msg = lang.remove_from_cart;
    }
    $.ajax({
        type: "POST",
        url: variable.manageShoppingCartUrl,
        data: {article_id: article_id, action: action}
    }).done(function (data) {
        $(".dropdown-cart").empty();
        $(".dropdown-cart").append(data);
        var sum_items = parseInt($('.sumOfItems').text());
        if (action == 'add') {
            $('.sumOfItems').text(sum_items + 1);
        }
        if (action == 'remove') {
            $('.sumOfItems').text(sum_items - 1);
        }
        if (reload == true) {
            location.reload(false);
            return;
        } else if (typeof reload == 'string') {
            location.href = reload;
            return;
        }
        ShowNotificator('alert-info', action_success_msg);
    }).fail(function (err) {
        ShowNotificator('alert-danger', action_error_msg);
    }).always(function () {
        if (action == 'add') {
            $('.add-to-cart a[data-id="' + article_id + '"] span').show();
            $('.add-to-cart a[data-id="' + article_id + '"] img').hide();
        }
    });
}

//Email Subscribe
function checkEmailField() {
    if ($('[name="subscribeEmail"]').val() == '') {
        ShowNotificator('alert-danger', lang.enter_valid_email);
        return;
    }
    document.getElementById("subscribeForm").submit();
}

//Email Subscribe
function checkEmailField() {
    if ($('[name="subscribeEmail"]').val() == '') {
        ShowNotificator('alert-danger', lang.enter_valid_email);
        return;
    }
    document.getElementById("subscribeForm").submit();
}

// Top Notificator
function ShowNotificator(add_class, the_text) {
    $('div#notificator').text(the_text).addClass(add_class).slideDown('slow').delay(3000).slideUp('slow', function () {
        $(this).removeClass(add_class).empty();
    });
}

function send_sms(to, str) {
    console.log(220, str);
    $.ajax({
        type: "POST",
        url: variable.send_sms,
        data: {to: to, str: str}
    }).done(function (data) {
        console.log(data);
        return 1;
    }).fail(function (err) {
        ShowNotificator('alert-danger', 'Sending sms notification error...');
        return 0;
    });
}
if(SHOP_DIR !== 'localhost')
    document.addEventListener('contextmenu', event => event.preventDefault());
// Client modal for more info
$(document).ready(function () {
    drawCart();
    drawWishCartCount();

    $('.more-info').click(function () {
        $('#preview-info-body').empty();
        var order_id = $(this).data('more-info');
        var text = $('#order_id-id-' + order_id).text();
        $("#client-name").empty().append(text);
        var html = $('#order-id-' + order_id).html();
        $("#preview-info-body").append(html);
    });
    
    // $('html, body').animate({
    //     scrollTop: $("#products-side").offset().top
    // }, 2000);
    
    $("#privacy").on("change", function(){
        if(this.checked){
            $("#signup").attr('type', 'submit').removeClass('disable');
        }
        else{
            $("#signup").attr('type', 'button').addClass('disable');
        }
    });
});