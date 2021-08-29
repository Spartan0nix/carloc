if(document.getElementById('account')){
    let userAddressSubmit = document.querySelector('.btn-submit');
    let userAddressCancel = document.querySelector('.btn-warning');

    userAddressSubmit.style.display = 'none'
    userAddressCancel.style.display = 'none'

    setTimeout(() => {
        let userInfoContainer = document.querySelector('.user-info-inputs');
        let userInfoInputs = userInfoContainer.querySelectorAll('input');
        let userInfoSubmit = userInfoContainer.querySelector('.btn');
        
        let addressInput = document.getElementById('address-input');
        let cityInput = document.getElementById('city-input');
        let departmentInput = document.getElementById('department-input');
        let userAddressBtn = document.querySelector('.btn-info');

        cityInput.disabled = true;
        departmentInput.disabled = true;

        userAddressBtn.addEventListener('click', () => { toogleContent() })
        userAddressCancel.addEventListener('click', () => { toogleContent() })

        function toogleContent(){
            userInfoInputs.forEach(element => {
                toogleDisable(element);
            })
    
            userInfoSubmit.classList.toggle('btn-disabled')
            toogleDisable(userInfoSubmit);
            toogleDisable(addressInput);
            toogleDisable(cityInput);
            toogleDisable(departmentInput);
            
            toogleDisplay(userAddressBtn)
            toogleDisplay(userAddressSubmit)
            toogleDisplay(userAddressCancel)
        }
          
        function toogleDisable(element) {
            let status = element.disabled;
            element.disabled = !status;
        }
        function toogleDisplay(element) {
            element.style.display === 'flex' || element.style.display === '' ? element.style.display = 'none' : element.style.display = 'flex';
        }
    }, 100);
}