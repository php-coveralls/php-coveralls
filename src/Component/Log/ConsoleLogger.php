<?php

namespace PhpCoveralls\Component\Log;

use Psr\Log\AbstractLogger;
use Symfony\Component\Console\Output\OutputInterface;

/**
 * Console logger for php-coveralls command.
 *
 * @author Kitamura Satoshi <with.no.parachute@gmail.com>
 */
class ConsoleLogger extends AbstractLogger
{
    use ConsoleLoggerLog;

    /**
     * Output.
     *
     * @var \Symfony\Component\Console\Output\OutputInterface
     */
    protected $output;

    /**
     * Constructor.
     */
    public function __construct(OutputInterface $output)
    {
        $this->output = $output;
    }
}
