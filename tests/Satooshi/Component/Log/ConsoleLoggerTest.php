<?php

namespace Satooshi\Component\Log;

/**
 * @covers \Satooshi\Component\Log\ConsoleLogger
 *
 * @author Kitamura Satoshi <with.no.parachute@gmail.com>
 */
class ConsoleLoggerTest extends \PHPUnit_Framework_TestCase
{
    protected function createAdapterMockWith($message)
    {
        $mock = $this->prophesize('\Symfony\Component\Console\Output\StreamOutput');
        $mock
            ->writeln($message)
            ->shouldBeCalled();

        return $mock->reveal();
    }

    /**
     * @test
     */
    public function shouldWritelnToOutput()
    {
        $message = 'log test message';
        $output = $this->createAdapterMockWith($message);

        $object = new ConsoleLogger($output);

        $object->log('info', $message);
    }
}
