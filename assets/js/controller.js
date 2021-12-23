import { Alert } from './components/alert';

import { SearchDynamic, SearchStatic } from './components/search';
import { SelectDynamic, SelectStatic } from './components/select'


customElements.define('js-alert', Alert);

customElements.define('select-dynamic', SelectDynamic)
customElements.define('select-static', SelectStatic)

customElements.define('search-dynamic', SearchDynamic)
customElements.define('search-static', SearchStatic)

import './components/navbar';
import './pages/rent_step_1';
import './pages/rent_step_2';
import './pages/rent_step_5';


