<?php
use Neutron\Silex\Provider\FilesystemServiceProvider;

$app = new Silex\Application();

const DATA_DIRECTORY            = '../data';
const PARSED_WEBSITES_DIRECTORY = '../data/parsed';
const SCRIPTS_DIRECTORY         = '../scripts';
const WPARSER_DIRECTORY         = '../scripts/wparser';

$app['debug'] = true;

$app->register(new FilesystemServiceProvider());

return $app;

//$app['cache.path'] = __DIR__ . '/cache';
//
//$app->register(new SessionServiceProvider());
//$app->register(new UrlGeneratorServiceProvider());
//
//$app->register(new HttpCacheServiceProvider());
//$app['http_cache.cache_dir'] = $app['cache.path'] . '/http';
//
//$app->register(new MonologServiceProvider(), array(
//    'monolog.logfile'       => __DIR__.'/log/app.log',
//    'monolog.name'          => 'kp_app',
//    'monolog.level'         => 300 // = Logger::WARNING
//));
//
//$app->register(new TwigServiceProvider(), array(
//    'twig.path'             => array(__DIR__ . '/../src/views'),
//    'twig.options'          => array(
//        'cache' => false, // __DIR__ . '/cache',
//        'strict_variables' => true
//    ),
//));
//
