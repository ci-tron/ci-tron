import { Injectable } from 'angular2/core';
import {Http, Response, Headers, RequestOptions} from 'angular2/http';

/**
 * The session class manage all request related to user status on the back.
 */
@Injectable()
export class Session {

    private active:boolean = null;

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
        return this.http.get('/back/login-status.json');
    }

    login(username, password) {
        let headers = new Headers({ 'Content-Type': 'application/x-www-form-urlencoded' });
        let options = new RequestOptions({ headers: headers });

        return this.http.post('/back/login', 'username=' + username + '&password=' + password, options);
    }

    logout() {
        return new Promise<Session>((resolve, reject) => {
            this.http.get('/back/logout').subscribe((res:Response) => {
                this.active = false;
                resolve(this);
            }, reject);
        })
    }
}
