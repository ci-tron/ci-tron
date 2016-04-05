/**
 * This file is a part of ci-tron package.
 *
 * (c) Ci-tron <dev@ci-tron.org>
 *
 * For the full license, take a look to the LICENSE file
 * on the root directory of this project
 */
import {Component, OnInit} from 'angular2/core';
import {HTTP_PROVIDERS}    from 'angular2/http';
import {Session} from "./user/session.service";
import {LoginComponent} from "./user/security.component";


/**
 * This component is the top/root component.
 */
@Component({
    selector: 'my-app',
    providers: [HTTP_PROVIDERS, Session],
    directives:  [LoginComponent],
    template: `
    <div *ngIf="logged">
        <h1 >I'm ci-tron !</h1>
        <p><a href="#" (click)="onLogout()">logout</a></p>
    </div>
    <login *ngIf="!logged" (updateUserStatus)="updateUserStatus($event)"></login>
    `
})
export class AppComponent implements OnInit {

    logged:boolean = null;

    constructor(private session:Session) {}

    /**
     * This method is called by the LoginComponent on user update status.
     * @param log This is a boolean saying if yes or not the user is logged.
     */
    updateUserStatus(log) {
        this.logged = log;
    }

    onLogout() {
        this.session.logout().then(
            (session:Session) => this.logged = session.isActive(),
            (error) => console.error(error)
        );

        return false;
    }

    /**
     * On root component init we should get the user status.
     * As login is managed with cookies it's possible the user is already connected.
     */
    ngOnInit() {
        this.session.init().then(
            (session:Session) => this.logged = session.isActive(),
            (error) => console.error(error)
        );
    }
}
