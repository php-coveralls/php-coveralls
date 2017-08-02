<?php

namespace Satooshi\Bundle\CoverallsV1Bundle\Api;

use Satooshi\Bundle\CoverallsV1Bundle\Collector\CiEnvVarsCollector;
use Satooshi\Bundle\CoverallsV1Bundle\Collector\CloverXmlCoverageCollector;
use Satooshi\Bundle\CoverallsV1Bundle\Collector\GitInfoCollector;
use Satooshi\Bundle\CoverallsV1Bundle\Entity\JsonFile;
use Satooshi\Component\System\Git\GitCommand;

/**
 * Jobs API.
 *
 * @author Kitamura Satoshi <with.no.parachute@gmail.com>
 */
class Jobs extends CoverallsApi
{
    /**
     * URL for jobs API.
     *
     * @var string
     */
    const URL = 'https://coveralls.io/api/v1/jobs';

    /**
     * Filename as a POST parameter.
     *
     * @var string
     */
    const FILENAME = 'json_file';

    /**
     * JsonFile.
     *
     * @var JsonFile
     */
    protected $jsonFile;

    // API

    /**
     * Collect clover XML into json_file.
     *
     * @return $this
     */
    public function collectCloverXml()
    {
        $rootDir = $this->config->getRootDir();
        $cloverXmlPaths = $this->config->getCloverXmlPaths();
        $xmlCollector = new CloverXmlCoverageCollector();

        foreach ($cloverXmlPaths as $cloverXmlPath) {
            $xml = simplexml_load_file($cloverXmlPath);

            $xmlCollector->collect($xml, $rootDir);
        }

        $this->jsonFile = $xmlCollector->getJsonFile();

        if ($this->config->isExcludeNoStatements()) {
            $this->jsonFile->excludeNoStatementsFiles();
        }

        $this->jsonFile->sortSourceFiles();

        return $this;
    }

    /**
     * Collect git repository info into json_file.
     *
     * @return $this
     */
    public function collectGitInfo()
    {
        $command = new GitCommand();
        $gitCollector = new GitInfoCollector($command);

        $this->jsonFile->setGit($gitCollector->collect());

        return $this;
    }

    /**
     * Collect environment variables.
     *
     * @param array $env $_SERVER environment
     *
     * @throws \Satooshi\Bundle\CoverallsV1Bundle\Entity\Exception\RequirementsNotSatisfiedException
     *
     * @return $this
     */
    public function collectEnvVars(array $env)
    {
        $envCollector = new CiEnvVarsCollector($this->config);

        try {
            $this->jsonFile->fillJobs($envCollector->collect($env));
        } catch (\Satooshi\Bundle\CoverallsV1Bundle\Entity\Exception\RequirementsNotSatisfiedException $e) {
            $e->setReadEnv($envCollector->getReadEnv());

            throw $e;
        }

        return $this;
    }

    /**
     * Dump uploading json file.
     *
     * @return $this
     */
    public function dumpJsonFile()
    {
        $jsonPath = $this->config->getJsonPath();

        file_put_contents($jsonPath, $this->jsonFile);

        return $this;
    }

    /**
     * Send json_file to jobs API.
     *
     * @return \GuzzleHttp\Psr7\Response|null
     */
    public function send()
    {
        if ($this->config->isDryRun()) {
            return;
        }

        $jsonPath = $this->config->getJsonPath();

        return $this->upload(static::URL, $jsonPath, static::FILENAME);
    }

    // internal method

    /**
     * Upload a file.
     *
     * @param string $url      uRL to upload
     * @param string $path     file path
     * @param string $filename filename
     *
     * @return \GuzzleHttp\Psr7\Response
     */
    protected function upload($url, $path, $filename)
    {
        $options = [
            'multipart' => [
                [
                    'name' => $filename,
                    'contents' => fopen($path, 'r'),
                ],
            ],
        ];

        return $this->client->post($url, $options);
    }

    // accessor

    /**
     * Set JsonFile.
     *
     * @param JsonFile $jsonFile json_file content
     *
     * @return $this
     */
    public function setJsonFile(JsonFile $jsonFile)
    {
        $this->jsonFile = $jsonFile;

        return $this;
    }

    /**
     * Return JsonFile.
     *
     * @return JsonFile|null
     */
    public function getJsonFile()
    {
        return $this->jsonFile;
    }
}
