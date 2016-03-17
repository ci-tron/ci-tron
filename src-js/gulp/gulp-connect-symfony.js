'use strict';

var fs = require('fs');
var binVersionCheck = require('bin-version-check');
var http = require('http');
var open = require('opn');
var spawn = require('child_process').spawn;
var exec = require('child_process').exec;

module.exports = (function () {
    var checkServerTries = 0;
    var workingPort = 8000;

    function checkServer(hostname, port, cb) {
        setTimeout(function () {
            http.request({
                method: 'HEAD',
                hostname: hostname,
                port: port
            }, function (res) {
                var statusCodeType = Number(res.statusCode.toString()[0]);

                if ([2, 3, 4].indexOf(statusCodeType) !== -1) {
                    return cb();
                } else if (statusCodeType === 5) {
                    console.log(
                        'Server docroot returned 500-level response. Please check ' +
                        'your configuration for possible errors.'
                    );
                    return cb();
                }

                checkServer(hostname, port, cb);
            }).on('error', function (err) {
                // back off after 1s
                if (++checkServerTries > 20) {
                    console.log('PHP server not started. Retrying...');
                    return cb();
                }
                checkServer(hostname, port, cb);
            }).end();
        }, 50);
    }

    var closeServer = function (cb) {
        var child = exec('lsof -i :' + workingPort,
            function (error, stdout, stderr) {
                //console.log('stdout: ' + stdout);
                //console.log('stderr: ' + stderr);
                if (error !== null) {
                    console.log('exec error: ' + error);
                }

                // get pid then kill it
                var pid = stdout.match(/php\s+?([0-9]+)/)[1];
                if (pid) {
                    exec('kill ' + pid, function (error, stdout, stderr) {
                        //console.log('stdout: ' + stdout);
                        //console.log('stderr: ' + stderr);
                        cb();
                    });
                } else {
                    cb({error: "couldn't find process id and kill it"});
                }
            });
    };

    function extend(obj /* , ...source */) {
        for (var i = 1; i < arguments.length; i++) {
            for (var key in arguments[i]) {
                if (Object.prototype.hasOwnProperty.call(arguments[i], key)) {
                    obj[key] = arguments[i][key];
                    obj[key] = (typeof arguments[i][key] === 'object' && arguments[i][key] ? extend(obj[key], arguments[i][key]) : arguments[i][key]);
                }
            }
        }
        return obj;
    }


    var server = function (options, cb){
        if (!cb) {
            cb = function(){};
        }

        options = extend({
            // Symfony config
            port: 8000,             // Default symfony server port
            hostname: '127.0.0.1',  // change it to 0.0.0.0 to make the server accessible for everyone
            base: null,             // Document root
            router: null,           // custom router (bind to ser:run command)
            env: 'dev',             // environment to start the symfony server

            // Shell config
            bin: 'php',             // location of php binary
            stdio: 'inherit',       // node stdio config

            // utils config
            open: false             // Open in the brower
        }, options);
        workingPort = options.port;
        var args = [];

        var sfConsole = 'bin/console';
        try {
            fs.accessSync(sfConsole, fs.R_OK);
        } catch(e) {
            sfConsole = 'app/console';
        }
        args.push(sfConsole, 'server:run', options.hostname, '-p', options.port);

        if (options.base) {
            args.push('-d', options.base);
        }
        if (options.router) {
            args.push('-r', options.router);
        }

        args.push('-e=' + options.env);

        binVersionCheck(options.bin, '>=5.4', function (err) {
            if (err) {
                cb();
                return;
            }
            spawn(options.bin, args, {
                cwd: '.',
                stdio: options.stdio
            });

            checkServer(options.hostname, options.port, function () {
                if (options.open) {
                    open('http://' + host + options.root);
                }
                cb();
            });
        });

    };

    return {
        server: server,
        closeServer: closeServer
    }
})();
