if (window.matchMedia('(min-width: 40.063em)').matches) {
    $( window ).scroll(function() {

        var $body = $('body');

        if ($body.scrollTop() >= 636) {
            $('div.inner-wrap.fixed')
                .css('box-shadow', '0 4px 5px #888888')
                .css('background', '#FFF')
            ;

            $('section.left-small').css('border', '.2em solid #394A59');
            $('section.middle').removeClass('show-for-small');
            resizeLogo($('a.idci-logo'));

            $('section.right-small').css('border', '.2em solid #394A59');

            $('a.idci-menu').css('color', '#394A59');
            $('a.idci-search').css('color', '#394A59');
        } else {
            $('div.inner-wrap.fixed')
                .css('box-shadow', 'none')
                .css('background', '0 0')
            ;

            $('section.left-small').css('border', '.2em solid #FFF');
            $('section.middle').addClass('show-for-small');
            $('section.right-small').css('border', '.2em solid #FFF');

            $('a.idci-menu').css('color', '#FFF');
            $('a.idci-search').css('color', '#FFF');
        }
    });

    var resizeLogo = function(element) {
        element
            .css('background-size', '400px')
            .css('background-position', '0 -115px')
            .css('height', '44px')
            .css('margin-left', '2em')
            .css('width', '100%')
        ;
    };
}

