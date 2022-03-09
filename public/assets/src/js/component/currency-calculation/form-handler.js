'use strict';

import FormStore from './form-store';

const FORM_INPUT_ERROR = 'currencyconversion:form-handler:input-error';

export default class FormHandler 
{
    #defaultConfig = {
        //selector: selector
        //base: {selector, amount, value}
        //target: {selector, amount, value}
    };
    #config;
    #formStore;

    static get FORM_INPUT_ERROR() {
        return FORM_INPUT_ERROR;
    }

    constructor(formStore, config) {
        this.#formStore = formStore;
        this.#config = Object.assign({}, config, this.defaultConfig);

        this.#_handleEvents();
    }

    getConfig (key) {
        if (!key) {
            return this.#config;
        }

        if (!this.#config[key]) {
            console.warn(`config key "${key}" doesn't exist.`);
            return;
        }

        return this.#config[key];
    }

    #_getFormData () {
        const providerField = this.#_getProviderField();
        const baseFields = this.#_getBaseFields();
        const targetFields = this.#_getTargetFields();

        return {
            'provider': providerField.value,
            'base': {
                'amount': baseFields.amount.value,
                'code': baseFields.code.value
            },
            'target': {
                'amount': targetFields.amount.value,
                'code': targetFields.code.value
            }
        }
    }

    #_getForm() 
    {
        return document.querySelector(this.getConfig('selector'));
    }

    /**
     * Abstraction for querying DOM nodes inside form
     * 
     * @param object selectorConfig 
     * @returns 
     */
    #_getFields (selectorConfig) 
    {
        return {
            'amount': this.#_getForm().querySelector(`${selectorConfig.selector} ${selectorConfig.amount}`),
            'code': this.#_getForm().querySelector(`${selectorConfig.selector} ${selectorConfig.code}`),
        };
    }

    #_getProviderField()
    {
        return this.#_getForm().querySelector(`${this.getConfig('provider').selector}`);
    }

    #_getBaseFields () 
    {
        return this.#_getFields(this.getConfig('base'));
    }

    #_getTargetFields () 
    {
        return this.#_getFields(this.getConfig('target'));
    }

    /**
     * Handle form events
     */
    #_handleEvents() {
        const self = this;

        const handleSubmit = function (ev) {
            ev.preventDefault();

            const formData = self.#_getFormData();
            let invalidation = self.#_checkFormData(formData)

            if (invalidation.error) {
                console.log(FormHandler.FORM_INPUT_ERROR);
                self.#formStore.publish(FormHandler.FORM_INPUT_ERROR, invalidation);
                return false;
            }

            self.#formStore.fetchConversion(formData);

            return false;
        }

        /**
         * Adding event listener for toggling to element. 
         * - This event will be only be meant for touch devices.
         * - This event should NOT be passive due to possible underlaying elements that we do not want to be triggered. 
         */
        this.#_getForm()
            .addEventListener(
                'submit', 
                handleSubmit, 
                // {
                //     capture: true, 
                //     passive: false
                // }
            );

        return this;
    }

    /**
     * check whether validation passes
     * 
     * @param array formData 
     * @returns object
     */
    #_checkFormData(formData) 
    {
        // console.log(formData);
        formData.base.amount = parseFloat(formData.base.amount);

        if (!formData.base.amount) {
            return {'error': true, 'message': 'No value set for amount to convert.'};
        } else if (formData.base.code === formData.target.code) {
            return {'error': true, 'message': 'Makes no sense to convert one and the same.'}
        }

        return {'success': true};
    }
};
