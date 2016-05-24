/**
 * This file is a part of ci-tron package.
 *
 * (c) Ci-tron <dev@ci-tron.org>
 *
 * For the full license, take a look to the LICENSE file
 * on the root directory of this project
 */

import { Injectable } from 'angular2/core';
import {Http, Response} from "angular2/http";
import 'rxjs/add/operator/toPromise';


@Injectable()
export class Project {
    constructor (private http: Http) {}

    getProjects(): Promise<ProjectEntity[]> {
        return this.http.get('/back/secured/users/admin/projects.json')
            .toPromise()
            .then(function(response) {
                var projects: ProjectEntity[] = [];

                for (var item of response.json()) {
                    projects.push(new ProjectEntity(item.name, item.slug, item.repository));
                }

                return projects;
            })
            .catch(error => Promise.reject(error))
        ;
    }

    getProject(slug: string): Promise<ProjectEntity> {
        return this.http.get('/back/secured/users/admin/projects/' + slug + '.json')
            .toPromise()
            .then(function(response) {
                let item = response.json();
                return new ProjectEntity(item.name, item.slug, item.repository);
            })
            .catch(error => Promise.reject(error))
        ;
    }
}

export class ProjectEntity {
    name: string;
    slug: string;
    repository: string;

    constructor(name: string, slug:string, repository:string) {
        this.name = name;
        this.slug = slug;
        this.repository = repository;
    }
}
