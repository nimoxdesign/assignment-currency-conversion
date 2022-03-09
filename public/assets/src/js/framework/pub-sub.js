'use strict';

export default class PubSub
{
    #events;

    constructor() 
    {
        this.#events = {};
    }

    /**
     * Cast an event
     * 
     * @param String eventName 
     * @param mixed eventData 
     * @returns 
     */
    publish(eventName, eventData)
    {
        if (!eventName) {
            return;
        }

        const eventCallbacks = this.#getEvent(eventName);

        for (const callbackName in eventCallbacks) {
            eventCallbacks[callbackName](eventData);
        }

        return this;
    }

    /**
     * Hook into an event via callback
     * 
     * @param string eventName 
     * @param mixed key 
     * @param function callback 
     * @returns 
     */
    subscribe(eventName, key, callback)
    {
        if (!key || !eventName || !callback) {
            return;
        }

        this.#setEvent(eventName, key, callback);

        return this;
    }

    list()
    {
        return this.#getEvents();
    }

    listEventNames()
    {
        return Object.keys(this.list());
    }

    listEventListeners(eventName)
    {
        if (!this.hasEvent(eventName)) {
            return;
        }

        return Object.keys(this.#getEvent(eventName));
    }

    hasEvent(eventName)
    {
        return !!this.#events[eventName];
    }

    #getEvents()
    {
        return this.#events;
    }

    #getEvent(eventName, key) 
    {
        if (!this.hasEvent(eventName)) {
            return;
        } else if (!key) {
            return this.#events[eventName];
        } else {
            return this.#events[key] ? this.#events[key] : undefined;
        }
    }

    #setEvent(eventName, key, callback)
    {
        if (!this.hasEvent(eventName)) {
            this.#events[eventName] = {};
        }

        this.#events[eventName][key] = callback;
    }
}
