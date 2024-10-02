document.addEventListener('DOMContentLoaded', function() {
    const selected = 'selected';

    document.querySelectorAll('.section-dynamic-display-navigation-label').forEach(element => {
        element.addEventListener('click', function() {
            element.parentElement.querySelector('.' + selected).classList.remove(selected);
            element.classList.add(selected);
        })
    })
})
