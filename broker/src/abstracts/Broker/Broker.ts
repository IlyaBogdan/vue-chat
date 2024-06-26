import { WebSocketEntry } from "../..";
import { SessionStore } from "../../SessionStore";
import { BrokerApi } from "./BrokerApi";

export abstract class Broker {
    
    public api: BrokerApi;
    protected sessionStore: SessionStore;

    constructor() {
        this.api = this.getApi();
        this.sessionStore = new SessionStore();  
    }

    protected abstract getApi(): BrokerApi;

    public methodExists(method: keyof typeof this.api): boolean {
        return this.api.hasOwnProperty(method);
    }

    public call(apiMethod: keyof typeof this.api): any {
        if (!this.methodExists(apiMethod)) throw new Error(`Method '${apiMethod}' not exists`);

        return (message) => {
            return this.api[apiMethod].action(message, this);
        }
    }

}