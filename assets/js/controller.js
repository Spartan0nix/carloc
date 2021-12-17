import { Alert } from './components/alert';
import { DynamicSearch, StaticSearch } from './components/search';

import { DynamicSelect, StaticSelect } from './components/old_select';

import { SelectDynamic, SelectStatic } from './components/select'


customElements.define('js-alert', Alert);

customElements.define('js-search-static', StaticSearch);
customElements.define('js-search-dynamic', DynamicSearch);

customElements.define('js-select-static', StaticSelect);
customElements.define('js-select-dynamic', DynamicSelect);


customElements.define('select-dynamic', SelectDynamic)
customElements.define('select-static', SelectStatic)



import './pages/account';
import './components/navbar';
import './pages/rent_step_1';
import './pages/rent_step_2';
import './pages/rent_step_5';


