/**
 * This file is a part of ci-tron package.
 *
 * (c) Ci-tron <dev@ci-tron.org>
 *
 * For the full license, take a look to the LICENSE file
 * on the root directory of this project
 */

import { Component, OnInit } from 'angular2/core';
import {ROUTER_PROVIDERS, ROUTER_DIRECTIVES, Router} from "angular2/router";
import {Project, ProjectEntity} from "./project/project.service";

@Component({
    selector: 'projects',
    providers: [Project],
    directives:  [ROUTER_DIRECTIVES],
    templateUrl: 'app/templates/dashboard.html'
})
export class DashboardComponent implements OnInit {
    projects: ProjectEntity[];

    constructor(private router: Router, private Project: Project) {
    }

    ngOnInit() {
        this.Project.getProjects().then(data => {
            this.projects = data;
            console.log(data);
        });

    }
}
