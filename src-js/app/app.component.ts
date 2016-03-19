import {Component} from 'angular2/core';
import {Session} from "./user/session.service";

@Component({
    selector: 'my-app',
    template: `
    <h1 *ngIf="logged">I\'m ci-tron !</h1>
    <h1 *ngIf="!logged">login</h1>
    `
})

export class AppComponent {

    logged:boolean = null;

    constructor(private session:Session) {}

    ngOnInit() {
        this.session.init().then(
            (session:Session) => this.logged = session.isActive(),
            (error) => console.error(error)
        );
    }
}
