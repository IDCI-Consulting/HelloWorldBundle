$(document).foundation();

Modernizr.load({
    test: Modernizr.cssremunit,
    yep: '',
    nope: ['polyfills/rem.js']
});

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