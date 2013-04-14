<?php
namespace Contrib\Component\Http\Adapter;

/**
 * HTTP adapter interface.
 *
 * @author Kitamura Satoshi <with.no.parachute@gmail.com>
 */
interface HttpAdapter
{
    /**
     * Send a request.
     *
     * @param array $options Request options.
     * @return array
     * @throws \RuntimeException
     */
    public function send(array $options);

    /**
     * Return response.
     *
     * @return array
     */
    public function getResponse();
}
