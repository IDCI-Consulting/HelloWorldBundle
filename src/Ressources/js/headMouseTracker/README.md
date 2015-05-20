head mouse tracker
==================

This script allow to easily create an element with a profile picture, where the head follow the mouse.

Create a sprite of your profile with 9 photos in this order

---------------------------------------
|            |           |            |
|   up left  |     up    |  up right  |
|            |           |            |
---------------------------------------
|            |           |            |
|    left    |   front   |   right    |
|            |           |            |
--------------------------------------|
|            |           |            |
| down left  |   down    |  center    |
|            |           |            |
---------------------------------------


 Add this html where you want the picture.
 Edit the base image src attribute (it should be a frame (png) so we can see your profile behind )

 ```html
<div class="image-holder username">
    <img class="circle" src="images/basecenterimage.png"/>
    <div class="profile-picture front"></div>
</div>
```

Lastly, you need to add the sprite location

```css
.username .profile-picture {
    background-image: url(/images/brahim/sprite.jpg);
}
```

As well as some css features with images positions:

```css
.image-holder .upleft {
      background-position: 0 0;
      width: 260px;
      height: 260px;
}
```

Edit background-position, width and height according to your sprite.
Repeat this operation with these classes : .up, .left, .front, .upright, .right, .downleft, .down and .downright

We recommand using a task runner to easily perform those operations : https://github.com/twolfson/gulp.spritesmith

As simple as :

```js
gulp.task('sprite', function () {
    gulp.src(src['images'])
        .pipe(spritesmith({
            imgName: 'sprite.jpg',
            cssName: 'sprite.css'
        }))
        .pipe(gulp.dest(web['images']))
    ;
});
```

This will generate the sprite and the associated css.