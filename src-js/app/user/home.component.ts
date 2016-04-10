/**
 * This file is a part of ci-tron package.
 *
 * (c) Ci-tron <dev@ci-tron.org>
 *
 * For the full license, take a look to the LICENSE file
 * on the root directory of this project
 */
import {Component, Input, Output, EventEmitter} from 'angular2/core';
import {RouteConfig, RouterOutlet, CanActivate} from 'angular2/router';
import {RegistrationFormComponent} from './registration-form.component';
import {Registration} from './registration.service';
import {Router} from "angular2/router";
import {Session} from "../user/session.service";


@Component({
    selector: 'home',
    directives: [RouterOutlet],
    template: `

        <h1>logged in</h1>
    `
})

@CanActivate(Session.isActive)
export class HomeComponent {
    constructor(
    ) {}
}
