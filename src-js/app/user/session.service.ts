/**
 * This file is a part of ci-tron package.
 *
 * (c) Ci-tron <dev@ci-tron.org>
 *
 * For the full license, take a look to the LICENSE file
 * on the root directory of this project
 */
import { Injectable } from '@angular/core';
import { Http, Response, Headers, RequestOptions } from '@angular/http';

/**
 * The session class manage all request related to user status on the back.
 */
@Injectable()
export class Session {

    private active:boolean = null;

    constructor (private http: Http) {}

    init() : Promise<Session> {

        if (this.active !== null) {
            return new Promise<Session>((resolve: any, reject: any) => {
                resolve(this);
                reject('nope');
            });
        }

        return new Promise<Session>((resolve: any, reject: any) => {
            this.renew().subscribe((res:Response) => {
                this.active = res.status === 200;
                resolve(this);
            }, reject);
            reject('nope');
        });
    }

    isActive() {
        return this.active;
    }

    renew() {
        return this.http.get('/back/login-status.json');
    }

    login(username: string, password: string) {
        let headers = new Headers({ 'Content-Type': 'application/x-www-form-urlencoded' });
        let options = new RequestOptions({ headers: headers });

        return this.http.post('/back/login', 'username=' + username + '&password=' + password, options);
    }

    logout() {
        return new Promise<Session>((resolve: any, reject: any) => {
            this.http.get('/back/logout').subscribe((res:Response) => {
                this.active = false;
                resolve(this);
            }, reject);
            reject('nope');
        })
    }
}
