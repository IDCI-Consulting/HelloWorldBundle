$(document).ready(function() {
    $('.tabs li:first-child').addClass('active');

    $('div.tabs-content > div:first-child').addClass('active on');

    $('.tabs').on('click', 'li', function() {
        $('.tabs-content').find('.content').removeClass('on');
        $('.content.active').fadeIn('slow').addClass('on');
    });
});