<?php
namespace Contrib\Component\Service\Coveralls\V1\Api;

use Contrib\Component\Http\HttpClient;
use Contrib\Component\Service\Coveralls\V1\Config\Configuration;

/**
 * Coveralls API client.
 *
 * @author Kitamura Satoshi <with.no.parachute@gmail.com>
 */
abstract class CoverallsApi
{
    /**
     * Configuration.
     *
     * @var Contrib\Component\Service\Coveralls\V1\Config\Configuration
     */
    protected $config;

    /**
     * HttpClient.
     *
     * @var Contrib\Component\Http\HttpClient
     */
    protected $client;

    /**
     * Constructor.
     *
     * @param HttpClient $client
     */
    public function __construct(Configuration $config, HttpClient $client = null)
    {
        $this->config = $config;
        $this->client = $client;
    }

    // accessor

    /**
     * Return configuration.
     *
     * @return \Contrib\Component\Service\Coveralls\V1\Config\Configuration
     */
    public function getConfiguration()
    {
        return $this->config;
    }

    /**
     * Set HTTP client.
     *
     * @param HttpClient $client
     * @return \Contrib\Component\Service\Coveralls\V1\Api\CoverallsApi
     */
    public function setHttpClient(HttpClient $client)
    {
        $this->client = $client;

        return $this;
    }

    /**
     * Return HTTP client.
     *
     * @return \Contrib\Component\Http\HttpClient
     */
    public function getHttpClient()
    {
        return $this->client;
    }
}
