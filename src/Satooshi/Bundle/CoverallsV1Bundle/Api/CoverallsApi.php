<?php

namespace Satooshi\Bundle\CoverallsV1Bundle\Api;

use GuzzleHttp\Client;
use Satooshi\Bundle\CoverallsV1Bundle\Config\Configuration;

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
     * @var Satooshi\Bundle\CoverallsV1Bundle\Config\Configuration
     */
    protected $config;

    /**
     * HTTP client.
     *
     * @var \GuzzleHttp\Client
     */
    protected $client;

    /**
     * Constructor.
     *
     * @param Configuration      $config configuration
     * @param \GuzzleHttp\Client $client hTTP client
     */
    public function __construct(Configuration $config, Client $client = null)
    {
        $this->config = $config;
        $this->client = $client;
    }

    // accessor

    /**
     * Return configuration.
     *
     * @return \Satooshi\Bundle\CoverallsV1Bundle\Config\Configuration
     */
    public function getConfiguration()
    {
        return $this->config;
    }

    /**
     * Set HTTP client.
     *
     * @param \GuzzleHttp\Client $client hTTP client
     *
     * @return \Satooshi\Bundle\CoverallsV1Bundle\Api\CoverallsApi
     */
    public function setHttpClient(Client $client)
    {
        $this->client = $client;

        return $this;
    }

    /**
     * Return HTTP client.
     *
     * @return \GuzzleHttp\Client
     */
    public function getHttpClient()
    {
        return $this->client;
    }
}
