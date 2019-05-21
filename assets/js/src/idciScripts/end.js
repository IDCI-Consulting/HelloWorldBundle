// Get all articles & add 'end' class at the last of them
$(document).ready(function() {
    var $articles = $('article.member');
    var length = $articles.length;
    $articles.each(function(index) {
        if (index == length -1) {
            $(this).addClass('end');
        }
    });
});