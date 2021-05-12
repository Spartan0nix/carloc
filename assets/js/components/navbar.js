document.addEventListener("click", (event) => {
    if(event.target.closest('.mobile-nav-btn')){
        const navbar = document.querySelector('.navbar-container');
        const body = document.querySelector('body');

        !body.style.overflow ? body.style.overflow = 'hidden' : body.attributes.removeNamedItem('style');
        navbar.classList.toggle('navbar-open');
    }
})