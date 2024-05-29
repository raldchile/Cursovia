$(document).ready(function() {
    $('.paid-banner-col').fadeIn();
    $(".slider").bxSlider({
        infiniteLoop: true,
        // slideWidth: 600,
        // randomStart: true,
        // tickerHover: true,
        touchEnabled: false,
        controls: false,
        auto: true,
        autoDelay: 20,
        slideWidth: 363,
        minSlides: 1,
        maxSlides: 3,
        slideMargin: 6,
        speed: 1500,
        shrinkItems: true,
        autoHover: true,
    });
    $('.paid-courses-container').fadeIn();
    $(".slider-for-search").bxSlider({
        infiniteLoop: true,
        touchEnabled: true,
        controls: false,
        auto: true,
        autoDelay: 20,
        minSlides: 1,
        maxSlides: 1,
        slideMargin: 6,
        speed: 1000,
        autoHover: true,
        easing: 'ease-in-out',
        mode: 'fade',
    });
});