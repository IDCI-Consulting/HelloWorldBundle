/**
 * Created by brahim on 19/05/15.
 */
var mouseX;
var mouseY;
var imageBrahim;

jQuery(init);

jQuery(window).load(init);
jQuery(window).resize(init);

jQuery(window).mousemove(getMousePosition);

function init() {
    mouseX = 0;
    mouseY = 0;

    imageBrahim = new HeadImage('brahim');
}