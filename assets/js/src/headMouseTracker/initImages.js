/**
 * Created by brahim on 19/05/15.
 */
var mouseX;
var mouseY;

function initImage() {
    mouseX = 0;
    mouseY = 0;

    var profilePictures = document.querySelectorAll('.profile-picture');
    profilePictures.forEach(function(){
            var holderName = this.getAttribute('class').substring(13);
            console.log(holderName);
            //images[index] = new HeadImage(holderName);
        }
    );
}