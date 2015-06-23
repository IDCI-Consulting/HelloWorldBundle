$(document).foundation();

Modernizr.load({
    test: Modernizr.cssremunit,
    yep: '',
    nope: ['polyfills/rem.min.js']
});