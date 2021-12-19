export class Select extends HTMLElement 
{
    constructor() {
        super()

        /**
         * Phase : Build the element
         */
        this.default_tags = []
        let checkbox_container = this.dataset.id
        // Check if a checkbox_container_id was passed as an argument
        if(checkbox_container && checkbox_container != '') {
            // Retrieve the checkbox_container element
            this.checkbox_container = document.getElementById(checkbox_container)
            let checkboxes = this.checkbox_container.querySelectorAll('input[type="checkbox"]')
            checkboxes.forEach(checkbox => {
                // Retrieve the label associate with the current checkbox
                let label = this.checkbox_container.querySelector(`label[for="${checkbox.id}"]`)
                // If the checkbox is checked, retrieve the value of the label and checkbox
                // to later build a tag to inform the user that this value is checked by default
                if(checkbox.checked) {
                    this.default_tags.push({
                        id: checkbox.value,
                        value: label.innerHTML
                    })
                } else {
                    this.checkbox_container.removeChild(checkbox)
                }
                this.checkbox_container.removeChild(label)
            })

        } else {
            // If a checkboc_container was not passed as an argument, build one
            let container = document.createElement('div')
            container.classList.add('select-checkbox-container')

            this.appendChild(container)
            this.checkbox_container = this.querySelector('.select-checkbox-container')
        }

        // Create a container containing the user input field and future tag(s)
        let select_input_container = document.createElement('div')
        select_input_container.classList.add('select-input-container')

        // Create the user input field
        let input = document.createElement('input')
        input.type = "text"
        input.name = "select-input"
        input.classList.add('select-input')
        input.id = 'select-input'
        let placeholder = this.dataset.placeholder
        if(placeholder && placeholder != ''){
            input.placeholder = placeholder
        } else {
            input.placeholder = "Sélectionner une entrée(s)."
        }
        select_input_container.appendChild(input)

        // Create a container containing a list of the values returned
        let select_result_container = document.createElement('div')
        select_result_container.classList.add('select-result-container')
        let ul = document.createElement('ul')
        ul.classList.add('select-result-list')
        select_result_container.appendChild(ul)

        this.appendChild(select_input_container)
        this.appendChild(select_result_container)

        /**
         * Phase : Define class properties
         */
        this.root = this
        this.input_container = this.querySelector('.select-input-container')
        this.input = this.input_container.querySelector('input[type="text"]')
        this.result_container = this.querySelector('.select-result-container')
        this.result_list = this.result_container.querySelector('.select-result-list')
        this.list_open = false
        this.url = this.dataset.url
        this.data = []
        this.array_name = this.dataset.name
    }

    connectedCallback() {
        // Add a listener to update the click event on each tag when updating the input_container
        this.input_container.addEventListener('update-tag-listener', () => {
            let tags = this.input_container.querySelectorAll('.select-input-tag')
            tags.forEach(tag => {
                if(tag.dataset.listener != 'true') {
                    tag.dataset.listener = 'true'
                    tag.addEventListener('click', (event) => {
                        let confirmation = confirm('Supprimer ?')
                        if(confirmation) {
                            let id = tag.dataset.reference
                            let checkbox = this.checkbox_container.querySelector(`#${this.checkbox_container.id}_${id}`)
                            this.input_container.removeChild(tag)
                            this.checkbox_container.removeChild(checkbox)
                        }
                    })
                }
            })
        })

        // If their was default_tags, build the corresponding tags
        if(this.default_tags.length != 0) {
            this.default_tags.forEach(element => {;
                let li = this.buildTag(element.id, element.value)
                this.input_container.insertBefore(li, this.input)
                this.input_container.dispatchEvent(new Event('update-tag-listener'))
            });
        }

        // Retrieve values from the url passed as an argument
        (async () => {
            let response = await fetch(`/api/search${this.url}?q=`)
            
            if(response.ok) {
                let json = await response.json()
                this.data = Array.from(json.data)

                // Build the corresponding item in the list
                this.data.forEach(element => {
                    let keys = Object.keys(element)
                    let li = this.buildListItem(element[keys[0]], element[keys[1]])
                    this.result_list.appendChild(li)
                })
            }
        })()
        
        this.input.addEventListener('click', () => {
            this.root.classList.toggle('display-results')
            this.list_open = !this.list_open

            if(this.input.closest('.select-element').classList.contains('display-results')) {
                // Focus on the input
                this.input.focus()

                // Retrieve all result from the result_container
                const results_list = this.result_container.querySelectorAll('.select-result-item')
                this.resultListItemsListener(results_list)
            }
        })

        this.input.addEventListener('keydown', (event) => {
            // Check if the backspace was pressed
            if(event.code === 'Backspace' && this.input.value === '') {
                // Retrieve all the tags from the input_container
                let tags = this.input_container.querySelectorAll('.select-input-tag')
                if(tags.length != 0) {
                    // Retrieve the last tag
                    let last_tag = tags[tags.length - 1]
                    let id = last_tag.dataset.reference
                    // Retrieve the associate <input> from the checkbox_container
                    let checkbox = this.checkbox_container.querySelector(`input[value="${id}"]`)
                    
                    // Remove the tag and the <input>
                    this.input_container.removeChild(last_tag)
                    this.checkbox_container.removeChild(checkbox)

                    // Close the result list
                    this.root.classList.remove('display-results')
                    this.list_open = false
                } 
            }
        })

        window.addEventListener('click', (event) => {
            if(this.list_open) {
                if(!event.target.closest('.select-element')) {
                    this.root.classList.remove('display-results')
                    this.list_open = false
                }
            }
        })

    }

    /**
     * Build an option element to add to the select element
     * @param {String} id 
     * @param {String} content 
     * @returns {Object}
     */
    buildCheckbox(id) {
        let checkbox = document.createElement('input')
        let checkbox_container_id = this.checkbox_container.id
        checkbox.type = "checkbox"
        checkbox.checked = true
        checkbox.value = id
        checkbox.id = `${checkbox_container_id}_${id}`
        checkbox.name = this.array_name

        return checkbox
    }

    /**
     * Build tag to add to the input container
     * @param {String} id 
     * @param {String} content 
     * @returns {Object}
     */
    buildTag(id, content) {
        let tag = document.createElement('div')
        tag.classList.add('select-input-tag')
        tag.dataset.reference = id
        tag.innerHTML = content

        return tag
    }

    /**
     * Build a list element
     * @param {String} id 
     * @param {String} content 
     * @returns {Object}
     */
    buildListItem(id, content) {
        let li = document.createElement('li')
        li.classList.add('select-result-item')
        li.id = id
        li.innerHTML = content

        return li
    }
    
    /**
     * Listen for click event for earch items in the results list and build the corresponding option and tag element
     * @param {Object} results_list 
     */
    resultListItemsListener(results_list) {
        results_list.forEach(result => {
            result.addEventListener('click', (event) => {

                // Retrieve all options from the <select> element
                let selected_options = this.checkbox_container.querySelectorAll('input[type="checkbox"]:checked')
                // Build an array compose of each options id
                let selected_options_values = Array.from(selected_options).map((element) => {
                    return element.value
                })

                // Check if the clicked result has not been already selected
                if(!selected_options_values.includes(event.target.id)){
                    // Add an <input> to the checkbox_container
                    let checkbox = this.buildCheckbox(event.target.id, event.target.innerHTML)
                    this.checkbox_container.appendChild(checkbox)

                    // Add an <div> to the input_container
                    let tag = this.buildTag(event.target.id, event.target.innerHTML)
                    this.input_container.insertBefore(tag, this.input)
                    this.input_container.dispatchEvent(new Event('update-tag-listener'))

                    this.root.classList.remove('display-results')
                }
            })
        })
    }
}

