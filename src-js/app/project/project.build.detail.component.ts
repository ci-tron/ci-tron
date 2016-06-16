/**
 * This file is a part of ci-tron package.
 *
 * (c) Ci-tron <dev@ci-tron.org>
 *
 * For the full license, take a look to the LICENSE file
 * on the root directory of this project
 */

import { Component, OnInit } from 'angular2/core';
import {RouteConfig, RouteParams} from "angular2/router";

@Component({
    selector: 'project-build-detail',
    templateUrl: 'app/templates/project/project-build-detail.html'
})
export class ProjectBuildDetailComponent implements OnInit {
    constructor(private _routeParams: RouteParams) {
        console.log('builds');
    }

    ngOnInit() {
    }
}
