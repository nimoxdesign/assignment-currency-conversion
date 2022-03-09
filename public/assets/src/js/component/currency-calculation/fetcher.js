'use strict';

import PubSub from "../../framework/pub-sub";

const FETCH_START  = 'fetch:start';
const FETCH_FINISH = 'fetch:finish';
const FETCH_ERROR  = 'fetch:error';

export default class Fetcher
{
    #eventManager;

    static get FETCH_START() {
        return FETCH_START;
    }

    static get FETCH_FINISH() {
        return FETCH_FINISH;
    }

    static get FETCH_ERROR() {
        return FETCH_ERROR;
    }

    constructor(eventManager)
    {
        if (!eventManager) {
            eventManager = new PubSub();
        }

        this.#eventManager = eventManager;
    }

    fetch(url, callback)
    {
        this.#eventManager.publish(Fetcher.FETCH_START, {'url': url});

        fetch(
            url,
            {
                method: "GET",
                headers: {
                    'Accept': 'application/json',
                    'Content-Type': 'application/json',
                },
            })
            .then((response) => response.json())
            .then((responseData) => {
                console.log(responseData);
                this.#eventManager.publish(Fetcher.FETCH_FINISH, {'data': responseData});

                callback(responseData);
            })
            .catch((error) => {
                this.#eventManager.publish(Fetcher.FETCH_ERROR, {'error': error});
            })
        ;
    }
}
