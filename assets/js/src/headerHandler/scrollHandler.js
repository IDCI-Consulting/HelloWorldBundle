var $headerHeight = $('header').height();


if (Modernizr.mq('(min-width: 40.063em)')) {
    var $window = $(window);

    $('a.animate').on('click', function() {
        $('html, body').animate({scrollTop: $headerHeight}, 'slow');
    });

    $window.scroll(function() {
        $window.scrollTop() >= $headerHeight ? displayHeader() : hideHeader();
    });

    var displayHeader = function() {
        $('div.inner-wrap.fixed')
            .css('box-shadow', '0 4px 5px #888888')
            .css('background', '#FFF')
        ;

        $('section.left-small').css('border', '.2em solid #394A59');
        $('section.idci-logo').removeClass('show-for-small');

        resizeLogo($('section.idci-logo > h1 .idci-logo'));

        $('section.right-small').css('border', '.2em solid #394A59');

        $('a.idci-menu').css('color', '#394A59');
        $('a.idci-search').css('color', '#394A59');
    };

    var hideHeader = function() {
        $('div.inner-wrap.fixed')
            .css('box-shadow', 'none')
            .css('background', '0 0')
        ;

        $('section.left-small').css('border', '.2em solid #FFF');
        $('section.middle.idci-logo').addClass('show-for-small');
        $('section.right-small').css('border', '.2em solid #FFF');

        $('a.idci-menu').css('color', '#FFF');
        $('a.idci-search').css('color', '#FFF');
    };

    var resizeLogo = function(element) {
        element
            .css('background-size', '400px')
            .css('background-position', '0 -114px')
            .css('height', '44px')
            .css('margin-left', '2em')
            .css('width', '100%')
        ;
    };
}