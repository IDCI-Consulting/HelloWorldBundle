/**
 * Created by brahim on 19/05/15.
 */
function HeadImage(className) {

    this.className         = className;
    var $image             = jQuery('.'+this.className+'>.icon-home');

    /* Calculating the image's borders */
    this.imageBorderTop    = $image.offset().top;
    this.imageBorderLeft   = $image.offset().left;
    this.imageBorderRight  = this.imageBorderLeft + $image.width();
    this.imageBorderBottom = this.imageBorderTop + $image.height();

    /**
     * Replaces the given element class by the given new class name
     * @param element
     * @param newClassName
     */
    this.replaceElementClass = function replaceElementClassF(element, newClassName) {
        element.removeClass().addClass(newClassName);
    };

    /**
     * Determines where the mouse pointer is according to the image and displays the correct image
     */
    this.setImageDirection = function setImageDirectionF() {
        var $headImage = jQuery('.'+this.className+'>.icon-home');

        if (mouseX >= this.imageBorderLeft && mouseX <= this.imageBorderRight && mouseY <= this.imageBorderTop) {
            this.replaceElementClass($headImage, "icon-home icon-2-up");
        } else if (mouseX < this.imageBorderLeft && mouseY < this.imageBorderTop) {
            this.replaceElementClass($headImage, "icon-home icon-1-upleft");
        } else if (mouseX <= this.imageBorderLeft && mouseY >= this.imageBorderTop && mouseY <= this.imageBorderBottom) {
            this.replaceElementClass($headImage, "icon-home icon-3-left");
        } else if (mouseX < this.imageBorderLeft && mouseY > this.imageBorderBottom) {
            this.replaceElementClass($headImage, "icon-home icon-7-downleft");
        } else if (mouseX >= this.imageBorderLeft && mouseX <= this.imageBorderRight && mouseY >= this.imageBorderBottom) {
            this.replaceElementClass($headImage, "icon-home icon-8-down");
        } else if (mouseX > this.imageBorderRight && mouseY > this.imageBorderBottom) {
            this.replaceElementClass($headImage, "icon-home icon-9-downright");
        } else if (mouseX >= this.imageBorderRight && mouseY >= this.imageBorderTop && mouseY <= this.imageBorderBottom) {
            this.replaceElementClass($headImage, "icon-home icon-6-right");
        } else if (mouseX > this.imageBorderRight && mouseY < this.imageBorderTop) {
            this.replaceElementClass($headImage, "icon-home icon-5-upright");
        } else {
            this.replaceElementClass($headImage, "icon-home icon-4-front");
        }
    };
}
