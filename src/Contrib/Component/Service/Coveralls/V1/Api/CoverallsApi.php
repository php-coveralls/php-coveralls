<?php
namespace Contrib\Component\Service\Coveralls\V1\Api;

use Contrib\Component\Http\HttpClient;

abstract class CoverallsApi
{
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
    public function __construct(HttpClient $client)
    {
        $this->client = $client;
    }
}
