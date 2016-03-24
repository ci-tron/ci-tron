import {Component} from 'angular2/core';
import {HTTP_PROVIDERS}    from 'angular2/http';
import {Session} from "./user/session.service";

@Component({
    selector: 'my-app',
    providers: [HTTP_PROVIDERS, Session],
    template: `
            <p>coucou</p>
    <h1 *ngIf="logged">I\'m ci-tron !</h1>
    <div *ngIf="!logged">
        <h1>Login</h1>
        <form (ngSubmit)="onSubmit()">
            <input type="text" [(ngModel)]="username">
            <input type="password" [(ngModel)]="password">
            <button type="submit" class="btn btn-default">Submit</button>
        </form>
    </div>
    `
})

export class AppComponent {

    logged:boolean = null;

    // TODO: create a new component for the login
    username:string = '';
    password:string = '';

    constructor(private session:Session) {}

    onSubmit() {
        this.session.login(this.username, this.password).subscribe(() => {
            this.logged = true;
        }, (error) => console.error(error));
    }

    ngOnInit() {
        this.session.init().then(
            (session:Session) => this.logged = session.isActive(),
            (error) => console.error(error)
        );
    }
}
