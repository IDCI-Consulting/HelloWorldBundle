$('.menu-icon').on('click', function(event) {
    event.preventDefault();

    var $nav = $('nav.nav-menu'),
        $hamburger = $('.hamburger')
    ;

    $nav.toggleClass('open');
    $hamburger.toggleClass('open');
    $nav.toggle('fast');
});