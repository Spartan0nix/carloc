if(document.getElementById('rent_step_5')){
    var count = 0;
    document.querySelector('.animation').addEventListener('animationend', (event) => {
        let element = event.target;
        element.classList.toggle('check');
        element.style.border = '2px solid var(--success)';
        element.style.animation = 'fadein 1s ease-in';
        element.innerHTML = `<svg xmlns="http://www.w3.org/2000/svg" class="icon icon-tabler icon-tabler-check" width="100" height="100" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor" fill="none" stroke-linecap="round" stroke-linejoin="round">
                                <path stroke="none" d="M0 0h24v24H0z" fill="none"/>
                                <path d="M5 12l5 5l10 -10" />
                            </svg>`;
        element.style.opacity = '1';
    })
}