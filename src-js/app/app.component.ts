import {Component, OnInit} from 'angular2/core';
import {HTTP_PROVIDERS}    from 'angular2/http';
import {Session} from "./user/session.service";
import {SecurityComponent} from "./user/security.component";

@Component({
    selector: 'my-app',
    providers: [HTTP_PROVIDERS, Session],
    directives:  [SecurityComponent],
    template: `
    <div *ngIf="logged">
        <h1 >I'm ci-tron !</h1>
        <p><a href="#" (click)="onLogout()">logout</a></p>
    </div>
    <login *ngIf="!logged" (updateUserStatus)="updateUserStatus($event)"></login>
    `
})

export class AppComponent implements OnInit {

    logged:boolean = null;

    constructor(private session:Session) {}

    updateUserStatus(log) {
        this.logged = log;
    }

    onLogout() {
        this.session.logout().then(
            (session:Session) => this.logged = session.isActive(),
            (error) => console.error(error)
        );

        return false;
    }

    ngOnInit() {
        this.session.init().then(
            (session:Session) => this.logged = session.isActive(),
            (error) => console.error(error)
        );
    }
}
