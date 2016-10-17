/**
 * This file is a part of ci-tron package.
 *
 * (c) Ci-tron <dev@ci-tron.org>
 *
 * For the full license, take a look to the LICENSE file
 * on the root directory of this project
 */
import { NgModule } from '@angular/core';
import { HttpModule } from "@angular/http";
import { BrowserModule }  from '@angular/platform-browser';
import { AppComponent } from './app.component';
import { LoginComponent } from './user/security.component';
import { Session } from './user/session.service';
import { FormsModule }   from '@angular/forms';

@NgModule({
    imports: [ BrowserModule, FormsModule, HttpModule ],
    declarations: [ AppComponent, LoginComponent ],
    bootstrap: [ AppComponent ],
    providers: [ Session ]
})

export class AppModule { }
