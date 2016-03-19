import { Injectable } from 'angular2/core';
import {Http, Response} from 'angular2/http';
import {Observable}     from 'rxjs/Observable';
import {Message} from "../standard/messages";

@Injectable()
export class Session {

    private active:boolean;

    constructor (private http: Http) {}

    init() : Promise {
        if (this.active !== null) {
            return new Promise((resolve, reject) => { resolve(this); });
        }

        return new Promise((resolve, reject) => {
            this.renew().subscribe((res:Response) => {
                this.active = res.status === 200;
                resolve(this);
            }, reject);
        });
    }

    isActive() {
        return this.active;
    }

    renew() {
        return this.http.get('/back/login-status.json').catch(this.handleError);
    }

    private handleError (error: Response) {
        // in a real world app, we may send the error to some remote logging infrastructure
        // instead of just logging it to the console
        console.error(error);
        return Observable.throw(error.json().error || 'Server error');
    }
}
