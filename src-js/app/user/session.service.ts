import { Injectable } from 'angular2/core';
import {Http, Response, Headers, RequestOptions} from 'angular2/http';
import {Observable}     from 'rxjs/Observable';
import {Message} from "../standard/messages";

@Injectable()
export class Session {

    private active:boolean;

    constructor (private http: Http) {}

    init() : Promise<Session> {

        if (this.active !== null) {
            return new Promise<Session>((resolve, reject) => { resolve(this); });
        }

        return new Promise<Session>((resolve, reject) => {
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

    login(username, password) {
        console.log('hello');
        let headers = new Headers({ 'Content-Type': 'application/x-www-form-urlencoded' });
        let options = new RequestOptions({ headers: headers });

        return this.http.post('/back/login', 'username=' + username + '&password=' + password, options);
    }

    private handleError (error: Response) {
        // in a real world app, we may send the error to some remote logging infrastructure
        // instead of just logging it to the console
        console.error(error);
        return Observable.throw(error.json().error || 'Server error');
    }
}
