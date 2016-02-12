Ci-tron
=======
[![Build Status](https://travis-ci.org/ci-tron/ci-tron.svg?branch=master)](https://travis-ci.org/ci-tron/ci-tron)
[![Join the chat at https://gitter.im/ci-tron/ci-tron](https://badges.gitter.im/ci-tron/ci-tron.svg)](https://gitter.im/ci-tron/ci-tron?utm_source=badge&utm_medium=badge&utm_campaign=pr-badge&utm_content=badge) 

Ci-tron is a **free** and **open source** continuous integration tool !

For now the project is in very hard development.

Killer features:

* Easy to install
* Easy to scale

Requirements
------------

* PHP 7.0
* NodeJS 5.x
* MariaDB


How to install
--------------

**For now this is only for development purpose ! DO NOT USE IT IN PRODUCTION, IT'S NOT READY YET.**

```
composer install
npm install

php bin/console doctrine:database:create
php bin/console doctrine:schema:create
php bin/console doctrine:fixtures:load -n
```


How to run
----------

### in a development environment

```
npm start
```
