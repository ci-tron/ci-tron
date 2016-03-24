///<reference path="../../web/jspm_packages/npm/angular2@2.0.0-beta.12/typings/browser.d.ts"/>

// Angular2 requirements. Needed because of systemjs.
import 'zone.js/dist/zone';
import 'zone.js/dist/long-stack-trace-zone';
import 'reflect-metadata';

import {bootstrap}    from 'angular2/platform/browser'
import {AppComponent} from './app.component'

bootstrap(AppComponent);
