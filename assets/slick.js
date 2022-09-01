$(document).ready(function() {
    var elements = document.getElementsByClassName("pic-ctn");
    Array.from(elements).forEach((element) => {
        element.classList.remove("keyframes");
        element.classList.add("carousel-single-item");
    });

    $('.pic-ctn').slick({
        slidesToShow: 1,
        slidesToScroll: 1,
        autoplay: true,
        autoplaySpeed: 3500,
    });
});