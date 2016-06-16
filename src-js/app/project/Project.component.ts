/**
 * This file is a part of ci-tron package.
 *
 * (c) Ci-tron <dev@ci-tron.org>
 *
 * For the full license, take a look to the LICENSE file
 * on the root directory of this project
 */

import { Component, OnInit } from 'angular2/core';
import {RouteConfig, RouteParams, ROUTER_DIRECTIVES, Router} from "angular2/router";
import {ProjectDashoardComponent} from "./project.dashboard.component";
import {ProjectStatisticsComponent} from "./project.statistics.component";
import {ProjectSettingsComponent} from "./project.settings.component";
import {Project, ProjectEntity} from "./project.service";
import {ProjectBuildsComponent} from "./project.builds.component";
import {ProjectBuildDetailComponent} from "./project.build.detail.component";

@Component({
    selector: 'project',
    providers: [Project],
    directives:  [ROUTER_DIRECTIVES],
    templateUrl: 'app/templates/project/project.html',
    styles: [`
        #project {
        }
    `]
})
@RouteConfig([
    { path: '/dashboard',  name: 'ProjectDashboard', component: ProjectDashoardComponent, useAsDefault: true },
    { path: '/builds',  name: 'ProjectBuilds', component: ProjectBuildsComponent },
    { path: '/builds/:id',  name: 'ProjectBuildDetail', component: ProjectBuildDetailComponent },
    { path: '/statistics',  name: 'ProjectStatistics', component: ProjectStatisticsComponent },
    { path: '/settings',  name: 'ProjectSettings', component: ProjectSettingsComponent }
])
export class ProjectComponent implements OnInit {
    project:ProjectEntity;

    constructor(private Project: Project, private router: Router, private _routeParams: RouteParams) {
    }

    ngOnInit() {
        if (this._routeParams.get('slug') !== null) {
            this.Project.getProject(this._routeParams.get('slug')).then(data => {
                this.project = data;
            });
        }
    }

    isActive(instruction: any[]): boolean {
        return this.router.isRouteActive(this.router.generate(instruction));
    }
}
