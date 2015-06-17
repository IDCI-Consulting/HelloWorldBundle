$(document).on('open.fndtn.offcanvas', '[data-offcanvas]', function (e) {
    e.preventDefault();
    var hamburgerMenu = $('i.fa-bars');
    if ($('#toto').hasClass('move-right')) {
        hamburgerMenu.animate({borderSpacing: 90}, {
            step: function () {
                $(this).css('-webkit-transform', 'rotate(90deg)');
                $(this).css('-moz-transform', 'rotate(90deg)');
                $(this).css('transform', 'rotate(90deg)');
            },
            duration: 50
        });
    }
});

$(document).on('close.fndtn.offcanvas', '[data-offcanvas]', function (e) {
    e.preventDefault();
    var hamburgerMenu = $('i.fa-bars');
    hamburgerMenu.animate({  borderSpacing: 0 }, {
        step: function () {
            $(this).css('-webkit-transform', 'rotate(0deg)');
            $(this).css('-moz-transform','rotate(0deg)');
            $(this).css('transform','rotate(0deg)');
        },
        duration: 50
    });
});