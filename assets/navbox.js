document.addEventListener('DOMContentLoaded', function() {
    let navboxLinks = document.getElementById('navbox').querySelectorAll('ul li');
    let sections = document.getElementsByClassName('section-container');
    let sectionsStarts = [];
    let currentLink;

    Array.from(sections).forEach((element, index) => {
        sectionsStarts[index] = element.getBoundingClientRect()['y'] - document.body.getBoundingClientRect()['y'];
    });

    window.addEventListener('scroll', function scroll() {
        for (const key in sectionsStarts) {
            navboxLinks[key].classList.remove('active');
            if (this.scrollY >= sectionsStarts[key]) {
                currentLink = key;
            }
        }
        
        navboxLinks[currentLink].classList.add('active');
    });
});