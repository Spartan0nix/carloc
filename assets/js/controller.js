import { Alert } from './components/alert';
import { SearchOffices } from './components/search';

customElements.define('js-alert', Alert);
customElements.define('js-search-offices', SearchOffices);

if(document.getElementById('rent_step_1')){
    var searchOfficesElement = document.createElement('js-search-offices');
    searchOfficesElement.dataset.placeholder = "Entrez une ville ou code postal";
    searchOfficesElement.dataset.id = "office-pickup-input";
    searchOfficesElement.dataset.name = "pickup_office";
    searchOfficesElement.dataset.listId = "pickup_offices";
    document.querySelector('.office-pickup').appendChild(searchOfficesElement);
}

