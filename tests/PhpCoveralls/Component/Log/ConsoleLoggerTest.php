<?php

namespace PhpCoveralls\Component\Log;

use Symfony\Component\Console\Output\StreamOutput;

/**
 * @covers \PhpCoveralls\Component\Log\ConsoleLogger
 *
 * @author Kitamura Satoshi <with.no.parachute@gmail.com>
 */
class ConsoleLoggerTest extends \PHPUnit_Framework_TestCase
{
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

    protected function createAdapterMockWith($message)
    {
        $mock = $this->prophesize(StreamOutput::class);
        $mock
            ->writeln($message)
            ->shouldBeCalled();

        return $mock->reveal();
    }
}
