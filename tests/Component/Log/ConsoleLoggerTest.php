<?php

namespace PhpCoveralls\Tests\Component\Log;

use PhpCoveralls\Component\Log\ConsoleLogger;
use PhpCoveralls\Tests\ProjectTestCase;
use Prophecy\PhpUnit\ProphecyTrait;
use Symfony\Component\Console\Output\StreamOutput;

/**
 * @covers \PhpCoveralls\Component\Log\ConsoleLogger
 *
 * @author Kitamura Satoshi <with.no.parachute@gmail.com>
 */
class ConsoleLoggerTest extends ProjectTestCase
{
    use ProphecyTrait;
    
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

    /**
     * @param string $message
     *
     * @return StreamOutput
     */
    protected function createAdapterMockWith($message)
    {
        $mock = $this->prophesize(StreamOutput::class);
        $mock
            ->writeln($message)
            ->shouldBeCalled();

        return $mock->reveal();
    }
}
