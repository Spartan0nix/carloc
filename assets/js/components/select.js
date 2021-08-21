import { generateAlert } from './alert'

export class Select extends HTMLElement {
    connectedCallback() {
        this.classList.add('search');
        /**
         * Retrieve search configuration element
         */
        const placeholder = this.dataset.placeholder;
        const id = this.dataset.id;
        const name = this.dataset.name;
        const type = this.dataset.type;
        const URL = "";
        const dataSelected = "";


        /**
         * Choose between the different type of search
         * @var String type
         */
        switch (type) {
            case "brand":
                this.URL = '/api/search/brands/';
                this.type = 'brand';
                break;
            case "model":
                this.URL = '/api/search/models/';
                this.type = 'model';
                break;
            case "type":
                this.URL = '/api/search/types/';
                this.type = 'type';
                break;
            case "fuel":
                this.URL = '/api/search/fuels/';
                this.type = 'fuel';
                break;
            case "gearbox":
                this.URL = '/api/search/gearboxs/';
                this.type = 'gearbox';
                break;
            default:
                break;
        }

        /**
         * Add the default HTML structure of the search element
         */
        this.innerHTML = `<input type="text" placeholder="${placeholder}" id="${id}">
                            <span class="close-search">
                                <svg xmlns="http://www.w3.org/2000/svg" class="icon icon-tabler icon-tabler-caret-down" width="25" height="25" viewBox="0 0 24 24" stroke-width="1" stroke="currentColor" fill="none" stroke-linecap="round" stroke-linejoin="round">
                                    <path stroke="none" d="M0 0h24v24H0z" fill="none"/>
                                    <path d="M18 15l-6 -6l-6 6h12" transform="rotate(180 12 12)" />
                                </svg>
                            </span>
                            <fieldset class="search-result"></fieldset> 
                        `;
        this.dataset.selected != undefined ? this.dataSelected = this.dataset.selected : this.dataSelected = '';
    }

    /**
     * Update the search result container
     * @param {Object} data 
     */
    updateSearchResult(data) {
        let fieldset = this.children[2];

        // Remove every unchecked input
        let options = fieldset.querySelectorAll('input[type="checkbox"]');
        options.forEach(option => {
            if (!option.checked) {
                let wrapper = option.closest('.select-result-element');
                wrapper.parentNode.removeChild(wrapper);
            }
        })
        data.forEach(element => {
            let array = Object.keys(element).map(function (key) { return element[key]; });
            let id = array[0];
            let attribute = array[1];

            // Check if the option is already present
            if (!fieldset.querySelector(`#${attribute}`)) {
                // Update the search result fielset with a new input and a new label
                let wrapper = document.createElement('div');
                wrapper.classList.add('select-result-element');

                let inputElement = document.createElement('input');
                inputElement.type = 'checkbox';
                inputElement.name = `${this.type}_filter[${id}]`;
                inputElement.value = id;
                inputElement.id = attribute;
                let labelElement = document.createElement('label');
                labelElement.setAttribute('for', attribute)
                labelElement.innerHTML = attribute;

                fieldset.appendChild(wrapper)
                wrapper.appendChild(inputElement);
                wrapper.appendChild(labelElement);
            }
        })
    }

    /**
     * Add the previous selected elements after the filter was applied
     * @param {*} selected 
     */
    setElement(selected) {
        console.log(selected);
    }
    /**
     * Toggle the search result container by adding the open-search-result class to the .search parent
     */
    toggleSearchResult(searchContainer) {
        searchContainer.classList.toggle('open-search-result')
    }

    async notFound(error) {
        let container = this.children[3]
        container.innerHTML = `<div class='search-result-element search-not-found'>
                                    ${error.message}
                                </div>`
    }
}

export class DynamicSelect extends Select {
    connectedCallback() {
        super.connectedCallback();

        /**
         * Set a timeout to allow the rendering in the dom of the previous HTML
         */
        setTimeout(() => {
            let children = Array.from(this.children)
            let input = children[0]
            let timer;

            if(this.dataSelected != ""){
                let selected = JSON.parse(this.dataSelected);
                let fieldset = this.children[2];
                this.updateSearchResult(selected);

                let inputs = fieldset.querySelectorAll('input[type="checkbox"]');
                inputs.forEach(input => {
                    input.checked = true;
                })

            }

            /**
             * Toggle the search result container
             */
            input.addEventListener('click', (event) => {
                this.toggleSearchResult(event.target.closest('.search'));
            })

            // /**
            //  * Fetch the data
            //  */
            input.addEventListener('input', () => {
                // Clear the timer
                clearTimeout(timer);
                /**
                 * Wait 300ms before fetching the data to prevent multiple request
                 */
                timer = setTimeout(async () => {
                    try {
                        let response = await fetch(`${this.URL}?q=${encodeURI(input.value)}`)
                        if (response.ok) {
                            let data = await response.json()
                            this.updateSearchResult(data.data)
                        } else {
                            let error = await response.json()
                            this.notFound(error)
                        }
                    } catch (error) {
                        generateAlert("error", "Erreur durant la résolution de la requête.")
                    }
                }, 300)
            })
        }, 50)
    }
}

export class StaticSelect extends Select {
    connectedCallback() {
        super.connectedCallback();

        setTimeout(async () => {
            let children = Array.from(this.children)
            let input = children[0]
            let timer;
            /**
             * Toggle the search result container
             */
            input.addEventListener('click', (event) => {
                this.toggleSearchResult(event.target.closest('.search'));
            })

            /**
             * Fetch the data
             */
            try {
                let response = await fetch(`${this.URL}?q=`)
                if (response.ok) {
                    let data = await response.json()
                    this.data = data.data;
                    this.updateSearchResult(this.data)
                    if(this.dataSelected != ""){
                        let selected = JSON.parse(this.dataSelected);
                        let fieldset = this.children[2];

                        selected.forEach(element => {
                            let keys = Object.keys(element);
                            fieldset.querySelector(`#${element[keys[1]]}`).checked = true;
                        })
                    }
                } else {
                    let error = await response.json()
                    this.notFound(error)
                }
            } catch (error) {
                generateAlert("error", "Erreur durant la résolution de la requête.")
                console.log(error)
            }

            input.addEventListener('input', () => {
                // Clear the timer
                clearTimeout(timer);
                /**
                 * Wait 300ms before updating the data
                 */
                timer = setTimeout(() => {
                    if (input.value === "") {
                        this.updateSearchResult(this.data);
                        return;
                    }
                    let result = [];
                    this.data.forEach(element => {
                        let key = Object.keys(element)[1];
                        if (element[key].includes(input.value)) {
                            result.push(element);
                        }
                    })
                    this.updateSearchResult(result);
                }, 300)
            })
        }, 50)
    }
}