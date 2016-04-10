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
import {RegistrationFormComponent} from './registration-form.component';
import {Registration} from './registration.service';
import {Router} from "angular2/router";
import {Validators, FormBuilder} from "angular2/common";

@Component({
    selector: 'registration',
    templateUrl: 'app/templates/user/registration.html',
    directives: [RouterOutlet],
    providers:  [Registration]
})
@RouteConfig([
    {path:'/', name: 'RegistrationForm', component: RegistrationFormComponent, useAsDefault: true}
])
export class RegistrationComponent {
    registrationForm: ControlGroup;
    error: string = null;

    constructor(
        private _router: Router,
        private registration: Registration,
        private builder: FormBuilder
    ) {
        this.registrationForm = builder.group({
            username: ['', Validators.required],
            password: ["", Validators.required],
            email: ["", Validators.required]
        });
    }

    onSubmit() {
        this.registration.register(this.username, this.password, this.email).subscribe(() => {
            this._router.navigate(['Login']);
        }, (error) => {
            if (error.status !== 200) {
                this.error = error._body;
            }
        });
    }
}
