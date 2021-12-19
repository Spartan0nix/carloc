export class Search extends HTMLElement 
{
    constructor() {
        super()

        /**
         * Phase : Build the element
         */
        this.default_tag = []
        let form_input_id = this.dataset.id
        // Check if a form_input was passed as an argument
        if(form_input_id && form_input_id != '') {
            // Retrieve the default form_input element
            this.form_input = document.getElementById(form_input_id)
            this.default_tag.push({
                id: this.form_input.value
            })
        } else {
            // If a form_input was not passed as an argument, build one
            let input = document.createElement('input')
            input.type = 'text'
            input.style.display = 'none'
            input.classList.add('search-input-form')
            input.name = this.dataset.name

            this.appendChild(input)
            this.form_input = this.querySelector('.search-input-form')
        }

        // Create a container containing the user input field and future tag(s)
        let search_input_container = document.createElement('div')
        search_input_container.classList.add('search-input-container')

        // Create the user input field
        let input = document.createElement('input')
        input.type = "text"
        input.name = "search-input"
        input.classList.add('search-input')
        input.id = 'search-input'
        let placeholder = this.dataset.placeholder
        if(placeholder && placeholder != ''){
            input.placeholder = placeholder
        } else {
            input.placeholder = "Sélectionner une entrée."
        }
        search_input_container.appendChild(input)

        // Create a container containing a list of the values returned
        let search_result_container = document.createElement('div')
        search_result_container.classList.add('search-result-container')
        let ul = document.createElement('ul')
        ul.classList.add('search-result-list')
        search_result_container.appendChild(ul)

        this.appendChild(search_input_container)
        this.appendChild(search_result_container)

        /**
         * Phase : Define class properties
         */
        this.root = this
        this.input_container = this.querySelector('.search-input-container')
        this.input = this.input_container.querySelector('input[type="text"]')
        this.result_container = this.querySelector('.search-result-container')
        this.result_list = this.result_container.querySelector('.search-result-list')
        this.list_open = false
        this.url = this.dataset.url
        this.data = []
    }

    connectedCallback() {
        // Add a listener to update the click event on the current tag
        if(this.input_container){
            this.input_container.addEventListener('update-tag-listener', () => {
                let tag = this.input_container.querySelector('.search-input-tag')
                if(tag.dataset.listener != 'true') {
                    tag.dataset.listener = 'true'
                    tag.addEventListener('click', (event) => {
                        let confirmation = confirm('Supprimer ?')
                        if(confirmation) {
                            this.input_container.removeChild(tag)
                            this.form_input.value = ""
                        }
                    })
                }
            })
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

                    if(this.default_tag.length != 0) {
                        if(element[keys[0]].toString() === this.default_tag[0].id.toString()) {
                            let tag = this.buildTag(element[keys[0]], element[keys[1]])
                            this.input_container.insertBefore(tag, this.input)
                            this.input_container.dispatchEvent(new Event('update-tag-listener'))
                        }
                    }
                })
            }
        })()
        
        this.input.addEventListener('click', () => {
            this.root.classList.toggle('display-results')
            this.list_open = !this.list_open

            if(this.input.closest('.search-element').classList.contains('display-results')) {
                // Focus on the input
                this.input.focus()

                // Retrieve all result from the result_container
                const results_list = this.result_container.querySelectorAll('.search-result-item')
                this.resultListItemsListener(results_list)
            }
        })

        this.input.addEventListener('keydown', (event) => {
            // Check if the backspace was pressed
            if(event.code === 'Backspace' && this.input.value === '') {
                // Retrieve all the tags from the input_container
                let tag = this.input_container.querySelector('.search-input-tag')
                if(tag.length != 0) {
                    // Remove the tag
                    this.input_container.removeChild(tag)
                    this.form_input.value = ""

                    // Close the result list
                    this.root.classList.remove('display-results')
                    this.list_open = false
                } 
            }
        })

        window.addEventListener('click', (event) => {
            if(this.list_open) {
                if(!event.target.closest('.search-element')) {
                    this.root.classList.remove('display-results')
                    this.list_open = false
                }
            }
        })
    }

    /**
     * Build tag to add to the input container
     * @param {String} id 
     * @param {String} content 
     * @returns {Object}
     */
    buildTag(id, content) {
        let tag = document.createElement('div')
        tag.classList.add('search-input-tag')
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
        li.classList.add('search-result-item')
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
                // Check if the clicked result has not been already selected
                if(this.form_input.value != event.target.id){
                    // Update the input_from test input value
                    this.form_input.value = event.target.id

                    // Remove the old tag
                    let old_tag = this.input_container.querySelector('.search-input-tag')
                    if(old_tag) {
                        this.input_container.removeChild(old_tag)
                    }

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

export class SearchStatic extends Search
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

            const results_list = this.result_container.querySelectorAll('.search-result-item')
            this.resultListItemsListener(results_list)
        })
    }
}

export class SearchDynamic extends Search
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

                const results_list = this.result_container.querySelectorAll('.search-result-item')
                this.resultListItemsListener(results_list)
            }, 200)
        })
    }
}