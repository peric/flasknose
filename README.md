# Flasknose-core

Project is using Silex framework. http://silex.sensiolabs.org/

## Prerequisites

* Install latest composer into project directory https://getcomposer.org/download/
* Install r-base: sudo apt-get install r-base
* Install phantomjs https://github.com/TryGhost/Ghost/wiki/Functional-testing-with-PhantomJS-and-CasperJS#phantomjs-1
* Install all python dependencies used by wparser
* change config.py to your needs

or

* use this vagrant box https://github.com/peric/vagrant-php-dev-boilerplate
* be sure to properly setup shared directory

## Install dependencies

```
php composer.phar install
```

## Vagrant - shared directory setup

```
config.vm.synced_folder "../master-thesis/flasknose-core/files", "/var/www/webapp/files", {:mount_options => ['dmode=777','fmode=777'], :owner => "www-data", :group => "www-data"}
config.vm.synced_folder "../master-thesis/flasknose-core/scripts", "/var/www/webapp/scripts", {:mount_options => ['dmode=777','fmode=777'], :owner => "www-data", :group => "www-data"}
```

or

```
sudo chown -R www-data:www-data /files
sudo chown -R www-data:www-data /scripts
```
