/**
 * This file is a part of ci-tron package.
 *
 * (c) Ci-tron <dev@ci-tron.org>
 *
 * For the full license, take a look to the LICENSE file
 * on the root directory of this project
 */

///<reference path="../../web/jspm_packages/npm/angular2@2.0.0-beta.12/typings/browser.d.ts"/>

// Angular2 requirements. Needed because of systemjs.
import 'zone.js/dist/zone';
import 'zone.js/dist/long-stack-trace-zone';
import 'reflect-metadata';

import {bootstrap}    from 'angular2/platform/browser'
import {AppComponent} from './app.component'
import {APP_BASE_HREF, LocationStrategy, HashLocationStrategy, ROUTER_PROVIDERS} from "angular2/router";
import {provide} from "angular2/core";

bootstrap(AppComponent, [
    ROUTER_PROVIDERS,
    provide(LocationStrategy, {useClass: HashLocationStrategy}),
    provide(APP_BASE_HREF, {useValue: '/'}
]);
