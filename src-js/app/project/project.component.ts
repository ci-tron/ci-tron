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
import {RouteConfig, ROUTER_DIRECTIVES, ROUTER_PROVIDERS, RouterOutlet} from "angular2/router";
import {ProjectDetailComponent} from "./projectDetail.component";
import {DashboardComponent} from "./dashboard.component.ts";


@Component({
    providers: [Project],
    directives: [RouterOutlet],
    template: `
    <div>
        <h1>Projects</h1>
        <router-outlet></router-outlet>
    </div>
    `
})
@RouteConfig([
    {path:'/:slug', name: 'ProjectDetail', component: ProjectDetailComponent, useAsDefault: true}
])
export class ProjectComponent {
    constructor(private project:Project){}
}
