/**
 * This file is a part of ci-tron package.
 *
 * (c) Ci-tron <dev@ci-tron.org>
 *
 * For the full license, take a look to the LICENSE file
 * on the root directory of this project
 */

import {Project} from "./project.service";
import {Component} from "angular2/core";
import {RouteConfig, ROUTER_DIRECTIVES, ROUTER_PROVIDERS, RouteParams} from "angular2/router";


@Component({
    providers: [Project],
    directives: [ROUTER_DIRECTIVES],
    template: `
    <div>
        <h2>Project detail</h2>
    </div>
    `
})
export class ProjectDetailComponent {
    private projectSlug: string = '';
    
    constructor(private project:Project, private _routeParams: RouteParams){}

    ngOnInit() {
        if (this._routeParams.get('slug') !== null) {
            this.projectSlug = this._routeParams.get('slug');

            console.log(this.project.getProject(this.projectSlug));
        }
    }
}
