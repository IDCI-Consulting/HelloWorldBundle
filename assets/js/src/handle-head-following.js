export default function handleHeadFollowing() {
    function replaceElementClass(element, newClass) {
        element.classList = newClass;
    }

    function offset(element) {
        var rect = element.getBoundingClientRect(), bodyElement = document.body;
        return {
            top: rect.top + bodyElement.scrollTop,
            left: rect.left + bodyElement.scrollLeft,
        }
    }

    document.onmousemove = ImageChange;

    function ImageChange(event) {

        var profilePictures = document.querySelectorAll("img.profile-picture").forEach(function (image) {

            var imagePosition = offset(image);
            var bodyRect = offset(document.body);
            var imageBorderTop = imagePosition.top - bodyRect.top;
            var imageBorderLeft = imagePosition.left - bodyRect.left;
            var imageBorderRight = imageBorderLeft + image.offsetWidth;
            var imageBorderBottom = imageBorderTop + image.offsetHeight;

            if (event.pageX >= imageBorderLeft && event.pageX <= imageBorderRight && event.pageY <= imageBorderTop) {
                replaceElementClass(image, "profile-picture up");
            } else if (event.pageX < imageBorderLeft && event.pageY < imageBorderTop) {
                replaceElementClass(image, "profile-picture upleft");
            } else if (event.pageX <= imageBorderLeft && event.pageY >= imageBorderTop && event.pageY <= imageBorderBottom) {
                replaceElementClass(image, "profile-picture left");
            } else if (event.pageX < imageBorderLeft && event.pageY > imageBorderBottom) {
                replaceElementClass(image, "profile-picture downleft");
            } else if (event.pageX >= imageBorderLeft && event.pageX <= imageBorderRight && event.pageY >= imageBorderBottom) {
                replaceElementClass(image, "profile-picture down");
            } else if (event.pageX > imageBorderRight && event.pageY > imageBorderBottom) {
                replaceElementClass(image, "profile-picture downright");
            } else if (event.pageX >= imageBorderRight && event.pageY >= imageBorderTop && event.pageY <= imageBorderBottom) {
                replaceElementClass(image, "profile-picture right");
            } else if (event.pageX > imageBorderRight && event.pageY < imageBorderTop) {
                replaceElementClass(image, "profile-picture upright");
            } else {
                replaceElementClass(image, "profile-picture front");
            }
        });
    }
}