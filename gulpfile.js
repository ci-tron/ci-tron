var gulp = require('gulp'),
    connect = require('./src-js/gulp/gulp-connect-symfony'),
    browserSync = require('browser-sync'),
    fs = require('fs');

function copyAssets() {
    try {
        fs.symlinkSync(__dirname + '/node_modules', __dirname+ '/web/node_modules');
        fs.symlinkSync(__dirname + '/src-js', __dirname + '/web/src-js');
    } catch (e) {
        if (e.code !== 'EEXIST') {
            throw e;
        }
    }
}

gulp.task('connect', function() {
    console.log('Starting *dev* environment.');
    copyAssets();

    connect.server({}, function () {
        browserSync({
            proxy: '127.0.0.1:8000'
        });
    });
    gulp.watch(['src/**/*.php', 'src-js/**/*.js']).on('change', function () {
        browserSync.reload();
    });
});

gulp.task('default', ['connect']);
