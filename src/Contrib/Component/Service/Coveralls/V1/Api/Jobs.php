<?php
namespace Contrib\Component\Service\Coveralls\V1\Api;

use Contrib\Component\Service\Coveralls\V1\Entity\JsonFile;
use Contrib\Component\Service\Coveralls\V1\Collector\CloverXmlCoverageCollector;
use Contrib\Component\Service\Coveralls\V1\Collector\GitInfoCollector;
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
        $cloverXmlPath  = $this->config->getCloverXmlPath();

        $xml            = simplexml_load_file($cloverXmlPath);
        $xmlCollector   = new CloverXmlCoverageCollector();
        $this->jsonFile = $xmlCollector->collect($xml, $srcDir);

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
     * Send json_file to jobs API.
     *
     * @return array|null
     * @throws \RuntimeException
     */
    public function send()
    {
        $jsonPath = $this->config->getJsonPath();

        if ($this->config->hasRepoToken()) {
            $this->jsonFile->setRepoToken($this->config->getRepoToken());
        }

        $this->jsonFile->fillJobs($_SERVER);

        file_put_contents($jsonPath, $this->jsonFile);

        if ($this->config->isDryRun()) {
            return;
        }

        return $this->client->upload(static::URL, $jsonPath, static::FILENAME);
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
