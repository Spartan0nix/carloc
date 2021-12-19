import { generateAlert } from './alert'

export class Search extends HTMLElement {
    connectedCallback() {
        this.classList.add('search');
        /**
         * Retrieve search configuration element
         */
        const placeholder = this.dataset.placeholder;
        const id = this.dataset.id;
        const name = this.dataset.name;
        const query = this.dataset.query;

        /**
         * Add the default HTML structure of the search element
         */
        this.innerHTML = `<input type="text" placeholder="${placeholder}" id="${id}">
                            <span class="search-remove-filter">
                                <svg xmlns="http://www.w3.org/2000/svg" class="icon icon-tabler icon-tabler-square-minus" width="25" height="25" viewBox="0 0 24 24" stroke-width="1" stroke="currentColor" fill="none" stroke-linecap="round" stroke-linejoin="round">
                                    <path stroke="none" d="M0 0h24v24H0z" fill="none"/>
                                    <rect x="4" y="4" width="16" height="16" rx="2" />
                                    <line x1="9" y1="12" x2="15" y2="12" />
                                </svg>
                            </span>
                            <span class="close-search">
                                <svg xmlns="http://www.w3.org/2000/svg" class="icon icon-tabler icon-tabler-caret-down" width="25" height="25" viewBox="0 0 24 24" stroke-width="1" stroke="currentColor" fill="none" stroke-linecap="round" stroke-linejoin="round">
                                    <path stroke="none" d="M0 0h24v24H0z" fill="none"/>
                                    <path d="M18 15l-6 -6l-6 6h12" transform="rotate(180 12 12)" />
                                </svg>
                            </span>
                            <input type="hidden" name="${name}">
                            <div class="search-result"></div> 
                        `;
        
        /**
         * Array containing ids of the desire selected element by default
         */
        this.dataset.selected != undefined ? this.dataSelected = this.dataset.selected : this.dataSelected = '';
        this.URL = `/api/search${this.dataset.query}`
    }

    /**
     * Update the search result container
     * @param {Object} data 
     */
    async updateSearchResult(data) {
        let container = this.children[4]
        // Remove any leftover content
        container.innerHTML = '';

        data.forEach(element => {
            let result = "";
            // If no id field is return, this will still wrap each <p> inside a <div> element
            result = `<div class='search-result-element'>`
            Object.keys(element).forEach(item => {
                if (item === "id") {
                    result = `<div class='search-result-element' data-elementid='${element[item]}'>`
                } else {
                    result += `<p class='search-result-row'>${element[item]}</p>`
                }
            })
            result += "</div>"
            // Update the search result container innerHTML
            container.innerHTML += result;
        })
    }

    /**
     * Keep track of the selected element 
     * @param {*} event 
     */
    addElement(event) {
        let searchContainer = event.closest('.search');
        let userInput = searchContainer.querySelector('input[type="text"]')
        let form_input = searchContainer.querySelector('input[type="hidden"]')
        let removeFilterIcon = searchContainer.querySelector('.search-remove-filter');
        let element = event.closest('.search-result-element');

        userInput.value = element.querySelector('p').innerHTML
        form_input.value = element.dataset.elementid;
        removeFilterIcon.style.display = 'block';
        this.toggleSearchResult(event.closest('.search'));
    }

    /**
     * Add the previous selected element after the filter was applied
     * @param {*} selected 
     */
    setSelected(selected) {
        let keys = Object.keys(selected);
        let userInput = this.children[0];
        let removeFilterIcon = this.children[1];
        let form_input = this.children[3];

        userInput.value = selected[keys[1]];
        form_input.value = selected[keys[0]];
        removeFilterIcon.style.display = 'block';
    }

    removeElement(container){
        let userInput = container.querySelector('input[type="text"]');
        let removeFilterIcon = container.querySelector('.search-remove-filter');
        let form_input = container.querySelector('input[type="hidden"]');

        userInput.value = '';
        form_input.value = '';
        removeFilterIcon.style.display = 'none';
    }

    /**
     * Toggle the search result container by adding the open-search-result class to the .search parent
     */
    toggleSearchResult(searchContainer) {
        searchContainer.classList.toggle('open-search-result')
    }

    async notFound(error) {
        let container = this.children[4]
        container.innerHTML = `<div class='search-result-element search-not-found'>
                                    ${error.message}
                                </div>`
    }
}

export class DynamicSearch extends Search {
    connectedCallback() {
        super.connectedCallback();

        /**
         * Set a timeout to allow the rendering in the dom of the previous HTML
         */
        setTimeout(() => {
            let input = this.children[0]
            let timer;

            if(this.dataSelected != ""){
                let selected = JSON.parse(this.dataSelected);
                this.setSelected(selected);
            }

            /**
             * Toggle the search result container
             */
            input.addEventListener('click', (event) => {
                this.toggleSearchResult(event.target.closest('.search'));
            })

            /**
             * Fetch the data
             */
            input.addEventListener('input', () => {
                // Clear the timer
                clearTimeout(timer);
                /**
                 * Wait 300ms before fetching the data to prevent multiple request
                 */
                timer = setTimeout(async () => {
                    try {
                        if(this.dataset.require){
                            var response = await fetch(`${this.URL}?q=${encodeURI(input.value)}&require=${encodeURI(this.dataset.require)}`)    
                        } else {
                            var response = await fetch(`${this.URL}?q=${encodeURI(input.value)}`)
                        }
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

            document.addEventListener('click', (event) => {
                if (event.target.closest('.search-result-element') && !event.target.closest('.search-not-found')) {
                    event.stopImmediatePropagation();
                    this.addElement(event.target);
                }
                if(event.target.closest('.search-remove-filter')) {
                    event.stopImmediatePropagation();
                    this.removeElement(event.target.closest('.search'));
                }
            })
        }, 50)
    }
}

export class StaticSearch extends Search {
    connectedCallback() {
        super.connectedCallback();
        const data = [];

        setTimeout(async () => {
            let input = this.children[0]
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
                        this.setSelected(selected);
                    }
                } else {
                    let error = await response.json()
                    this.notFound(error)
                }
            } catch (error) {
                generateAlert("error", "Erreur durant la résolution de la requête.")
            }

            input.addEventListener('input', () => {
                // Clear the timer
                clearTimeout(timer);
                /**
                 * Wait 300ms before updating the data
                 */
                timer = setTimeout(async () => {
                    if(input.value === "") {
                        this.updateSearchResult(this.data);
                        return;
                    }
                    let result = [];
                    this.data.forEach(element => {
                        let key = Object.keys(element)[1];
                        if(element[key].includes(input.value)) {
                            result.push(element);
                        }
                    })
                    this.updateSearchResult(result);
                }, 300)
            })

            document.addEventListener('click', (event) => {
                if (event.target.closest('.search-result-element') && !event.target.closest('.search-not-found')) {
                    event.stopImmediatePropagation();
                    this.addElement(event.target);
                }
                if(event.target.closest('.search-remove-filter')) {
                    event.stopImmediatePropagation();
                    this.removeElement(event.target.closest('.search'));
                }
            })
        }, 50)
    }
}