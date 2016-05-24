/**
 * This file is a part of ci-tron package.
 *
 * (c) Ci-tron <dev@ci-tron.org>
 *
 * For the full license, take a look to the LICENSE file
 * on the root directory of this project
 */

import {Project, ProjectEntity} from "./project.service";
import {Component} from "angular2/core";
import {RouteConfig, ROUTER_DIRECTIVES, ROUTER_PROVIDERS} from "angular2/router";


@Component({
    providers: [Project, ROUTER_PROVIDERS],
    directives: [ROUTER_DIRECTIVES],
    template: `
    <div>
        <h2>Project list</h2>
        <div>
            <ul>
                <li *ngFor="#project of projects">
                    <a [routerLink]="['Project', {slug:project.slug}]">
                        {{project.name}}
                    </a>
                </li>
            </ul>
        </div>     
    </div>
    `
})
export class DashboardComponent {
    projects: ProjectEntity[] = [];

    constructor(private project:Project){
        project.getProjects().then(projects => this.projects = projects);
    }
}
