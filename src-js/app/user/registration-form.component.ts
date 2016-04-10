/**
 * This file is a part of ci-tron package.
 *
 * (c) Ci-tron <dev@ci-tron.org>
 *
 * For the full license, take a look to the LICENSE file
 * on the root directory of this project
 */
import {Component, Input, Output, EventEmitter} from 'angular2/core';
import {RouteConfig, RouterOutlet} from 'angular2/router';

@Component({
    template: `
    <h2>Registration form</h2>
  `,
})
export class RegistrationFormComponent {
    constructor() {
        console.log('registration');
    }

}
