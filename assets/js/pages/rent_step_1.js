if(document.getElementById('rent_step_1')){
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