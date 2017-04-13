/**
 * This file is a part of ci-tron package.
 *
 * (c) Ci-tron <dev@ci-tron.org>
 *
 * For the full license, take a look to the LICENSE file
 * on the root directory of this project
 */
import { Component, Output, EventEmitter, Inject } from '@angular/core';
import { Session } from '../user/session.service';

@Component({
    selector: 'login',
    template: require('./login.html'),
})

export class LoginComponent {
    @Output() updateUserStatus = new EventEmitter();

    username:string = '';
    password:string = '';
    error:string = null;

    constructor(@Inject(Session) private session:Session) {}

    onSubmit() {
        this.session.login(this.username, this.password).subscribe(() => {
            this.updateUserStatus.emit(true);
        }, (error: any) => {
            if (error.status === 401) {
                this.error = 'Bad credentials';
            }
        });
    }
}
