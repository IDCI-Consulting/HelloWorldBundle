$(document).foundation();

Modernizr.load({
    test: Modernizr.cssremunit,
    yep: '',
    nope: ['polyfills/rem.js']
});


// Function to decode string into utf8
String.prototype.decode = function(encoding) {
    var result = "";

    var index = 0;
    var c = c1 = c2 = 0;

    while(index < this.length) {
        c = this.charCodeAt(index);

        if(c < 128) {
            result += String.fromCharCode(c);
            index++;
        } else if((c > 191) && (c < 224)) {
            c2 = this.charCodeAt(index + 1);
            result += String.fromCharCode(((c & 31) << 6) | (c2 & 63));
            index += 2;
        } else {
            c2 = this.charCodeAt(index + 1);
            c3 = this.charCodeAt(index + 2);
            result += String.fromCharCode(((c & 15) << 12) | ((c2 & 63) << 6) | (c3 & 63));
            index += 3;
        }
    }

    return result;
};

// Function to highlight aside menu element referenced with id and href
(function highlightNav() {
    var prev; //keep track of previous selected link
    var isVisible= function(el){
        el = $(el);

        if(!el || el.length === 0){
            return false
        }

        var docViewTop = $(document).scrollTop();
        var docViewBottom = docViewTop + $(document).height();

        var elemTop = el.offset().top;
        var elemBottom = elemTop + el.height();
        return ((elemBottom >= docViewTop) && (elemTop <= docViewBottom));
    };

    $(window).scroll(function(){
        $('.aside-navigation-menu a').each(function(index, el){
            el = $(el);
            if(isVisible(el.attr('href'))){
                if(prev){
                    prev.removeClass('active');
                }
                el.addClass('active');
                prev = el;

                //break early to keep highlight on the first/highest visible element
                //remove this you want the link for the lowest/last visible element to be set instead
                return false;
            }
        });
    });

    //trigger the scroll handler to highlight on page load
    $(window).scroll();
})();