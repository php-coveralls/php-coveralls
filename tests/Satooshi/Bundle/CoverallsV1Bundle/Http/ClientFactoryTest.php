<?php
namespace Satooshi\Bundle\CoverallsV1Bundle\Http;

use Ivory\HttpAdapter\AbstractHttpAdapter;
use Ivory\HttpAdapter\Event\Events;
use Ivory\HttpAdapter\Message\InternalRequestInterface;
use Satooshi\Bundle\CoverallsV1Bundle\Http\ClientFactory;

class ClientFactoryTest extends \PHPUnit_Framework_TestCase
{
    /**
     * @test
     * @dataProvider adapterProvider
     */
    public function shouldCreateClient($name, $class)
    {
        $client = ClientFactory::create($name);

        $this->assertInstanceOf($class, $client);

        $listeners = $client->getConfiguration()->getEventDispatcher()->getListeners();

        $this->assertCount(1, $listeners);
        $this->assertArrayHasKey(Events::POST_SEND, $listeners);

        $listener = $listeners[Events::POST_SEND];

        $this->assertCount(1, $listener);
        $this->assertArrayHasKey(0, $listener);
        $this->assertArrayHasKey(0, $listener[0]);
        $this->assertInstanceOf('Ivory\HttpAdapter\Event\Subscriber\StatusCodeSubscriber', $listener[0][0]);
    }

    /**
     * @test
     */
    public function shouldRegisterClient()
    {
        $name = 'foo';
        $class = 'Satooshi\Bundle\CoverallsV1Bundle\Http\ClientMock';

        ClientFactory::register($name, $class);
        $client = ClientFactory::create($name);

        $this->assertInstanceOf($class, $client);
    }

    /**
     * @test
     * @expectedException \InvalidArgumentException
     * @expectedExceptionMessage The HTTP client "bar" does not exist.
     */
    public function throwInvalidArgumentExceptionIfUnknowClient()
    {
        ClientFactory::create('bar');
    }

    public function adapterProvider()
    {
        $adapters = array(
            array('buzz', 'Ivory\HttpAdapter\BuzzHttpAdapter'),
            array('curl', 'Ivory\HttpAdapter\CurlHttpAdapter'),
            array('file_get_contents', 'Ivory\HttpAdapter\FileGetContentsHttpAdapter'),
            array('fopen', 'Ivory\HttpAdapter\FopenHttpAdapter'),
            array('guzzle', 'Ivory\HttpAdapter\GuzzleHttpAdapter'),
            array('httpful', 'Ivory\HttpAdapter\HttpfulHttpAdapter'),
            array('socket', 'Ivory\HttpAdapter\SocketHttpAdapter'),
            array('zend1', 'Ivory\HttpAdapter\Zend1HttpAdapter'),
            array('zend2', 'Ivory\HttpAdapter\Zend2HttpAdapter'),
        );

        if (PHP_VERSION_ID >= 54000) {
            $adapters[] = array('guzzle_http', 'Ivory\HttpAdapter\GuzzleHttpHttpAdapter');
        }

        return $adapters;
    }
}

class ClientMock extends AbstractHttpAdapter
{
    public function getName()
    {
        return 'mock';
    }

    protected function doSend(InternalRequestInterface $internalRequest)
    {

    }
}
