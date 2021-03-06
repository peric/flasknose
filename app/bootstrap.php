<?php
use Neutron\Silex\Provider\FilesystemServiceProvider;

$app = new Silex\Application();

const EXPORTS_DIRECTORY            = '../exports';
const PARSED_WEBSITES_DIRECTORY    = '../exports/parsed';
const EVALUATED_WEBSITES_DIRECTORY = '../exports/evaluated';
const EXPLAINED_WEBSITES_DIRECTORY = '../exports/explained';
const OPTIMAL_VALUES_CSV           = '../data/optimal.csv';
const SELECTED_ATTRIBUTES_CSV      = '../data/selected_attributes.csv';
const BEST_CONTRIBUTIONS_CSV       = '../data/best_contributions.csv';
const SCRIPTS_DIRECTORY            = '../scripts';
const WPARSER_DIRECTORY            = '../scripts/wparser';
const R_DIRECTORY                  = '../scripts/r';

$app['debug'] = true;

$app->register(new FilesystemServiceProvider());

return $app;
