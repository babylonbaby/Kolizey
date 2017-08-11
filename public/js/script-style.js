$(document).ready(function() {
    /* начало скрипта
     * -----------------------------------------------------------------------------------------------------------*/
// ----------menu
    $(document).click(function(event) {
        if ($(event.target).closest(".drop").length) return;
        $(".p-menu").removeClass("active");
        $(".drop").removeClass("active");
        event.stopPropagation();
    });
    $(document).click(function(event) {
        if ($(event.target).closest(".search").length) return;
        if ($(event.target).closest(".input-search").length) return;
        $('.input-search').hide();
        event.stopPropagation();
    });

    $('.drop').click(function(e) {
        e.preventDefault();
        if ($(this).find('.p-menu').hasClass('active')) {
            $('.drop').removeClass('active');
            $('.p-menu').each(function() {
                $(this).removeClass('active');
            });
        } else {
            $('.drop').removeClass('active');
            $('.p-menu').each(function() {
                $(this).removeClass('active');
            });
            $(this).find('.p-menu').addClass('active');
            $(this).addClass('active');
        }

    });

    $('.goLink').click(function(e) {
        var url = $(this).find('a').attr('href');
        window.location.href = url;
    });

    $("#owl-1").owlCarousel({
        navigation : true, // показывать кнопки next и prev

        slideSpeed : 1000,
        paginationSpeed : 1000,

        rewindNav : true,
        rewindSpeed : 2000,

        transitionStyle : true,
        loop: true,

        items : 1,
        itemsDesktop : false,
        itemsDesktopSmall : false,
        itemsTablet: false,
        itemsMobile : false
    });

    $("#owl-2").owlCarousel({
        navigation : true, // показывать кнопки next и prev

        slideSpeed : 1000,
        paginationSpeed : 1000,

        items : 1,
        itemsDesktop : false,
        itemsDesktopSmall : false,
        itemsTablet: false,
        itemsMobile : false
    });

    $("#owl-3").owlCarousel({
        navigation : true, // показывать кнопки next и prev

        slideSpeed : 1000,
        paginationSpeed : 1000,

        items : 1,
        itemsDesktop : false,
        itemsDesktopSmall : false,
        itemsTablet: false,
        itemsMobile : false
    });

    $("#owl-4").owlCarousel({
        navigation : true, // показывать кнопки next и prev

        slideSpeed : 1000,
        paginationSpeed : 1000,

        items : 1,
        itemsDesktop : false,
        itemsDesktopSmall : false,
        itemsTablet: false,
        itemsMobile : false
    });

    $("#owl-head").owlCarousel({
        navigation : true, // показывать кнопки next и prev

        slideSpeed : 1000,
        paginationSpeed : 1000,

        // autoPlay : true,
        loop : true,
        items : 1,
        itemsDesktop : false,
        itemsDesktopSmall : false,
        itemsTablet: false,
        itemsMobile : true
    });

    // Дополнительные слайдеры:
    $("#owl-desc").owlCarousel({
        navigation : true, // показывать кнопки next и prev

        slideSpeed : 1000,
        paginationSpeed : 1000,

        items : 1,
        itemsDesktop : false,
        itemsDesktopSmall : false,
        itemsTablet: false,
        itemsMobile : false
    });

    $('.owl-prev').text("");
    $('.owl-next').text("");

    // ----------------- кнопка - вверх
    $(window).on("scroll", function() {
        if ($(window).scrollTop() > 800) $('.btn-up').show();
        else $('.btn-up').hide();
    }); /* фмксируем меню при скроле больше 100*/

    $('.btn-up').click(function () {
        $('html, body').animate({scrollTop: 0},1000);
        return false;
    });
    /* окончание скрипта
     * -----------------------------------------------------------------------------------------------------------*/
    $('.search').click(function() {
        if ($('.input-search').is(':visible')) {
            $('.input-search').hide();
        } else {
            $('.input-search').show(300);
        }
    });

    // Вкладки
    $(".tabs_header > a").click(function(e) {
        e.preventDefault();
        $(this).parent().find("a").each(function(){
            $(this).removeClass('active');
            $(this.getAttribute('href')).removeClass('active');
        });

        $(this).addClass("active");
        $(this.getAttribute('href')).addClass('active');
    });

    // Фиксированный асайд
    if ($(".callback_fixer").length > 0) {
        $(window).bind('scroll', function () {
            var st = $(window).scrollTop()
            var hp = $(".main").offset().top +  24;
            var lp = $(".aside").offset().top + $(".aside").height() - $(".callback_fixer").outerHeight();
            var offsetRight = ($(window).width() - $('.container').width()) / 2;
            if (st > hp && st < lp) {
                $('.callback_fixer').addClass('fixed');
                $('.callback_fixer').css({right:offsetRight+"px"});
            } else {
                $('.callback_fixer').removeClass('fixed');
                $('.callback_fixer').css({right:"0"});
            }
            if ( st > lp ) {
                $(".callback_fixer").addClass('bottom');
            } else {
                $('.callback_fixer').removeClass('bottom');
            }
        });
    }

    // Раскрывающиеся фильтры
    $(".extended_filters").each(function(){
        var filter = $(this);
        $(this).find(".toggle").click(function(e){
            e.preventDefault();
            if (filter.hasClass("active")) {
                filter.removeClass("active");
                $(this).text("Развернуть");
            } else {
                filter.addClass("active");
                $(this).text("Свернуть");
            }
        });
    });


    // Попапы
    //оставить отзыв
    $("#feedback_open").click(function(e){
//    $(".popup_open").click(function(e){
        e.preventDefault();
        var it = $(this);
        var target = (it.attr("data-target"))? it.attr("data-target"):it.attr("href");
        $(".popup").removeClass("active");
        $(target+",#curtain").addClass("active");
        var form = $('form[name="form_feedback"]');
        $(form).on('submit', function(e) {
            e.preventDefault();
            $('feedback_send').prop('disable', true);
            var send = new Array($('#feedback_name').val(), $('#feedback_email').val(), $('#feedback_select').val(), $('#feedback_message').val());
//            console.log();
            $.ajax({
                url: '/otzivy',
                type: 'POST',
                data: 'data='+JSON.stringify(send),
                success: function (data, textStatus, jqXHR) {
                    if (jqXHR.status == '202') {
                        $(form)[0].reset();
                        $('#feedback_send').prop('disable', false);
                        $("#feedback").removeClass("active");
                        $('#feedback_success').addClass("active");
                    }
                    return false;
                }
//                error: function (jqXHR, textStatus, errorThrown) {
//
//                }
            });

        });
    });
    //оставить заявку
    $("#order_open").click(function(e){
        e.preventDefault();
        var it = $(this);
        var target = (it.attr("data-target"))? it.attr("data-target"):it.attr("href");
        $(".popup").removeClass("active");
        $(target+",#curtain").addClass("active");
        var form = $('form[name="form_order"]');
        $(form).on('submit', function(e) {
            e.preventDefault();
            $('order_send').prop('disable', true);
            var send = new Array($('#order_name').val(), $('#order_email').val(), $('#order_phone').val(), $('#order_select').val(), $('#order_message').val());
            $.ajax({
                url: '/order',
                type: 'POST',
                data: 'data='+JSON.stringify(send),
                success: function (data, textStatus, jqXHR) {
                    if (jqXHR.status == '202') {
                        $(form)[0].reset();
                        $('#order_send').prop('disable', false);
                        $("#order").removeClass("active");
                        $('#feedback_success').addClass("active");
                    }
                    return false;
                }
//                error: function (jqXHR, textStatus, errorThrown) {
//
//                }
            });

        });
    });

    //Заказать обратный звонок
    $("#callback").click(function(e){
        e.preventDefault();
        var it = $(this);
        var target = (it.attr("data-target"))? it.attr("data-target"):it.attr("href");
        $(".popup").removeClass("active");
        $(target+",#curtain").addClass("active");
        var form = $('form[name="form_order"]');
        $(form).on('submit', function(e) {
            e.preventDefault();
            $('order_send').prop('disable', true);
            var send = new Array($('#order_name').val(), $('#order_email').val(), $('#order_phone').val(), $('#order_select').val(), $('#order_message').val());
            $.ajax({
                url: '/order',
                type: 'POST',
                data: 'data='+JSON.stringify(send),
                success: function (data, textStatus, jqXHR) {
                    if (jqXHR.status == '202') {
                        $(form)[0].reset();
                        $('#order_send').prop('disable', false);
                        $("#order").removeClass("active");
                        $('#feedback_success').addClass("active");
                    }
                    return false;
                }
//                error: function (jqXHR, textStatus, errorThrown) {
//
//                }
            });

        });
    });
    //оставить ипотечную заявку
    $("#mortgage").click(function(e){
        e.preventDefault();
        var it = $(this);
        var target = (it.attr("data-target"))? it.attr("data-target"):it.attr("href");
        $(".popup").removeClass("active");
        $(target+",#curtain").addClass("active");
        var form = $('form[name="form_order"]');
        $(form).on('submit', function(e) {
            e.preventDefault();
            $('order_send').prop('disable', true);
            var send = new Array($('#order_name').val(), $('#order_email').val(), $('#order_phone').val(), $('#order_select').val(), $('#order_message').val());
            $.ajax({
                url: '/order',
                type: 'POST',
                data: 'data='+JSON.stringify(send),
                success: function (data, textStatus, jqXHR) {
                    if (jqXHR.status == '202') {
                        $(form)[0].reset();
                        $('#order_send').prop('disable', false);
                        $("#order").removeClass("active");
                        $('#feedback_success').addClass("active");
                    }
                    return false;
                }
//                error: function (jqXHR, textStatus, errorThrown) {
//
//                }
            });

        });
    });

    $(".popup_close,#curtain").click(function(e){
        e.preventDefault();
        $("#curtain,.popup").removeClass("active");
    });

    // квартиры
    $(".flat.occupied, .flat.vacant").click(function(e) {
        $(".flat").not(this).removeClass("active");
        $(this).toggleClass("active");
    })
});
