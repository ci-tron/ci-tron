/**
 * This file is a part of ci-tron package.
 *
 * (c) Ci-tron <dev@ci-tron.org>
 *
 * For the full license, take a look to the LICENSE file
 * on the root directory of this project
 */
import {Component, Input, Output, EventEmitter} from 'angular2/core';
import {Session} from "../user/session.service";


@Component({
    selector: 'login',
    providers: [Session],
    templateUrl: 'app/templates/user/login.html'
})
export class LoginComponent {
    @Output() updateUserStatus = new EventEmitter();

    username:string = '';
    password:string = '';
    error:string = null;

    constructor(private session:Session) {}

    onSubmit() {
        this.session.login(this.username, this.password).subscribe(() => {
            this.updateUserStatus.emit(true);
        }, (error) => {
            if (error.status === 401) {
                this.error = 'Bad credentials';
            }
        });
    }
}
