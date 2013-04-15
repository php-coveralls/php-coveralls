<?php
namespace Contrib\Component\Http;

use Contrib\Component\Http\Adapter\HttpAdapter;

/**
 * HTTP client.
 *
 * @author Kitamura Satoshi <with.no.parachute@gmail.com>
 */
class HttpClient
{
    /**
     * HttpAdapter object.
     *
     * @var HttpAdapter
     */
    protected $adapter;

    /**
     * Constructor.
     *
     * @param HttpAdapter $adapter
     */
    public function __construct(HttpAdapter $adapter)
    {
        $this->adapter = $adapter;
    }

    // API

    /**
     * Upload a file.
     *
     * @param string $url      URL to upload.
     * @param string $path     File path.
     * @param string $filename Filename.
     * @return array
     * @throws \RuntimeException
     */
    public function upload($url, $path, $filename)
    {
        // since PHP 5.5 CurlFile
        // until PHP 5.4 @path
        $file = class_exists('CurlFile') ? new \CurlFile($path) : '@' . $path;
        $post = array(
            $filename => $file,
        );

        return $this->adapter->send(
            array(
                CURLOPT_URL            => $url,
                CURLOPT_POST           => true,
                CURLOPT_POSTFIELDS     => $post,
                CURLOPT_RETURNTRANSFER => true,
                CURLOPT_FOLLOWLOCATION => true,
            )
        );
    }
}
