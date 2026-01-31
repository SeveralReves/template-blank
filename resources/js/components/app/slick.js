
import 'slick-carousel';
import $ from 'jquery';


window.$ = $;
window.jQuery = $;

// importa los CSS desde JS
import 'slick-carousel/slick/slick.css';
import 'slick-carousel/slick/slick-theme.css';

const init = () => {
  initReviewsSlider();
  initGallerySlider();
}

const initGallerySlider = () => {
  const $sliderGallery = $('.js-gallery-slider');
  if (!$sliderGallery.length || typeof $.fn.slick !== 'function') return;

  $sliderGallery.slick({
    slidesToShow: 1,
    slidesToScroll: 1,
    infinite: true,
    arrows: false,
    dots: false,
    fade: true,
    autoplaySpeed: 5000,
    speed: 1000,
    autoplay: true,
  });
}

const initReviewsSlider = () => {
  const $slider = $('.js-reviews-slider');

  if (!$slider.length || typeof $.fn.slick !== 'function') return;

  $slider.slick({
    slidesToShow: 4,
    slidesToScroll: 1,
    infinite: true,
    arrows: true,
    dots: true,
    appendArrows: $('.reviews__nav'),
    appendDots: $('.js-reviews-dots'),
    prevArrow: $('.reviews__arrow--prev'),
    nextArrow: $('.reviews__arrow--next'),
    autoplay: false,
    responsive: [
      { breakpoint: 1280, settings: { slidesToShow: 1 } },
      // { breakpoint: 992,  settings: { slidesToShow: 2 } },
      // { breakpoint: 576,  settings: { slidesToShow: 1 } },
    ]
  });
}

export default {
    init
};