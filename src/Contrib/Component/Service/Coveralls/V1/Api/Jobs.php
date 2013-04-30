<?php
namespace Contrib\Component\Service\Coveralls\V1\Api;

use Contrib\Component\Service\Coveralls\V1\Entity\JsonFile;
use Contrib\Component\Service\Coveralls\V1\Collector\CloverXmlCoverageCollector;
use Contrib\Component\Service\Coveralls\V1\Collector\GitInfoCollector;
use Contrib\Component\Service\Coveralls\V1\Collector\CiEnvVarsCollector;
use Contrib\Component\System\Git\GitCommand;

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
     * @var Contrib\Component\Service\Coveralls\V1\Entity\JsonFile
     */
    protected $jsonFile;

    // API

    /**
     * Collect clover XML into json_file.
     *
     * @return \Contrib\Component\Service\Coveralls\V1\Api\Jobs
     */
    public function collectCloverXml()
    {
        $srcDir         = $this->config->getSrcDir();
        $cloverXmlPaths = $this->config->getCloverXmlPaths();
        $xmlCollector   = new CloverXmlCoverageCollector();

        foreach ($cloverXmlPaths as $cloverXmlPath) {
            $xml = simplexml_load_file($cloverXmlPath);

            $xmlCollector->collect($xml, $srcDir);
        }

        $this->jsonFile = $xmlCollector->getJsonFile();

        $this->jsonFile->sortSourceFiles();

        return $this;
    }

    /**
     * Collect git repository info into json_file.
     *
     * @return \Contrib\Component\Service\Coveralls\V1\Api\Jobs
     */
    public function collectGitInfo()
    {
        $command      = new GitCommand();
        $gitCollector = new GitInfoCollector($command);

        $this->jsonFile->setGit($gitCollector->collect());

        return $this;
    }

    /**
     * Collect environment variables.
     *
     * @param array $env $_SERVER environment.
     * @return \Contrib\Component\Service\Coveralls\V1\Api\Jobs
     */
    public function collectEnvVars(array $env)
    {
        $envCollector = new CiEnvVarsCollector($this->config);

        $this->jsonFile->fillJobs($envCollector->collect($env));

        return $this;
    }

    /**
     * Dump uploading json file.
     *
     * @return \Contrib\Component\Service\Coveralls\V1\Api\Jobs
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
     * @return array|null
     * @throws \RuntimeException
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
     * @param string $url      URL to upload.
     * @param string $path     File path.
     * @param string $filename Filename.
     * @return array Returns the response(s)
     */
    protected function upload($url, $path, $filename)
    {
        return $this->client
        ->post($url)
        ->addPostFiles(array($filename => $path))
        ->send();
    }

    // accessor

    /**
     * Set JsonFile.
     *
     * @param JsonFile $jsonFile json_file content.
     * @return \Contrib\Component\Service\Coveralls\V1\Api\Jobs
     */
    public function setJsonFile(JsonFile $jsonFile)
    {
        $this->jsonFile = $jsonFile;

        return $this;
    }

    /**
     * Return JsonFile.
     *
     * @return JsonFile
     */
    public function getJsonFile()
    {
        if (isset($this->jsonFile)) {
            return $this->jsonFile;
        }

        return null;
    }
}
