document.addEventListener('DOMContentLoaded', function() {
    let navboxLinks = document.getElementById('navbox').querySelectorAll('ul li');
    let linksScrolls = [];
    let linkStart = 0;
    let currentLink;

    navboxLinks.forEach((element, index) => {
        linksScrolls[index] = linkStart;
        linkStart += 1300;
    });

    window.addEventListener('scroll', function scroll() {
        for (const key in linksScrolls) {
            navboxLinks[key].classList.remove('active');
            if(this.scrollY > linksScrolls[key]) {
                currentLink = key;
            }
        }
        
        navboxLinks[currentLink].classList.add('active');
    });
});