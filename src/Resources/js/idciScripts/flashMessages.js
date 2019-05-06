(function($) {
    $.notify = function(level, message) {
        var $alert = $('<div data-alert class="alert-box '+level+' radius"></div>');
        var $closeButton = $('<a href="#" class="close">&times;</a>');

        $alert.append(message);
        $alert.append($closeButton);
        $('#flash-message-container').append($alert).foundation();

        // Close alert box after 2s
        setTimeout(function() {
            $closeButton.click();
        }, 2000);
    }
})(jQuery);