import { getDefault, updateFormSearch } from '../search/utils'

if(document.querySelector('.admin_car_search')) {
    let brand_container = document.querySelector('.brand-container')
    let model_container = document.querySelector('.model-container')
    let fuel_container = document.querySelector('.fuel-container')
    let gearbox_container = document.querySelector('.gearbox-container')
    let color_container = document.querySelector('.color-container')
    let office_container = document.querySelector('.office-container')

    if(document.getElementById('admin_rent_edit')){
        let brand_id = document.getElementById('admin_car_brand_id').value
        getDefault(`/api/search/brand_id?id=${brand_id}`, 'brand_input', ['brand'])

        let model_id = document.getElementById('admin_car_model_id').value
        getDefault(`/api/search/model_id?id=${model_id}`, 'model_input', ['model'])

        let fuel_id = document.getElementById('admin_car_fuel_id').value
        getDefault(`/api/search/fuel_id?id=${fuel_id}`, 'fuel_input', ['fuel'])

        let gearbox_id = document.getElementById('admin_car_gearbox_id').value
        getDefault(`/api/search/gearbox_id?id=${gearbox_id}`, 'gearbox_input', ['gearbox'])

        let color_id = document.getElementById('admin_car_color_id').value
        getDefault(`/api/search/color_id?id=${color_id}`, 'color_input', ['color'])

        let office_id = document.getElementById('admin_car_office_id').value
        getDefault(`/api/search/office_id?id=${office_id}`, 'office_input', ['street'])
    }   

    updateFormSearch(brand_container, 'admin_car_brand_id')
    updateFormSearch(model_container, 'admin_car_model_id')
    updateFormSearch(fuel_container, 'admin_car_fuel_id')
    updateFormSearch(gearbox_container, 'admin_car_gearbox_id')
    updateFormSearch(color_container, 'admin_car_color_id')
    updateFormSearch(office_container, 'admin_car_office_id')
}