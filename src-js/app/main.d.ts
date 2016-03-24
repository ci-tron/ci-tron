// This is a hotfix from https://github.com/angular/zone.js/issues/297
// It should be remove in the future when zone.js will be patch

declare module 'zone.js/dist/zone' {
    export var Zone; // this doesn't actually do anything just makes the compiler not complain about the empty module
}
declare module 'zone.js/dist/long-stack-trace-zone' {
    export var Zone; // this doesn't actually do anything just makes the compiler not complain about the empty module
}
