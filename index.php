<?php
require 'vendor/autoload.php';

$app = new \Slim\Slim();

require 'app/routes/evaluate.php';

$app->run();


// TODO https://github.com/codeguy/Slim-Skeleton