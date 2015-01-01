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

## Setup

### Use vagrant box (recommended)

Vagrant box is available at https://github.com/peric/vagrant-php-dev-boilerplate. After you set it up, server will be available at http://192.168.56.101.
You have to be sure to properly setup shared directory (in the [Vagrantfile](https://github.com/peric/vagrant-php-dev-boilerplate/blob/master/Vagrantfile#L14)) where synced folder has to point to the directory where your project is settled.

In my case, Vagrant box is in `/home/username/dev/vagrant-php-dev-boilerplate` and synced_folder then points to the `../master-thesis/flasknose`, which means that my project is settled in `/home/username/dev/flasknose`.

```
config.vm.synced_folder "../master-thesis/flasknose", "/var/www/webapp", id: "vagrant-root"
config.vm.synced_folder "../master-thesis/flasknose/exports", "/var/www/webapp/exports", {:mount_options => ['dmode=777','fmode=777'], :owner => "www-data", :group => "www-data"}
config.vm.synced_folder "../master-thesis/flasknose/scripts", "/var/www/webapp/scripts", {:mount_options => ['dmode=777','fmode=777'], :owner => "www-data", :group => "www-data"}
```

### Set it up on your own

* Install latest composer into project directory https://getcomposer.org/download/
* Install r-base: sudo apt-get install r-base
* Install phantomjs https://github.com/TryGhost/Ghost/wiki/Functional-testing-with-PhantomJS-and-CasperJS#phantomjs-1
* Install all python dependencies used by wparser
* change config.py to your needs

## How to use Chrome extension

To see how to load an extension, [read](https://developer.chrome.com/extensions/getstarted#unpacked) the documentation.

## Install dependencies

```
php composer.phar install
```

## Screenshots

![Alt text](https://dl.dropboxusercontent.com/u/2425826/github/01.png)

![alt tag](https://dl.dropboxusercontent.com/u/2425826/github/03.png)
