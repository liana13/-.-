$(document).ready(function(){
    var href = $('#show-form').attr('href');
    $('.pricing-col .btn-square').click(function(){
        var id = $(this).attr('id').split('-')[1];
        var addhref = href+"/"+id;
        $('#show-form').attr('href', addhref);
        $('#show-form').removeClass('disabled');
    })
    $('.disabled').click(function(e){e.preventDefault();})
    $(".question p").click(function(){
        $(this).toggleClass("open");
        $(this).closest('.faqs').find('.answer').slideToggle("show");
    })
    $('.answer').first().show();
    $('.answer').first().addClass("open");
    if ($("#persontype").val()==1) {
        $("#display-type-1").show();
        $("#h1person").html("Для юридического лица");
        $(".one_and_two").show();
        $("#two_and_three").hide();
        $(".onlyone").show();
    } else if ($("#persontype").val()==2) {
        $("#display-type-1").show();
        $("#h1person").html("Для индивидуального предпринимателя");
        $("#two_and_three").show();
        $(".one_and_two").show();
        $(".onlyone").hide();
    }else if ($("#persontype").val()==3) {
        $("#display-type-1").show();
        $("#h1person").html("Для физического лица");
        $(".one_and_two").hide();
        $("#two_and_three").show();
        $(".onlyone").hide();
    }
    $(".print_bron").click(function() {
        $('#print-modal').modal('show');
    })
});
