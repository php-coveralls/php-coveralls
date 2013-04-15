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
use Contrib\Component\Service\Coveralls\Collector\V1\CloverXmlCoverageCollector;
use Contrib\Component\Service\Coveralls\Api\V1\Jobs;
use Contrib\Component\System\Git\GitCommand;
use Contrib\Component\Service\Coveralls\Collector\V1\GitInfoCollector;

//TODO Configurator
// configure
$xmlFilename   = 'clover.xml';
$loaderPath    = realpath(PHP_COVERALLS_COMPOSER_INSTALL);
$rootDir       = realpath(dirname($loaderPath) . '/..');
$logsDir       = realpath("$rootDir/build/logs");

if ($logsDir === false || !is_dir($logsDir)) {
    throw new \RuntimeException('build/logs directory does not exist');
}

$cloverXmlPath = realpath("$logsDir/$xmlFilename");

if ($cloverXmlPath === false || !file_exists($cloverXmlPath)) {
    $message = sprintf('Not found %s', $xmlFilename);

    throw new \RuntimeException($message);
}

$srcDir   = realpath("$rootDir/src");
$jsonPath = "$logsDir/coveralls.json";

// collect coverage
$xml          = simplexml_load_file($cloverXmlPath);
$xmlCollector = new CloverXmlCoverageCollector();
$jsonFile     = $xmlCollector->collect($xml, $srcDir);

// collect git
$gitCollector = new GitInfoCollector(new GitCommand());

$jsonFile->setGit($gitCollector->collect()->toArray());

// run
$client = new HttpClient(new CurlAdapter());
$api    = new Jobs($client);

$api->send($jsonFile, $jsonPath);
