/**
 * Created by brahim on 19/05/15.
 */
function HeadImage(className) {

    this.className         = className;
    var $image             = jQuery('.'+this.className+'>.profile-picture');

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
        var $headImage = jQuery('.'+this.className+'>.profile-picture');

        if (mouseX >= this.imageBorderLeft && mouseX <= this.imageBorderRight && mouseY <= this.imageBorderTop) {
            this.replaceElementClass($headImage, "profile-picture up");
        } else if (mouseX < this.imageBorderLeft && mouseY < this.imageBorderTop) {
            this.replaceElementClass($headImage, "profile-picture upleft");
        } else if (mouseX <= this.imageBorderLeft && mouseY >= this.imageBorderTop && mouseY <= this.imageBorderBottom) {
            this.replaceElementClass($headImage, "profile-picture left");
        } else if (mouseX < this.imageBorderLeft && mouseY > this.imageBorderBottom) {
            this.replaceElementClass($headImage, "profile-picture downleft");
        } else if (mouseX >= this.imageBorderLeft && mouseX <= this.imageBorderRight && mouseY >= this.imageBorderBottom) {
            this.replaceElementClass($headImage, "profile-picture down");
        } else if (mouseX > this.imageBorderRight && mouseY > this.imageBorderBottom) {
            this.replaceElementClass($headImage, "profile-picture downright");
        } else if (mouseX >= this.imageBorderRight && mouseY >= this.imageBorderTop && mouseY <= this.imageBorderBottom) {
            this.replaceElementClass($headImage, "profile-picture right");
        } else if (mouseX > this.imageBorderRight && mouseY < this.imageBorderTop) {
            this.replaceElementClass($headImage, "profile-picture upright");
        } else {
            this.replaceElementClass($headImage, "profile-picture front");
        }
    };
}
