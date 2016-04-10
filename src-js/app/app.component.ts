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
import {RegistrationComponent} from "./user/registration.component";
import {RouteConfig, ROUTER_PROVIDERS, ROUTER_DIRECTIVES} from 'angular2/router';
import {HomeComponent} from "./user/home.component";


/**
 * This component is the top/root component.
 */
@Component({
    selector: 'my-app',
    providers: [HTTP_PROVIDERS, Session, ROUTER_PROVIDERS],
    directives:  [LoginComponent, ROUTER_DIRECTIVES],
    template: `

        <h1>ci-tron</h1>
        <nav>
            <a [routerLink]="['Registration']">Registration</a>
            <a [routerLink]="['Login']">Login</a>
        </nav>

        <router-outlet></router-outlet>
    `
})
@RouteConfig([
    {
        path: '/registration/...',
        name: 'Registration',
        component: RegistrationComponent,
        useAsDefault: true
    },
    { path: '/login', name: 'Login', component: LoginComponent },
    { path: '/home', name: 'Home', component: HomeComponent }
])
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
