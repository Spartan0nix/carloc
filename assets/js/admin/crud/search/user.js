import { getDefault, updateDepartmentCitySearch } from '../search/utils'

if(document.querySelector('.admin_user_search')){
    let departmentContainer = document.querySelector('.department-container')
    let cityContainer = document.querySelector('.city-container')

    if(document.getElementById('admin_user_edit')){
        let department_id = document.getElementById('admin_user_department_id').value
        let city_id = document.getElementById('admin_user_city_id').value

        getDefault(`/api/search/department_id?id=${department_id}`, 'department_input', ['name'])
        getDefault(`/api/search/city_id?id=${city_id}`, 'city_input', ['name'])
    }
    
    updateDepartmentCitySearch(departmentContainer, 'admin_user_department_id', cityContainer, 'admin_user_city_id')
}