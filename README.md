# Flasknose

Project is using PHP Silex framework, phantomjs, python and R.

## Modules

### Core module

Core module communicates with Chrome extension, parser and evaluator. It receives a HTTP request and returns proper response.

### Parser

Parser is written in phantomjs and python. You can read more about it [here](https://github.com/peric/wparser/blob/master/README.md).

### Evaluator

Evaluator module is written in R and it's using already built prediction model based on randomForest.

### Chrome extension

Flasknose Chrome extension can be found in chrome-extension directory.
You can find out here how to load and test extension. https://developer.chrome.com/extensions/getstarted#unpacked

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
config.vm.synced_folder "../master-thesis/flasknose-core/exports", "/var/www/webapp/exports", {:mount_options => ['dmode=777','fmode=777'], :owner => "www-data", :group => "www-data"}
config.vm.synced_folder "../master-thesis/flasknose-core/scripts", "/var/www/webapp/scripts", {:mount_options => ['dmode=777','fmode=777'], :owner => "www-data", :group => "www-data"}
```

or

```
sudo chown -R www-data:www-data /exports
sudo chown -R www-data:www-data /scripts
```

## Screenshots

![alt tag](https://github.com/peric/flasknose/tree/master/images/01.png)

![alt tag](https://github.com/peric/flasknose/tree/master/images/03.png)