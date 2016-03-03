var gulp = require('gulp'),
    connect = require('./src-js/gulp/gulp-connect-symfony'),
    browserSync = require('browser-sync'),
    fs = require('fs'),
    ts = require('gulp-typescript'),
    path = require('path'),
    fsExtra = require('fs-extra');

// Consts for config
const APP_DIR = 'src-js/app';
const APP_DEST = 'web/app';
const TS_CONFIG = {
    "target": "es5",
    "module": "system",
    "moduleResolution": "node",
    "sourceMap": true,
    "emitDecoratorMetadata": true,
    "experimentalDecorators": true,
    "removeComments": false,
    "noImplicitAny": false
};


gulp.task('clean.dev', function () {
    fsExtra.removeSync(APP_DEST);
});

gulp.task('build.js.dev', ['clean.dev'], function () {
    gulp.src(['./' + APP_DIR + '/**/*.ts'])
        .pipe(ts(TS_CONFIG))
        .pipe(gulp.dest('./' + APP_DEST));
});

gulp.task('watch.dev', function () {
    gulp.watch([APP_DIR + '/**/*.ts'], function (context) {
        var compileToFile = context.path.replace(__dirname + '/' + APP_DIR, APP_DEST);
        compileToFile = compileToFile.substring(0, compileToFile.length -2) + 'js';

        if (context.type === 'changed' || context.type === 'added') {
            gulp.src(context.path)
                .pipe(ts(TS_CONFIG))
                .pipe(gulp.dest(path.dirname(compileToFile)));

        } else if (context.type === 'deleted') {
            fs.unlinkSync(compileToFile);
        }

        console.info('File ' + context.path  + ' was ' + context.type);
    });
});

gulp.task('serve', ['build.js.dev', 'watch.dev'], function() {
    connect.server({}, function () {
        browserSync({
            proxy: '127.0.0.1:8000'
        });
    });


    gulp.watch([APP_DEST + '/**/*.js', 'src/**/*.php']).on('change', function () {
        console.log('RELOAD§');
        browserSync.reload();
    });
});

//gulp.task('default', ['connect']);
gulp.task('default', ['serve']);
