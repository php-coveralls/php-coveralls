<?php
namespace Satooshi\Bundle\CoverallsV1Bundle\Http;

use Ivory\HttpAdapter\Event\Subscriber\StatusCodeSubscriber;

/**
 * @author GeLo <geloen.eric@gmail.com>
 */
class ClientFactory
{
    const BUZZ              = 'buzz';
    const CURL              = 'curl';
    const FILE_GET_CONTENTS = 'file_get_contents';
    const FOPEN             = 'fopen';
    const GUZZLE            = 'guzzle';
    const GUZZLE_HTTP       = 'guzzle_http';
    const HTTPFUL           = 'httpful';
    const SOCKET            = 'socket';
    const ZEND1             = 'zend1';
    const ZEND2             = 'zend2';

    /**
     * The client mapping.
     *
     * @var array
     */
    protected static $mapping = array(
        self::BUZZ              => 'Ivory\HttpAdapter\BuzzHttpAdapter',
        self::CURL              => 'Ivory\HttpAdapter\CurlHttpAdapter',
        self::FILE_GET_CONTENTS => 'Ivory\HttpAdapter\FileGetContentsHttpAdapter',
        self::FOPEN             => 'Ivory\HttpAdapter\FopenHttpAdapter',
        self::GUZZLE            => 'Ivory\HttpAdapter\GuzzleHttpAdapter',
        self::GUZZLE_HTTP       => 'Ivory\HttpAdapter\GuzzleHttpHttpAdapter',
        self::HTTPFUL           => 'Ivory\HttpAdapter\HttpfulHttpAdapter',
        self::SOCKET            => 'Ivory\HttpAdapter\SocketHttpAdapter',
        self::ZEND1             => 'Ivory\HttpAdapter\Zend1HttpAdapter',
        self::ZEND2             => 'Ivory\HttpAdapter\Zend2HttpAdapter',
    );

    /**
     * Registers a client.
     *
     * @param string $name  The name.
     * @param string $class The class.
     */
    public static function register($name, $class)
    {
        self::$mapping[$name] = $class;
    }

    /**
     * Creates a client.
     *
     * @param string $name
     *
     * @return \Ivory\HttpAdapter\HttpAdapterInterface
     *
     * @throws \InvalidArgumentException If the client does not exist.
     */
    public static function create($name)
    {
        if (!isset(self::$mapping[$name])) {
            throw new \InvalidArgumentException(sprintf('The HTTP client "%s" does not exist.', $name));
        }

        $class = self::$mapping[$name];

        $client = new $class();
        $client->getConfiguration()->getEventDispatcher()->addSubscriber(new StatusCodeSubscriber());

        return $client;
    }
}
