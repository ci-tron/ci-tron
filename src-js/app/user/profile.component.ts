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
    selector: 'profile',
    providers: [Session],
    templateUrl: 'app/templates/user/profile.html'
})
export class ProfileComponent {
    constructor(private session:Session) {}
}