export class SelectStatic extends Select
{
    constructor() {
        super()
    }

    connectedCallback() {
        super.connectedCallback()

        this.input.addEventListener('input', (event) => {
            // Reset the content of the result_list
            this.result_list.innerHTML = ''
            // Filter the results based on the user input
            let filtered_results = this.data.filter(result => {
                let keys = Object.keys(result)
                return result[keys[1]].toLowerCase().includes(event.target.value.toLowerCase())
            })
            
            filtered_results.forEach(result => {
                let keys = Object.keys(result)
                let li = this.buildListItem(result[keys[0]], result[keys[1]])
                this.result_list.appendChild(li)
            })

            const results_list = this.result_container.querySelectorAll('.select-result-item')
            this.resultListItemsListener(results_list)
        })
    }
}

export class SelectDynamic extends Select
{
    constructor() {
        super()
    }

    connectedCallback() {
        super.connectedCallback()

        var timeout = null
        this.input.addEventListener('input', (event) => {
            // Reset the content of the result_list
            this.result_list.innerHTML = ''
            // Filter the results based on the user input
            clearTimeout(timeout)

            timeout = setTimeout(async () => {
                let response = await fetch(`/api/search${this.url}?q=${event.target.value}`)

                if(response.ok) {
                    let json = await response.json()

                    json.data.forEach(element => {
                        let keys = Object.keys(element)
                        let li = this.buildListItem(element[keys[0]], element[keys[1]])
                        this.result_list.appendChild(li)
                    })
                }

                const results_list = this.result_container.querySelectorAll('.select-result-item')
                this.resultListItemsListener(results_list)
            }, 200)
        })
    }
}