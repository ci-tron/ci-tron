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
import {ControlGroup, FormBuilder, Validators} from "angular2/common";

@Component({
    selector: 'project',
    providers: [Project],
    directives:  [ROUTER_DIRECTIVES],
    templateUrl: 'app/templates/project/project-creation.html'
})
export class ProjectCreationComponent implements OnInit {
    creationForm: ControlGroup;
    error: string = null;

    const visibility = ['public', 'private', 'hidden'];

    constructor(
        private _router: Router,
        private Project: Project,
        private builder: FormBuilder
    ) {
        this.creationForm = builder.group({
            name: ['', Validators.required],
            repository: ['', Validators.required],
            visibility: ['', Validators.required]
        });
    }

    ngOnInit() {}

    onSubmit() {
        this.Project.create(this.name, this.repository, this.visibility).then(data => {
            console.log(data);

            this._router.navigate(['/Project', {slug: data.slug}]);
        }, (error) => {
            if (error.status !== 200) {
                this.error = error._body;
            }
        });
    }
}
