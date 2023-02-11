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

    /**
     * {@inheritdoc}
     *
     * @see \Psr\Log\LoggerInterface::log()
     */
    public function log($level, string|\Stringable $message, array $context = []) : void
    {
        $this->output->writeln($message);
    }
}
