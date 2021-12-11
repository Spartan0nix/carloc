import { getDefault, updateFormSearch } from '../search/utils'

if(document.querySelector('.admin_rent_search')) {
    let pickupOfficeContainer = document.querySelector('.pickup-office-container')
    let returnOfficeContainer = document.querySelector('.return-office-container')
    let userContainer = document.querySelector('.user-container')
    let carContainer = document.querySelector('.car-container')
    let statusContainer = document.querySelector('.status-container')

    if(document.getElementById('admin_rent_edit')){
        let pickup_office_id = document.getElementById('admin_rent_pickup_office_id').value
        getDefault(`/api/search/office_id?id=${pickup_office_id}`, 'pickup_office_input', ['street'])

        let return_office_id = document.getElementById('admin_rent_return_office_id').value
        getDefault(`/api/search/office_id?id=${return_office_id}`, 'return_office_input', ['street'])

        let user_id = document.getElementById('admin_rent_user_id').value
        getDefault(`/api/search/user_id?id=${user_id}`, 'user_input', ['email'])

        let car_id = document.getElementById('admin_rent_car_id').value
        getDefault(`/api/search/car_id?id=${car_id}`, 'car_input', ['brand', 'model'])

        let status_id = document.getElementById('admin_rent_status_id').value
        getDefault(`/api/search/status_id?id=${status_id}`, 'status_input', ['status'])
    }   

    updateFormSearch(pickupOfficeContainer, 'admin_rent_pickup_office_id')
    updateFormSearch(returnOfficeContainer, 'admin_rent_return_office_id')
    updateFormSearch(userContainer, 'admin_rent_user_id')
    updateFormSearch(carContainer, 'admin_rent_car_id')
    updateFormSearch(statusContainer, 'admin_rent_status_id')
}