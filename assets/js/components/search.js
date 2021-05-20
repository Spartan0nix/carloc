import { generateAlert } from './alert'

export class Search extends HTMLElement {
    connectedCallback() {
        this.classList.add('search');
        var placeholder = this.dataset.placeholder;
        var id = this.dataset.id;
        var name = this.dataset.name;

        this.innerHTML = `<input type="text" placeholder="${placeholder}" id="${id}" required>
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
                        let data = await this.processJson(response)
                        this.updateSearchResult(data.data)
                    } else {
                        let error = await this.processJson(response)
                        this.notFound(error)
                    }
                } catch (error) {
                    generateAlert("error", "Erreur durant la résolution de la requête.")
                }
            },300)          
        })  
        input.addEventListener('focus', () => {
            children[2].style.display = 'flex'
        })
    }

    

    async updateSearchResult(data){
        let container = Array.from(this.children)[2]
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
        let userInput = Array.from(this.children)[0]
        let input = Array.from(this.children)[1]
        let container = Array.from(this.children)[2]
        let searchElement = event.closest('.search-result-element');

        userInput.value = searchElement.querySelector('.office-street').innerHTML
        input.value = searchElement.dataset.officeid;
        container.style.display = 'none'
    }


    async processJson(response){
        return response.json()
    }

    async notFound(error){
        let container = Array.from(this.children)[2]
        container.innerHTML = `<div class='search-result-element search-not-found'>
                                    ${error.message}
                                </div>`
    }
}