$('.menu-icon').on('click', function(event) {
    event.preventDefault();

    var $nav     = $('nav.nav-menu');
    var $navIcon = $('.menu-icon') ;

    $nav.toggleClass('open');
    $nav.toggle('fast');

    if ($nav.hasClass("open")) {
        $navIcon.html("<i class='fa fa-times'></i>");
    } else {
        $navIcon.html("<i class='fa fa-bars'></i>");
    }
});