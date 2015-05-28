/**
 * Created by brahim on 19/05/15.
 */
var getMousePosition = function getMousePositionF(event) {
    mouseX = event.pageX;
    mouseY = event.pageY;

    jQuery.each(images, function(index, image){
        image.setImageDirection();
    });
};
