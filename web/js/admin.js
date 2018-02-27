$(document).ready(function(){
    $(".question p").click(function(){
        $(this).toggleClass("open");
        $(this).closest('.faqs').find('.answer').slideToggle("show");
    })
    $('.answer').first().show();
    $('.answer').first().addClass("open");
    $('.fromicon').click(function(){
        $('.datefrom').trigger('focus');
    });
    $('.datefrom').datepicker({
        language: 'ru',
        format: 'yyyy-mm-dd',
        todayBtn: true,
        autoclose: true,
        todayHighlight: true
    });
});
$(function () {
    $('.top-scroll').on('scroll', function (e) {
        $('.grid-view').scrollLeft($('.top-scroll').scrollLeft());
    });
    $('.grid-view').on('scroll', function (e) {
        $('.top-scroll').scrollLeft($('.grid-view').scrollLeft());
    });
});
$(window).on('load', function (e) {
    $('.top-scrollbar').width($('.grid-view .table').width());
});
