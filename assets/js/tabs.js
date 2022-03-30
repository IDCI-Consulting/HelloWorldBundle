$(document).ready(function() {
    $('.tab:not(.is-active)').css('display', 'none');
      $('.tabs li').on('click', function() {
        var tab = $(this).data('tab');
    
        $('.tabs li').removeClass('is-active');
        $(this).addClass('is-active');
    
        $('.tab').removeClass('is-active');
        $('.tab:not(.is-active)').css('display', 'none');
        
        $(`#${tab}`).addClass('is-active');
        $(`#${tab}`).css('display', '');
      });
  });