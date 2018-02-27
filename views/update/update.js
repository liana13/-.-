$(document).keypress(
    function(event){
     if (event.which == '13') {
        event.preventDefault();
      }
});
$(document).ready(function(){
    $(".btn-modal-open").click(function() {
        $(".alert").hide();
    });
    $('.sort-block .dropdown-menu .active').closest('.dropdown').find('.dropdown-toggle').html($('.sort-block .dropdown-menu .active a').html());
    $('.sort-block .dropdown-menu .active').closest('.dropdown').find('.dropdown-toggle').addClass('text-primary');
    if ($('.sort-block .dropdown-menu .active').hasClass('asc')) {
        $('.sort-block .dropdown-menu .active').closest('.dropdown').find('.dropdown-toggle').addClass('asc');
    } else if ($('.sort-block .dropdown-menu .active').hasClass('desc')) {
        $('.sort-block .dropdown-menu .active').closest('.dropdown').find('.dropdown-toggle').addClass('desc');
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
        alert($('.discnow .row.count:last-child').attr('id'));
        var l = parseInt($('.discnow .row.count:last-child').attr('id')) + 1;
        $('.discount').append('<div class="row count" id="'+l+'"><div class="col-sm-3"><div class="form-group field-discount-fromage_'+l+' has-success"><input type="number" max="17" id="discount-fromage_'+l+'" class="form-control" name="Discount[fromage'+l+']" maxlength="255" placeholder="От (вкл)" aria-invalid="false"><p class="help-block help-block-error"></p></div></div><div class="col-sm-3"><div class="form-group field-discount-age_'+l+' has-success"><input type="number" max="17" id="discount-age_'+l+'" class="form-control" name="Discount[age'+l+']" maxlength="255" placeholder="До (вкл)" aria-invalid="false"><p class="help-block help-block-error"></p></div></div><div class="col-sm-4"><div class="form-group field-discount-percent_'+l+'"><input type="number" max="100" id="discount-percent_'+l+'" class="form-control" name="Discount[percent'+l+']" maxlength="255" placeholder="Скидка, %"><p class="help-block help-block-error"></p></div></div><i class="fa fa-trash text-danger del-size" onclick="delForm('+l+')" aria-hidden="true"></i></div>');
        // $('.discount').html(html);
    });

    // Daterangepicker
    $('.daterange1').daterangepicker({
        'locale': {
            'firstDay':1,
            'daysOfWeek': ['Вс', 'Пн', 'Вт', 'Ср', 'Чт', 'пт', 'сб'],
            'monthNames': ['Январь', 'Февраль', 'Март', 'Апрель', 'Май', 'Июнь', 'Июль', 'Август', 'Сентябрь', 'Октября', 'Ноябрь', 'Декабрь'],
        },
        autoApply: true,
        minDate:  new Date(),
    }, function(start, end, label) {
      console.log('New date range selected: ' + start.format('YYYY-MM-DD') + ' to ' + end.format('YYYY-MM-DD') + ' (predefined range: ' + label + ')');
    });
    $('.daterange1').on('apply.daterangepicker', function(ev, picker) {
        $('.price-from').val(picker.startDate.format('YYYY-MM-DD'));
        $('.price-to').val(picker.endDate.format('YYYY-MM-DD'));
    });
    $(".selectaddress").click(function(e){
        e.preventDefault();
        $('#address-modal').modal('show');
    });
    $(".btnclose").click(function(e){
        e.preventDefault();
        $('#delete-modal').modal('hide');
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
function scrollable(el) {
    el.closest('.calendar-room').find('.column').scrollLeft(el.scrollLeft());
}
