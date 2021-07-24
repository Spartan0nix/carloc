import { Alert } from './components/alert';
import { DynamicSearch, StaticSearch } from './components/search';
import { DynamicSelect, StaticSelect } from './components/select';


customElements.define('js-alert', Alert);
customElements.define('js-search-static', StaticSearch);
customElements.define('js-search-dynamic', DynamicSearch);
customElements.define('js-select-dynamic', DynamicSelect);
customElements.define('js-select-static', StaticSelect);



import './pages/rent_step_1';


