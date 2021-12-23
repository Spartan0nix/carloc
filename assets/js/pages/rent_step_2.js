if(document.getElementById('rent_step_2')){
    let reset_btn = document.getElementById('btn-reset-filter')

    reset_btn.addEventListener('click' , (event) => {
        event.preventDefault()

        let brand_form_container = document.getElementById('car_filter_brand_id')
        let brand_select_container = document.getElementById('select-brand-container').querySelector('.select-input-container')
        brand_form_container.innerHTML = ""
        brand_select_container.querySelectorAll('.select-input-tag').forEach(tag => {
            brand_select_container.removeChild(tag)
        })

        let model_form_container = document.getElementById('car_filter_model_id')
        let model_select_container = document.getElementById('select-model-container').querySelector('.select-input-container')
        model_form_container.innerHTML = ""
        model_select_container.querySelectorAll('.select-input-tag').forEach(tag => {
            model_select_container.removeChild(tag)
        })

        let type_form_container = document.getElementById('car_filter_type_id')
        let type_select_container = document.getElementById('select-type-container').querySelector('.select-input-container')
        type_form_container.innerHTML = ""
        type_select_container.querySelectorAll('.select-input-tag').forEach(tag => {
            type_select_container.removeChild(tag)
        })

        let fuel_form_container = document.getElementById('car_filter_fuel_id')
        let fuel_select_container = document.getElementById('select-fuel-container').querySelector('.select-input-container')
        fuel_form_container.innerHTML = ""
        fuel_select_container.querySelectorAll('.select-input-tag').forEach(tag => {
            fuel_select_container.removeChild(tag)
        })

        let gearbox_form_input = document.getElementById('car_filter_gearbox_id')
        let gearbox_search_container = document.getElementById('search-gearbox-container')
        gearbox_form_input.value = ""
        let tag = gearbox_search_container.querySelector('.search-input-tag')
        if(tag)  {
            tag.parentNode.removeChild(tag)
        }
    })
}