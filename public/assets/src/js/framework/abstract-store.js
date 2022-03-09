'use strict';

import PubSub from './pub-sub';

export default class AbstractStore
{
    #eventManager;
    #data;

    constructor(eventManager)
    {
        if (this.constructor === AbstractStore) {
            throw new Error('Initializing directly from abstract class');
        }

        if (!eventManager) {
            eventManager = new PubSub;
        }

        this.#eventManager = eventManager;
    }

    getData(key)
    {
        return this.#data.key ? this.#data.key : null;
    }

    setData(key, value)
    {
        if (this.#data.key) {
            return;
        }

        this.#data.key = value;

        return this;
    }

    /**
     * Cast an event, registrants will be notified
     * 
     * @param string eventName 
     * @param mixed eventData 
     * @returns 
     */
    publish(eventName, eventData)
    {
        this.getEventManager().publish(eventName, eventData);
        return this;
    }

    /**
     * Hook into an event, publishing will trigger callback set in subscribe()
     * 
     * @param string eventName 
     * @param string key 
     * @param function callback 
     * @returns 
     */
    subscribe(eventName, key, callback)
    {
        this.getEventManager().subscribe(eventName, key, callback);
        return this;
    }

    debugEvents()
    {
        const self = this;
        const eventNames = this.getEventManager().listEventNames();

        eventNames.forEach(
            eventName => console.table({
                event: eventName,
                events: self.getEventManager().listEventListeners(eventName)
            })
        );

        return this;
    }

    getEventManager()
    {
        return this.#eventManager;
    }
}
