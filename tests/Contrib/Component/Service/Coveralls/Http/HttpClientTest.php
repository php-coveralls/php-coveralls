<?php
namespace Contrib\Component\Http;

class HttpClientTest extends \PHPUnit_Framework_TestCase
{
    protected function setUp()
    {
        $this->url      = 'http://test-api';
        $this->path     = '/path/to/test.json';
        $this->filename = 'test.json';
        $this->post = array(
                $this->filename => '@' . $this->path,
        );

        $this->adapter = $this->createAdapterMock($this->url, $this->post);
        $this->object = new HttpClient($this->adapter);
    }

    protected function createAdapterMock($url, $post)
    {
        $adapter = $this->getMock('Contrib\Component\Http\Adapter\CurlAdapter', array('send'));

        // expected parameters
        $params = array(
            CURLOPT_URL            => $url,
            CURLOPT_POST           => true,
            CURLOPT_POSTFIELDS     => $post,
            CURLOPT_RETURNTRANSFER => true,
            CURLOPT_FOLLOWLOCATION => true,
        );

        $adapter
        ->expects($this->once())
        ->method('send')
        ->with($this->equalTo($params));

        return $adapter;
    }

    // upload()

    /**
     * @test
     */
    public function upload()
    {
        $this->object->upload($this->url, $this->path, $this->filename);
    }
}
