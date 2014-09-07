<?php
// Load the libraries
require_once __DIR__.'/../vendor/autoload.php';

// Create the application
/** @var Silex\Application() $app */
$app = require __DIR__.'/../app/bootstrap.php';

// Load the controllers
require __DIR__.'/../src/app.php';

$app->run();
