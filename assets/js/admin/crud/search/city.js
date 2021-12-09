import { updateDepartmentSearch, getDepartement } from '../search/utils'

if(document.querySelector('.admin_city_search')){
    let departmentContainer = document.querySelector('.department-container')

    if(document.getElementById('admin_city_edit')){
        let departmentFormType = departmentContainer.querySelector('#admin_city_department_id')
    
        getDepartement(departmentFormType.value, departmentContainer)
    }
    
    updateDepartmentSearch(departmentContainer, 'admin_city_department_id')
}