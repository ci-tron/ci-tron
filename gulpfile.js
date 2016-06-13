var gulp = require('gulp'),
    connect = require('./src-js/gulp/gulp-connect-symfony'),
    browserSync = require('browser-sync'),
    fs = require('fs'),
    ts = require('gulp-typescript'),
    path = require('path'),
    fsExtra = require('fs-extra'),
    less = require('gulp-less'),
    cleanCss = require('gulp-clean-css');

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
    gulp.src([
            './' + APP_DIR + '/**/*.ts'
        ])
        .pipe(ts(TS_CONFIG))
        .pipe(gulp.dest('./' + APP_DEST));

    gulp
        .src(['./' + APP_DIR + '/style/**/*.less'])
        .pipe(less({paths: ['./node_modules/bootstrap/less']}))
        .pipe(cleanCss())
        .pipe(gulp.dest('./' + APP_DEST))
    ;

    gulp.src(['./' + APP_DIR + '/**/*.html'])
        .pipe(gulp.dest('./' + APP_DEST));
});

gulp.task('watch.dev', function () {
    gulp.watch([APP_DIR + '/**/*.ts'], function (context) {
        var compileToFile = context.path.replace(__dirname + '/' + APP_DIR, APP_DEST);

        if (context.type === 'changed' || context.type === 'added') {
            gulp.src(['./' + APP_DIR + '/**/*.ts'])
                .pipe(ts(TS_CONFIG))
                .pipe(gulp.dest('./' + APP_DEST));
            browserSync.reload();

        } else if (context.type === 'deleted') {
            fs.unlinkSync(compileToFile);
        } else {
            console.log('Action "' + context.type + '" not supported by the watcher');
        }

        console.info('File ' + context.path  + ' was ' + context.type);
    });

    gulp.watch([APP_DIR + '/**/*.html'], function () {
        gulp.src(['./' + APP_DIR + '/**/*.html'])
            .pipe(gulp.dest('./' + APP_DEST));
    });

    gulp.watch([APP_DIR + '/style/**/*.less'], function (context) {
        var compileToFile = context.path.replace(__dirname + '/' + APP_DIR, APP_DEST);

        if (context.type === 'changed' || context.type === 'added') {
            gulp
                .src(['./' + APP_DIR + '/style/*.less'])
                .pipe(less({paths: ['./node_modules/bootstrap/less']}))
                .pipe(gulp.dest('./' + APP_DEST))
                .pipe(cleanCss())
                .pipe(browserSync.stream());
            ;

        } else if (context.type === 'deleted') {
            fs.unlinkSync(compileToFile);
        } else {
            console.log('Action "' + context.type + '" not supported by the watcher');
        }
    });

    gulp.watch([APP_DEST + '/**/*.js', 'src/**/*.php']).on('change', browserSync.reload);
});

function serve(env) {
    connect.server({env: env}, function () {
        browserSync({
            proxy: '127.0.0.1:8000'
        });
    });
}

gulp.task('serve.dev', ['build.js.dev', 'watch.dev'], function () { serve('dev'); });
gulp.task('serve.test', ['build.js.dev', 'watch.dev'], function () { serve('test'); });

gulp.task('default', ['serve.dev']);
