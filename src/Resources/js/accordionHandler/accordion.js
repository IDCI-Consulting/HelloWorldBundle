$(document).ready(function () {
    $('.accordion-toggle').on('click', function(event){
        event.preventDefault();
        // create accordion variables
        var accordion = $(this);
        var accordionContent = accordion.next('.accordion-content');
        var accordionToggleIcon = $(this).find('.toggle-icon');

        // toggle accordion link open class
        accordion.toggleClass("open");
        // toggle accordion content
        accordionContent.slideToggle(250);

        // change plus/minus icon
        if (accordion.hasClass("open")) {
            accordionToggleIcon.html("<i class='fa fa-angle-up'></i>");
        } else {
            accordionToggleIcon.html("<i class='fa fa-angle-down'></i>");
        }
    });
});