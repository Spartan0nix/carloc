if(document.getElementById('rent_step_1')){
    var searchPickupOffice = document.createElement('js-search-offices');
    searchPickupOffice.dataset.placeholder = "Entrez une ville ou code postal";
    searchPickupOffice.dataset.id = "office-pickup-input";
    searchPickupOffice.dataset.name = "pickup_office";
    searchPickupOffice.dataset.listId = "pickup_offices";
    document.querySelector('.office-pickup').appendChild(searchPickupOffice);

    var searchReturnOffice = document.createElement('js-search-offices');
    searchReturnOffice.dataset.placeholder = "Entrez une ville ou code postal";
    searchReturnOffice.dataset.id = "office-return-input";
    searchReturnOffice.dataset.name = "return_office";
    searchReturnOffice.dataset.listId = "return_offices";
    document.querySelector('.office-return').appendChild(searchReturnOffice);

    var returnOfficeCheckbox = document.getElementById('same-return-office');
    var returnOfficeContainer = returnOfficeCheckbox.closest('.office-return');

    setTimeout(() => {
        returnOfficeContainer.querySelector('input[type="text"]').required = false;
    },50)
    

    var returnOfficeCheckbox = document.getElementById('same-return-office');
    returnOfficeCheckbox.addEventListener('click', () => {
        if(container.classList.contains('diff-return-office')){
            returnOfficeContainer.querySelector('input[type="text"]').value = ''
            returnOfficeContainer.querySelector('input[type="hidden"]').value = ''
        }
        container.classList.toggle('diff-return-office');
    })
}