<?php
namespace Satooshi\Bundle\CoverallsV1Bundle\Http;

use Ivory\HttpAdapter\Event\Events;
use Satooshi\Bundle\CoverallsV1Bundle\Http\ClientFactory;

class ClientFactoryTest extends \PHPUnit_Framework_TestCase
{
    /**
     * @test
     */
    public function shouldCreateClientSubscriber()
    {
        $client = ClientFactory::create(ClientFactory::SOCKET);

        $listeners = $client->getConfiguration()->getEventDispatcher()->getListeners();

        $this->assertCount(1, $listeners);
        $this->assertArrayHasKey(Events::POST_SEND, $listeners);

        $listener = $listeners[Events::POST_SEND];

        $this->assertCount(1, $listener);
        $this->assertArrayHasKey(0, $listener);
        $this->assertArrayHasKey(0, $listener[0]);
        $this->assertInstanceOf('Ivory\HttpAdapter\Event\Subscriber\StatusCodeSubscriber', $listener[0][0]);
    }
}
