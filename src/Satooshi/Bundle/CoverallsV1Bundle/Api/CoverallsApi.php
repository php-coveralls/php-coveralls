<?php
namespace Satooshi\Bundle\CoverallsV1Bundle\Api;

use Satooshi\Bundle\CoverallsV1Bundle\Config\Configuration;
use Ivory\HttpAdapter\HttpAdapterInterface;

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
     * @var \Ivory\HttpAdapter\HttpAdapterInterface
     */
    protected $client;

    /**
     * Constructor.
     *
     * @param Configuration                           $config Configuration.
     * @param \Ivory\HttpAdapter\HttpAdapterInterface $client HTTP client.
     */
    public function __construct(Configuration $config, HttpAdapterInterface $client = null)
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
     * @param \Ivory\HttpAdapter\HttpAdapterInterface $client HTTP client.
     *
     * @return \Satooshi\Bundle\CoverallsV1Bundle\Api\CoverallsApi
     */
    public function setHttpClient(HttpAdapterInterface $client)
    {
        $this->client = $client;

        return $this;
    }

    /**
     * Return HTTP client.
     *
     * @return \Ivory\HttpAdapter\HttpAdapterInterface
     */
    public function getHttpClient()
    {
        return $this->client;
    }
}
