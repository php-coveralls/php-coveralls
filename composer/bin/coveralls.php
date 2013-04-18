#!/usr/bin/env php
<?php

$files = array(
    __DIR__ . '/../../vendor/autoload.php',
    __DIR__ . '/../../../../autoload.php'
);

foreach ($files as $file) {
    if (file_exists($file)) {
        include_once $file;

        define('PHP_COVERALLS_COMPOSER_INSTALL', $file);

        break;
    }
}

if (!defined('PHP_COVERALLS_COMPOSER_INSTALL')) {
    die(
        'You need to set up the project dependencies using the following commands:' . PHP_EOL .
        'curl -s http://getcomposer.org/installer | php' . PHP_EOL .
        'php composer.phar install' . PHP_EOL
    );
}

use Contrib\Component\Http\HttpClient;
use Contrib\Component\Http\Adapter\CurlAdapter;
use Contrib\Component\Service\Coveralls\V1\Api\Jobs;
use Contrib\Component\Service\Coveralls\V1\Config\Configurator;

//TODO implement command
// php app/console coveralls::v1::jobs --config=.coveralls.yml --dry-run
// --config -c = .coveralls.yml

// config
$rootDir = realpath(dirname(PHP_COVERALLS_COMPOSER_INSTALL) . '/..');

// from CLI option
$coverallsYmlPath = "$rootDir/.coveralls.yml";

// load .coveralls.yml
$configurator = new Configurator();
$config       = $configurator->load($coverallsYmlPath, $rootDir);

$config->setDryRun(false);

// run
$client = new HttpClient(new CurlAdapter());
$api    = new Jobs($config, $client);

$api->collectCloverXml()
->collectGitInfo()
->send();
