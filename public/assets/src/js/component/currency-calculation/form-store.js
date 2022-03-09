'use strict';

import AbstractStore from "../../framework/abstract-store";
import Fetcher from "./fetcher";

const FETCH_FINISH = 'currencyconversion:form-data:finish'

export default class FormStore extends AbstractStore
{
    #eventManager;
    #fetcher;

    static get FETCH_FINISH() {
        return FETCH_FINISH;
    }

    constructor(eventManager, fetcher)
    {
        super(eventManager);

        if (!fetcher) {
            fetcher = new Fetcher;
        }

        this.#eventManager = eventManager;
        this.#fetcher = fetcher;
    }

    fetchConversion(data)
    {
        const self = this;

        this.#fetcher.fetch(`/api/currencyconversion/data?provider=${data.provider}&base_currency=${data.base.code}&target_currency=${data.target.code}`, function (result) {
            self.publish(FormStore.FETCH_FINISH, result);
        });
    }
}
