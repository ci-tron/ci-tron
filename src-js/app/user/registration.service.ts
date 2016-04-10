/**
 * This file is a part of ci-tron package.
 *
 * (c) Ci-tron <dev@ci-tron.org>
 *
 * For the full license, take a look to the LICENSE file
 * on the root directory of this project
 */
import { Injectable } from 'angular2/core';
import {Http, Response, Headers, RequestOptions} from 'angular2/http';

/**
 * The session class manage all request related to user status on the back.
 */
@Injectable()
export class Registration {

    constructor (private http: Http) {}

    init() : Promise<Registration> {
        return new Promise<Registration>((resolve, reject) => { resolve(this); });
    }

    register(username, password, email) {
        let headers = new Headers({ 'Content-Type': 'application/x-www-form-urlencoded' });
        let options = new RequestOptions({ headers: headers });

        return this.http.post('/back/registration', 'username=' + username + '&password=' + password + '&email=' + email, options);
    }
}
