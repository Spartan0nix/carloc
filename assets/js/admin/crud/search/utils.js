export function updateFormSearch(container, form_input_id) {
    let input_hidden = container.querySelector('input[type="hidden"]')
    let form_input = container.querySelector(`#${form_input_id}`)
    let value = ''
    
    Object.defineProperty(input_hidden, "value", {
        set(newValue) {
            value = newValue
            form_input.value = newValue
        },
        get() {
            return value
        }
    })
}

export function updateDepartmentCitySearch(departmentContainer, departmentInputFormId, cityContainer, cityInputFormId) {
    let departmentInputHidden = departmentContainer.querySelector('input[type="hidden"]')
    let departmentFormType = departmentContainer.querySelector(`#${departmentInputFormId}`)
    let departmentId = ''
    let cityInput = cityContainer.querySelector('#city_input')
    let cityInputHidden = cityContainer.querySelector('input[type="hidden"]')
    let cityFormType = cityContainer.querySelector(`#${cityInputFormId}`)

    cityInput.disabled = true;

    Object.defineProperty(departmentInputHidden, "value", {
        set(newValue) {
            cityInput.disabled = false;
            cityInput.closest('.search').dataset.require = newValue;
            departmentId = newValue;
            departmentFormType.value = newValue;
        },
        get(){
            return departmentId;
        }
    });

    Object.defineProperty(cityInputHidden, "value", {
        set(newValue) {
            cityFormType.value = newValue;
        },
        get(){
            return cityFormType.value;
        }
    });
}

export async function getDefault(URL, input_id, keys) {
    let response = await fetch(URL)

    if(response.ok) {
        let json = await response.json()
        let data = json.data
        let input = document.getElementById(input_id)
        let index = 0

        if(keys.length > 1){
            keys.forEach(key => {
                index++
                input.value = input.value.concat(data[key])
                if(index != keys.length){
                    input.value = input.value.concat(' - ')
                }
            })
        } else {
            input.value = data[keys[0]]
        }
    }
}

export async function getDepartement(department_id, departmentContainer) {
    let URL = `/api/search/department_id?id=${department_id}`
    let response = await fetch(URL)

    if(response.ok){
        let json = await response.json()
        let department = json.department
        departmentContainer.querySelector('#department_input').value = department.name
    }
}

export async function getCity(city_id, cityContainer) {
    let URL = `/api/search/city_id?id=${city_id}`
    let response = await fetch(URL)

    if(response.ok) {
        let json = await response.json()
        let city = json.city
        cityContainer.querySelector('#city_input').value = city.name
    }
}