$(document).ready(function() {
    var elements = document.getElementsByClassName("pic-ctn");
    Array.from(elements).forEach((element) => {
        element.classList.remove("keyframes");
        element.classList.add("carousel-single-item");
    });

    $('.pic-ctn').slick({
        dots: false,
        arrows: true,
        prevArrow: '<button class="slide-arrow prev"><img src="/build/images/base/balise-left.svg" alt="previous arrow" class="slide-arrow-media"></button>',
        nextArrow: '<button class="slide-arrow next"><img src="/build/images/base/balise-right.svg" alt="next arrow" class="slide-arrow-media"></button>',
        slidesToShow: 1,
        slidesToScroll: 1,
        autoplay: true,
        autoplaySpeed: 3500,
    });
});