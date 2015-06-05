/**
 * Created by brahim on 19/05/15.
 */
var mouseX;
var mouseY;

jQuery(init);
jQuery(window).load(init);
jQuery(window).resize(init);
jQuery(window).mousemove(getMousePosition);

var images = [];

function init() {
    mouseX = 0;
    mouseY = 0;

    var holders = jQuery('.image-holder');
    jQuery.each(
        holders, function(index, holder){
            var holderName = jQuery(holder).attr('class').substring(13); //
            images[index] = new HeadImage(holderName);
        }
    );
}