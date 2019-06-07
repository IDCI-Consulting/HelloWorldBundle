import slick from 'slick-carousel';

export default function setUpCarousel(autoplay = true) {
    $('.carousel').slick({
        autoplay: autoplay,
        autoplaySpeed: 5000,
        speed: 1000,
        inifinite: true,
        nextArrow: $('.right'),
        prevArrow: $('.left')
    });
}