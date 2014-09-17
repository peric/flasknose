<?php
use Neutron\Silex\Provider\FilesystemServiceProvider;

$app = new Silex\Application();

const EXPORTS_DIRECTORY         = '../exports';
const PARSED_WEBSITES_DIRECTORY = '../exports/parsed';
const SCRIPTS_DIRECTORY         = '../scripts';
const WPARSER_DIRECTORY         = '../scripts/wparser';
const R_DIRECTORY               = '../scripts/r';

$app['debug'] = true;

$app->register(new FilesystemServiceProvider());

return $app;
