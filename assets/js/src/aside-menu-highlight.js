export default function asideMenuHighlight() {
    var prev; //keep track of previous selected link
    var isVisible= function(el){
        el = document.querySelector(el)
        if(!el || el.length === 0){
            return false
        }

        var docViewTop = window.scrollY;
        var docViewBottom = docViewTop + window.innerHeight;

        var elemTop = el.offsetTop;
        var elemBottom = elemTop + el.offsetHeight - 20;
        return ((elemBottom >= docViewTop) && (elemTop <= docViewBottom));
    };

    document.addEventListener('scroll', function(){
        document.querySelectorAll('.aside-navigation-menu a').forEach(el => {
            if (isVisible(el.getAttribute('href'))) {
                if (prev) {
                    prev.classList.remove('active');
                }
                el.classList.add('active');
                prev = el;

                return false;
            }
        });
    });
};