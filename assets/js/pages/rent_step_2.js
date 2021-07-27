if(document.getElementById('rent_step_2') && document.querySelector('.btn-clear-filter')){
    document.querySelector('.btn-clear-filter').addEventListener('click', (event) => {
        event.preventDefault();

        let form = document.getElementById('filter-form');

        let checkbox = form.querySelectorAll('input[type="checkbox"]');
        let searchElement = form.getElementsByTagName('js-search-static');
        let removeFilterButton = form.querySelector('.btn-clear-filter');

        checkbox.forEach(element => {
            element.checked ? element.checked = false : '';
        })

        searchElement.forEach(container => {
            let userInput = container.querySelector('input[type="text"]');
            let removeFilterIcon = container.querySelector('.search-remove-filter');
            let form_input = container.querySelector('input[type="hidden"]');
    
            userInput.value = '';
            form_input.value = '';
            removeFilterIcon.style.display = 'none';
        })

        removeFilterButton.style.display = 'none';
    })
}