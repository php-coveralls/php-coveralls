<?php
namespace Contrib\Component\Http\Adapter;

class CurlAdapterTest extends \PHPUnit_Framework_TestCase
{
    protected function setUp()
    {
        $url = 'http://www.google.com';
        $this->options = array(
            CURLOPT_URL            => $url,
            CURLOPT_POST           => false,
            CURLOPT_RETURNTRANSFER => true,
            CURLOPT_FOLLOWLOCATION => true,
        );

        $this->object = new CurlAdapter();
    }

    // getResponse()

    /**
     * @test
     */
    public function shouldNotHaveResponseOnConstruction()
    {
        $this->assertNull($this->object->getResponse());
    }

    // send()

    /**
     * @test
     */
    public function send()
    {
        $response = $this->object->send($this->options);

        $this->assertNotEmpty($response['header']);
        $this->assertTrue(is_string($response['body']));
        $this->assertSame($response, $this->object->getResponse());
    }

    /**
     * @test
     * @expectedException RuntimeException
     */
    public function throwRuntimeExceptionOnSendNonExistenceUrl()
    {
        $this->options[CURLOPT_URL] = '';

        $response = $this->object->send($this->options);
    }
}
