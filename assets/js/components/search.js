import { generateAlert } from './alert'

export class Search extends HTMLElement {
    connectedCallback() {
        this.classList.add('search');
        var placeholder = this.dataset.placeholder;
        var id = this.dataset.id;
        var name = this.dataset.name;

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
}

const URL_OFFICE = '/recherche/offices/';

export class SearchOffices extends Search {
    constructor(){
        super()
    }
    
    connectedCallback(){
        super.connectedCallback()
        var children = Array.from(this.children)
        var input = children[0]
        let timer;

        input.addEventListener('input', () => {
            clearTimeout(timer);
            timer = setTimeout( async () => {
                try {
                    let response = await fetch(`${URL_OFFICE}?q=${encodeURI(input.value)}`)
                    if(response.ok) {
                        let data = await response.json()
                        this.updateSearchResult(data.data)
                    } else {
                        let error = await response.json()
                        this.notFound(error)
                    }
                } catch (error) {
                    generateAlert("error", "Erreur durant la résolution de la requête.")
                }
            },300)          
        })  

        input.addEventListener('click', () => {
            this.toggleSearchResult();
        })
    }

    async updateSearchResult(data){
        let container = this.children[3]
        container.innerHTML = '';
        data.forEach(office => {
            container.innerHTML += `<div class='search-result-element' data-officeid='${office.id}'>
                                        <p class='office-street'>${office.street}</p>
                                        <p class='office-email'>${office.email}</p>
                                        <p class='office-phone'>${office.tel_number}</p>
                                    </div>`
        })

        document.addEventListener('click', (event) =>{
            if(event.target.closest('.search-result-element') && !event.target.closest('.search-not-found')){
                this.addOffice(event.target);
            }
        })
    }

    addOffice(event) {
        let userInput = this.children[0]
        let form_input = this.children[2]
        let searchElement = event.closest('.search-result-element');

        userInput.value = searchElement.querySelector('.office-street').innerHTML
        form_input.value = searchElement.dataset.officeid;
        this.toggleSearchResult();
    }

    toggleSearchResult(){
        this.children[0].closest('.search').classList.toggle('open-search-result')
    }

    async notFound(error){
        this.children[3]
        container.innerHTML = `<div class='search-result-element search-not-found'>
                                    ${error.message}
                                </div>`
    }
}