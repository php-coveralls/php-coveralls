<?php
namespace Satooshi\Bundle\CoverallsV1Bundle\Http;

use Ivory\HttpAdapter\Event\Subscriber\StatusCodeSubscriber;
use Ivory\HttpAdapter\HttpAdapterFactory;

/**
 * @author GeLo <geloen.eric@gmail.com>
 */
class ClientFactory extends HttpAdapterFactory
{
    /**
     * {@inheritdoc}
     */
    public static function create($name)
    {
        $client = parent::create($name);
        $client->getConfiguration()->getEventDispatcher()->addSubscriber(new StatusCodeSubscriber());

        return $client;
    }
}
