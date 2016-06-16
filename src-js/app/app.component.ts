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
import {ProjectComponent} from "./project/project.component";
import {RouteConfig, ROUTER_DIRECTIVES, ROUTER_PROVIDERS, Router} from "angular2/router";
import {DashboardComponent} from "./dashboard.component";
import {ProjectCreationComponent} from "./project/project.creation.component";
import {ProfileComponent} from "./user/profile.component";


/**
 * This component is the top/root component.
 */
@Component({
    selector: 'ci-tron',
    providers: [HTTP_PROVIDERS, Session],
    directives:  [ROUTER_DIRECTIVES, LoginComponent],
    templateUrl: 'app/templates/layout.html'
})
@RouteConfig([
    { path: '/dashboard',  name: 'Dashboard', component: DashboardComponent, useAsDefault: true },
    { path: '/projects/:slug/...', name: 'Project',  component: ProjectComponent },
    { path: '/projects/new', name: 'ProjectCreation',  component: ProjectCreationComponent },
    { path: '/login', name: 'Login',  component: LoginComponent },
    { path: '/profile', name: 'Profile',  component: ProfileComponent }
])
export class AppComponent implements OnInit {

    logged:boolean = null;

    constructor(private session:Session, private router: Router) {}

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

        this.router.navigate(['/Dashboard']);

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
