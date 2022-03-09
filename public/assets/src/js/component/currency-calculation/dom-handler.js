'use strict';

import PubSub from "../../framework/pub-sub";
import FormHandler from "./form-handler";
import FormStore from "./form-store";

export default class DomHandler
{
    #eventManager;

    constructor(eventManager)
    {
        if (!eventManager) {
            eventManager = new PubSub;
        }

        this.#eventManager = eventManager;

        this.#_subscribeEvents();
        this.#_handleEvents();
    }

    #_subscribeEvents()
    {
        const self = this;

        this.#eventManager.subscribe(
            FormHandler.FORM_INPUT_ERROR,
            'currencyconversion:dom-handler:form-input-error',
            (result) => {
                self.#_handleError(result);
            }
        );

        this.#eventManager.subscribe(
            FormStore.FETCH_FINISH, 
            'currencyconversion:dom-handler:fetch-finish', 
            (result) => {
                if (result.error) {
                    self.#_handleError(result);
                } else {
                    // Get the requested amount to convert out of the form. 
                    // This value is neccessary for calculation.  
                    result.data.base_amount = document.querySelector('[data-cc="type:base"] [data-cc="value:amount"]').value;

                    self.#_handleResult(result.data);
                }
            }
        );
    }

    #_handleEvents()
    {
        const self = this;
    }

    #_handleResult(data)
    {
        let template = this.#_getTemplate('#conversion-calculation');
        let target = document.querySelector('[data-cc="template:target"]');

        this.#_insertValuesIntoTemplate(template, data);

        target.innerHTML = '';
        target.appendChild(template);
    }

    #_handleError(data)
    {
        let template = this.#_getTemplate('#conversion-error').querySelector('[data-cc="template:content"]');
        let target = document.querySelector('[data-cc="template:target"]');
        
        template.textContent = data.message;       
        target.innerHTML = '';
        target.appendChild(template);
    }

    #_getTemplate(selector)
    {
        const template = document.querySelector(selector);

        return template.content.cloneNode(true);
    }

    #_insertValuesIntoTemplate(template, data)
    {
        const dataMapping = {
            'output:base:code': data.base_currency,
            'output:base:amount': data.base_amount,
            'output:target:code': data.target_currency,
            'output:target:rate': data.value,
            'output:target:amount': data.value * data.base_amount
        }

        for (let target in dataMapping) {
            template.querySelector(`[data-cc="${target}"]`).textContent = dataMapping[target]
        }
    }
}
