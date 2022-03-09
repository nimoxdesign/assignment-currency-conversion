// import 'dotenv/config'; // node only, not web
// console.log('Hello Node.js project.');
// console.log(process.env.MY_SECRET);

'use strict';

import PubSub from "./framework/pub-sub";
import FormHandler from './component/currency-calculation/form-handler';
import FormStore from "./component/currency-calculation/form-store";
import DomHandler from "./component/currency-calculation/dom-handler";

const formConfig = {
    selector: 'form[name="currencyconversion"]',
    provider: {
        selector: '[data-cc="type:provider"]'
    },
    base: {
        selector: '[data-cc="type:base"]',
        amount: '[data-cc="value:amount"]',
        code: '[data-cc="value:code"]'
    },
    target: {
        selector: '[data-cc="type:target"]',
        amount: '[data-cc="value:amount"]',
        code: '[data-cc="value:code"]'
    }
}

// Boot up application
const eventManager = new PubSub;
const formHandler = new FormHandler(new FormStore(eventManager), formConfig);
const domHandler = new DomHandler(eventManager);
