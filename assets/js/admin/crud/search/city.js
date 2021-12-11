import { getDefault, updateFormSearch } from '../search/utils'

if(document.querySelector('.admin_city_search')){
    let departmentContainer = document.querySelector('.department-container')

    if(document.getElementById('admin_city_edit')){
        let department_id = document.getElementById('admin_city_department_id').value
        getDefault(`/api/search/department_id?id=${department_id}`, 'department_input', ['name'])
    }
    
    updateFormSearch(departmentContainer, 'admin_city_department_id')
}