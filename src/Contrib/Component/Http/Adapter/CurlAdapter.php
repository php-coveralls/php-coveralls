<?php
namespace Contrib\Component\Http\Adapter;

/**
 * CURL HTTP adapter.
 *
 * @author Kitamura Satoshi <with.no.parachute@gmail.com>
 */
class CurlAdapter implements HttpAdapter
{
    /**
     * Response.
     *
     * @var array
     */
    protected $response;

    /**
     * {@inheritdoc}
     *
     * @see \Contrib\Component\Http\Adapter\HttpAdapter::send()
     */
    public function send(array $options)
    {
        $curl = curl_init();

        curl_setopt_array($curl, $options);

        $body   = curl_exec($curl);
        $header = curl_getinfo($curl);

        $this->response = array('header' => $header, 'body' => $body);

        if ($header['http_code'] != 200) {
            $message = sprintf('Failed to send request. status code: %d', $header['http_code']);

            throw new \RuntimeException($message);
        }

        curl_close($curl);

        return $this->response;
    }

    // accessor

    /**
     * {@inheritdoc}
     *
     * @see \Contrib\Component\Http\Adapter\HttpAdapter::getResponse()
     */
    public function getResponse()
    {
        if (isset($this->response)) {
            return $this->response;
        }

        return null;
    }
}
