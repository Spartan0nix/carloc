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
        const type = this.dataset.type;
        const URL = "";


        /**
         * Choose between the different type of search
         * @var String type
         */
        switch (type) {
            case "office":
                this.URL = '/recherche/offices/';
                break;
            case "brand":
                this.URL = '/recherche/brands/';
                break;
            case "model":
                this.URL = '/recherche/models/';
                break;
            case "type":
                this.URL = '/recherche/types/';
                break;
            case "fuel":
                this.URL = '/recherche/fuels/';
                break;
            case "gearbox":
                this.URL = '/recherche/gearboxs/';
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
                            <input type="hidden" name="${name}">
                            <div class="search-result"></div> 
                        `;

    }

    /**
     * Update the search result container
     * @param {Object} data 
     */
    async updateSearchResult(data) {
        let container = this.children[3]
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

    addElement(event) {
        let searchContainer = event.closest('.search');
        let userInput = searchContainer.querySelector('input[type="text"]')
        let form_input = searchContainer.querySelector('input[type="hidden"]')
        let element = event.closest('.search-result-element');

        userInput.value = element.querySelector('p').innerHTML
        form_input.value = element.dataset.elementid;
        this.toggleSearchResult(event.closest('.search'));
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

export class DynamicSearch extends Search {
    connectedCallback() {
        super.connectedCallback();

        /**
         * Set a timeout to allow the rendering in the dom of the previous HTML
         */
        setTimeout(() => {
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

            document.addEventListener('click', (event) => {
                if (event.target.closest('.search-result-element') && !event.target.closest('.search-not-found')) {
                    event.stopImmediatePropagation();
                    this.addElement(event.target);
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
        }, 50)
    }
}