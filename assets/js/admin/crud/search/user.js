import { updateFormSearch, getDepartement, getCity } from '../search/utils'

if(document.querySelector('.admin_user_search')){
    let departmentContainer = document.querySelector('.department-container')
    let cityContainer = document.querySelector('.city-container')

    if(document.getElementById('admin_user_edit')){
        let departmentFormType = departmentContainer.querySelector('#admin_user_department_id')
        let cityFormType = cityContainer.querySelector('#admin_user_city_id')
    
        getDepartement(departmentFormType.value, departmentContainer)
        getCity(cityFormType.value, cityContainer)
    }
    
    updateFormSearch(departmentContainer, 'admin_user_department_id', cityContainer, 'admin_user_city_id')
}