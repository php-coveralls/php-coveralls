<?php
namespace Contrib\Component\Service\Coveralls\Api\V1;

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
