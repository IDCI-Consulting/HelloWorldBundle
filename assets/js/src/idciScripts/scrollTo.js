$(document).ready(function() {
  enableScroll();
});

function enableScroll () {
  $('.aside-navigation-link, .scroll-to').on('click', function() {
      var $sectionId = $(this).attr('href');

      $('body, html').animate({
          scrollTop: $($sectionId).offset().top
      }, 'slow');
  });
}
