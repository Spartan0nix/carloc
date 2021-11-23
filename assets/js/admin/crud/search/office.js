import { updateFormSearch, getDepartement, getCity } from '../search/utils'

if(document.getElementById('admin_office_search')) {
    let departmentContainer = document.querySelector('.department-container')
    let cityContainer = document.querySelector('.city-container')

    if(document.getElementById('admin_office_edit')){
        let departmentFormType = departmentContainer.querySelector('#admin_office_department_id')
        let cityFormType = cityContainer.querySelector('#admin_office_city_id')

        getDepartement(departmentFormType.value, departmentContainer)
        getCity(cityFormType.value, cityContainer)
    }

    updateFormSearch(departmentContainer, 'admin_office_department_id', cityContainer, 'admin_office_city_id')
}