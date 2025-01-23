$(document).ready(function(){
    $('.section-dynamic-display-content').slick({
        dots: false,
        arrows: true,
        prevArrow: '<button class="button slide-arrow prev"><i class="fas fa-chevron-left"></i></button>',
        nextArrow: '<button class="button slide-arrow next"><i class="fas fa-chevron-right"></i></button>',
        autoplay: true,
        infinite: true,
        speed: 300,
        slidesToShow: 1,
        slidesToScroll: 1,
    });
});
