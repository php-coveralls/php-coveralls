<?php

require_once __DIR__ . '/../vendor/autoload.php';

use Contrib\Component\Http\HttpClient;
use Contrib\Component\Http\Adapter\CurlAdapter;
use Contrib\Component\Service\Coveralls\Collector\V1\CloverXmlCoverageCollector;
use Contrib\Component\Service\Coveralls\Api\V1\Jobs;

//TODO Configurator
// configure
$xmlFilename   = 'clover.xml';
$rootDir       = realpath(__DIR__ . '/..');
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
$xml       = simplexml_load_file($cloverXmlPath);
$collector = new CloverXmlCoverageCollector();
$jsonFile  = $collector->collect($xml, $srcDir);

// run
$client = new HttpClient(new CurlAdapter());
$api    = new Jobs($client);

//$api->send($jsonFile, $jsonPath);
