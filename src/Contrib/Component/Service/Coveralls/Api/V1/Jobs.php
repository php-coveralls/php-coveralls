<?php
namespace Contrib\Component\Service\Coveralls\Api\V1;

use Contrib\Component\Service\Coveralls\Entity\V1\JsonFile;
use Contrib\Component\Service\Coveralls\Collector\V1\CloverXmlCoverageCollector;

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

    // API

    /**
     * Send json_file to jobs API.
     *
     * @param JsonFile $jsonFile json_file content.
     * @param string   $jsonPath json_file path.
     * @return array
     * @throws \RuntimeException
     */
    public function send(JsonFile $jsonFile, $jsonPath)
    {
        $jsonFile->fillJobs($_SERVER);

        file_put_contents($jsonPath, $jsonFile);

        return $this->client->upload(static::URL, $jsonPath, static::FILENAME);
    }
}
