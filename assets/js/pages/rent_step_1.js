if(document.getElementById('rent_step_1')){

    var searchPickupOffice = document.createElement('js-search');
    searchPickupOffice.dataset.placeholder = "Entrez une ville ou code postal";
    searchPickupOffice.dataset.id = "office-pickup-input";
    searchPickupOffice.dataset.name = "pickup_office";
    searchPickupOffice.dataset.listId = "pickup_offices";
    searchPickupOffice.dataset.type = "office";
    document.querySelector('.office-pickup').appendChild(searchPickupOffice);

    var searchReturnOffice = document.createElement('js-search');
    searchReturnOffice.dataset.placeholder = "Entrez une ville ou code postal";
    searchReturnOffice.dataset.id = "office-return-input";
    searchReturnOffice.dataset.name = "return_office";
    searchReturnOffice.dataset.listId = "return_offices";
    searchReturnOffice.dataset.type = "office";
    document.querySelector('.office-return').appendChild(searchReturnOffice);

    var returnOfficeCheckbox = document.getElementById('same-return-office');
    var returnOfficeContainer = returnOfficeCheckbox.closest('.office-return');

    setTimeout(() => {
        returnOfficeContainer.querySelector('input[type="text"]').required = false;
    },50)
    
    returnOfficeCheckbox.addEventListener('click', () => {
        if(returnOfficeContainer.classList.contains('diff-return-office')){
            returnOfficeContainer.querySelector('input[type="text"]').value = ''
            returnOfficeContainer.querySelector('input[type="hidden"]').value = ''
        }
        returnOfficeContainer.classList.toggle('diff-return-office');
    })

    
}