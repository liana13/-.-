$(document).ready(function(){
    document.cookie = 'datephpvar=25';
    $('.sort-block .dropdown-menu .active').closest('.dropdown').find('.dropdown-toggle').html($('.sort-block .dropdown-menu .active a').html());
    $('.sort-block .dropdown-menu .active').closest('.dropdown').find('.dropdown-toggle').addClass('text-primary');
    if ($('.sort-block .dropdown-menu .active').hasClass('asc')) {
        $('.sort-block .dropdown-menu .active').closest('.dropdown').find('.dropdown-toggle').addClass('asc');
    } else if ($('.sort-block .dropdown-menu .active').hasClass('desc')) {
        $('.sort-block .dropdown-menu .active').closest('.dropdown').find('.dropdown-toggle').addClass('desc');
    }
    $(".toggle-adminform").click(function() {
        $('.contact-admin').slideToggle();
    });
    $('#object-form .form-control').selectmenu();
    $('#objectsitesearch-cat_id').on("selectmenuselect", function(event, ui){
        $('#country').val(""); $('#country').selectmenu("refresh"); $('#region').val(""); $('#region').selectmenu("refresh");
        $('#locality').val(""); $('#locality').selectmenu("refresh"); $('#service').val(""); $('#service').selectmenu("refresh");
        $('#object-form').submit();
    });
    $('#country').on("selectmenuselect", function(event, ui){
        $('#region').val(""); $('#region').selectmenu("refresh");
        $('#locality').val(""); $('#locality').selectmenu("refresh"); $('#service').val(""); $('#service').selectmenu("refresh");
        $('#object-form').submit();
    });
    $('#region').on("selectmenuselect", function(event, ui){
        $('#locality').val(""); $('#locality').selectmenu("refresh"); $('#service').val(""); $('#service').selectmenu("refresh");
        $('#object-form').submit();
    });
    $('#locality').on("selectmenuselect", function(event, ui){
        $('#service').val(""); $('#service').selectmenu("refresh");
        $('#object-form').submit();
    });
    $('#service').on("selectmenuselect", function(event, ui){
        $('#object-form').submit();
    });
    $('#service').selectmenu({
        open: function() {
            $('div.ui-selectmenu-menu li.ui-menu-item').each(function(idx){
                $(this).addClass( $('select option').eq(idx).attr('class') )
            })
        }
    });
    $('#locality').selectmenu({
        open: function() {
            $('div.ui-selectmenu-menu li.ui-menu-item').each(function(idx){
                $(this).addClass( $('select option').eq(idx).attr('class') )
            })
        }
    });
    if ($('#country').val()!='') {
        $('#region').selectmenu({
            disabled: false
        });
    }
    if ($('#region').val()!='') {
        $('#locality').selectmenu({
            disabled: false
        });
        $('#service').selectmenu({
            disabled: false
        });
    }
    $(".calendar-room h3").click(function() {
        $("."+$(this).parent().attr('id')).slideToggle('show');
    });
    $('#catroom-child_count').change(function(){
        var value = $('#catroom-child_count').val();
        if (value == 0) {
            $('.form1').addClass('displaynone');
            $('.form2').addClass('displaynone');
            $('.form3').addClass('displaynone');
            $('.form4').addClass('displaynone');
            $('.label0').addClass('displaynone');
            $('.label1').addClass('displaynone');
            $('.label2').addClass('displaynone');
            $('.label3').addClass('displaynone');

        }
        else if (value == 1) {
            $('.form1').removeClass('displaynone');
            $('.form2').addClass('displaynone');
            $('.form3').addClass('displaynone');
            $('.form4').addClass('displaynone');
            $('.label0').removeClass('displaynone');
            $('.label1').addClass('displaynone');
            $('.label2').addClass('displaynone');
            $('.label3').addClass('displaynone');

        }
        else if (value == 2) {
            $('.form1').removeClass('displaynone');
            $('.form2').removeClass('displaynone');
            $('.form3').addClass('displaynone');
            $('.form4').addClass('displaynone');
            $('.label0').removeClass('displaynone');
            $('.label1').removeClass('displaynone');
            $('.label2').addClass('displaynone');
            $('.label3').addClass('displaynone');
        }
        else if (value == 3) {
            $('.form1').removeClass('displaynone');
            $('.form2').removeClass('displaynone');
            $('.form3').removeClass('displaynone');
            $('.form4').addClass('displaynone');
            $('.label0').removeClass('displaynone');
            $('.label1').removeClass('displaynone');
            $('.label2').removeClass('displaynone');
            $('.label3').addClass('displaynone');
        }
        else if (value == 4) {
            $('.form1').removeClass('displaynone');
            $('.form2').removeClass('displaynone');
            $('.form3').removeClass('displaynone');
            $('.form4').removeClass('displaynone');
            $('.label0').removeClass('displaynone');
            $('.label1').removeClass('displaynone');
            $('.label2').removeClass('displaynone');
            $('.label3').removeClass('displaynone');
        }
    });
    $('.addform').click(function(){
        var html = $('.discount').html();
        var l = $('.discount > div').length + 1;
        html += '<div class="row" id="add_'+l+'"><div class="col-sm-3"><div class="form-group field-discount-fromage_'+l+' has-success"><input type="number" max="17" id="discount-fromage_'+l+'" class="form-control" name="Discount[fromage'+l+']" maxlength="255" placeholder="От" aria-invalid="false"><p class="help-block help-block-error"></p></div></div><div class="col-sm-3"><div class="form-group field-discount-age_'+l+' has-success"><input type="number" max="17" id="discount-age_'+l+'" class="form-control" name="Discount[age'+l+']" maxlength="255" placeholder="До (вкл)" aria-invalid="false"><p class="help-block help-block-error"></p></div></div><div class="col-sm-4"><div class="form-group field-discount-percent_'+l+'"><input type="number" max="100" id="discount-percent_'+l+'" class="form-control" name="Discount[percent'+l+']" maxlength="255" placeholder="Скидка, %"><p class="help-block help-block-error"></p></div></div><i class="fa fa-trash text-danger del-size" onclick="delForm('+l+')" aria-hidden="true"></i></div>';
        $('.discount').html(html);
    });
    $(window).scroll(function() {
       if($(window).scrollTop() > 400){$(".to-top").fadeIn();}else{$(".to-top").fadeOut();}
    });
    $(".to-top").click(function(){
        $("html, body").animate({ scrollTop: 0 }, "slow");
        return false;
    });
    $(".selectaddress").click(function(e){
        e.preventDefault();
        $('#address-modal').modal('show');
    });
    $(".btnclose").click(function(e){
        e.preventDefault();
        $('#delete-modal').modal('hide');
    });
    size_li = $(".review-item").size();
    x=5;
    $('.review-item:lt('+x+')').addClass('flex');
    $('#loadMore').click(function (e) {
        e.preventDefault();
        x= (x+5 <= size_li) ? x+5 : size_li;
    	if (x == size_li) {
    		$('#loadMore').closest('.text-right').hide();
    	}
        $('.review-item:lt('+x+')').addClass('flex');
    });
    $(".see-link").click(function(e) {
		e.preventDefault();
	    $('html, body').animate({
	        scrollTop: $("#seeall").offset().top
	    }, 1000);
	});
    $(".see-rev").click(function(e) {
		e.preventDefault();
	    $('html, body').animate({
	        scrollTop: $("#seereviews").offset().top
	    }, 1000);
	});
    $(".displaye-google").click(function(e){
        e.preventDefault();
        $(".google-translate").addClass("gvisible");
        $(this).addClass("novisible");
    });
    $(".displaye-changer").click(function(e){
        e.preventDefault();
        $(".curency-changer").toggleClass("cvisible");
    });
    $(".login-btn").click(function(e){
        e.preventDefault();
        $('#notify1-modal').modal('hide');
        $('#login-modal').modal('show');
    });
    $(".reset-pass").click(function(e){
        e.preventDefault();
        $('#login-modal').modal('hide');
        $('#reset-modal').modal('show');
    });
    $(".registration-btn").click(function(e){
        e.preventDefault();
        $('#checkreg-modal').modal('show');
    });
    $(".open-regform").click(function(e){
        e.preventDefault();
        $('#checkreg-modal').modal('hide');
        $('#regform-modal').modal('show');
    });
    $(".open-regadminform").click(function(e){
        e.preventDefault();
        $('#checkreg-modal').modal('hide');
        $('#regformadmin-modal').modal('show');
    });
    $(".go-reg").click(function(e) {
        e.preventDefault();
        $('#login-modal').modal('hide');
        $('#dialog-mess').modal('hide');
        $('#checkreg-modal').modal('show');
    });
    $('.owl-carousel').owlCarousel({
        margin:10,
        nav:true,
        loop:true,
        items:3,
        dots:false,
        navText: ["<img src='images/carousel/leftIcon.png'>","<img src='images/carousel/rigthIcon.png'>"],
        responsive:{
            0:{
                items:1
            },
            600:{
                items:2
            },
            1000:{
                items:3
            }
        }
    })
    $('#carousel').flexslider({
        animation: "slide",
        controlNav: false,
        animationLoop: false,
        slideshow: false,
        itemWidth: 100,
        asNavFor: '#slider'
    });
    $('#slider').flexslider({
        animation: "slide",
        controlNav: false,
        border: false,
        animationLoop: true,
        slideshow: false,
        sync: "#carousel"
    });
    $('#filter-count').click(function(){
        $('.form-guest').toggle();
    });
    $(document).mouseup(function(e){
        var container = $("#count-box");
        if (!container.is(e.target) && container.has(e.target).length === 0){
            container.hide();
        }
    });
    $('.bronirovat').click(function(){
        if ($('#filter-count').val()!= "" && $('#filter-from').val()!= "" && $('#filter-to').val()!= "") {
             $('.bookform').show();
             $('.notification').hide();
        }else {
            $('.bookform').hide();
            $('.notification').show();
        }
    })
    var input = $('#filter-count').html();
    if ($('.child_count').html() == 0) {
        $('.ages').css('padding', '0');
    }
    $('.plus_adult').click(function(){
        var adult_count = $('.adult_count').html();
        var max = $('.child_count').html();
        if (adult_count < 10) {
            adult_count ++;
            $('.adult_count').html(adult_count);
        }
        input = adult_count+' взр. и '+max+' дет.';
        $('#filter-count').val(input);
        $('#filter-adult').val(adult_count);
    });
    $('.plus-max').click(function(){
        var child_count = $('.child_count').html();
        var adult_count = $('.adult_count').html();
        if (child_count < 4) {
            child_count ++;
            $('.child_count').html(child_count);
            input = adult_count+' взр. и '+child_count+' дет.';
            $('#filter-count').val(input);
            $('#filter-child').val(child_count);
            // alert(child_count);
            $('.ages').css('padding', '15px');
            if (child_count == 1) {
                $('.age-1').show();
                $('.age-2').hide();
                $('.age-3').hide();
                $('.age-4').hide();
            }
            else if (child_count == 2) {
                $('.age-1').show();
                $('.age-2').show();
                $('.age-3').hide();
                $('.age-4').hide();
            }
            else if (child_count == 3) {
                $('.age-1').show();
                $('.age-2').show();
                $('.age-3').show();
                $('.age-4').hide();
            }
            else if (child_count == 4) {
                $('.age-1').show();
                $('.age-2').show();
                $('.age-3').show();
                $('.age-4').show();
            }
        }
    });
    $('.minus_adult').click(function(){
        var adult_count = $('.adult_count').html();
        var child_count = $('.child_count').html();
        if (adult_count > 1) {
            adult_count --;
            $('.adult_count').html(adult_count);
        }
        input = adult_count+' взр. и '+child_count+' дет.';
        $('#filter-count').val(input);
        $('#filter-adult').val(adult_count);
    });
    $('.minus-max').click(function(){
        var child_count = $('.child_count').html();
        var adult_count = $('.adult_count').html();
        if (child_count >0) {
            child_count --;
            $('.child_count').html(child_count);
            input = adult_count+' взр. и '+child_count+' дет.';
            $('#filter-count').val(input);
            $('#filter-child').val(child_count);
            if (child_count == 0) {
                $('.ages').css('padding', '0');
                $('.age-1').hide();
                $('.age-2').hide();
                $('.age-3').hide();
                $('.age-4').hide();
            } else if (child_count == 1) {
                $('.age-1').show();
                $('.age-2').hide();
                $('.age-3').hide();
                $('.age-4').hide();
            } else if (child_count == 2) {
                $('.age-1').show();
                $('.age-2').show();
                $('.age-3').hide();
                $('.age-4').hide();
            } else if (child_count == 3) {
                $('.age-1').show();
                $('.age-2').show();
                $('.age-3').show();
                $('.age-4').hide();
            }
        } else {
            $('.ages').css('padding', '0');
            $('.age-1').hide();
            $('.age-2').hide();
            $('.age-3').hide();
            $('.age-4').hide();
            $('#filter-age_1').val('0');$('#filter-age_2').val('0');$('#filter-age_3').val('0');$('#filter-age_4').val('0');
        }
    });
});
$(window).load(function(){
    $('#object-form .form-control').selectmenu("refresh");
});
function removeitem(i){
    var a = parseInt($('#catroom_update').val());
    $(".field-catroom-room_name"+i).parent().remove();
    $('#catroom_update').val(a-1);
}
function toggleDesc(elem) {
    var oldText = $('#podrobnee'+elem).text();
    var newText = $('#podrobnee'+elem).data('text');
    $('#podrobnee'+elem).text(newText).data('text',oldText);
    $('#description'+elem).toggleClass( "opened", 300, "easeOutSine" );
}
function countAgePlus(value, id) {
    var html = $('.'+value).html();
    if (html < 17) {
        html ++;
        $('.'+value).html(html);
        $('#'+id).val(html);
    }
}
function countAgeMinus(value, id) {
    var html = $('.'+value).html();
    if (html > 0) {
        html --;
        $('.'+value).html(html);
        $('#'+id).val(html);
    }
}
function delForm(i){
    $('#add_'+i).remove();
}
function openmodal(l) {
   var data = $('#izm'+l).data('target');
   $(data).modal('show');
}
