<?php

namespace PhpCoveralls\Component\Log;

/**
 * @internal
 */
trait ConsoleLoggerLogWithoutTypes
{
    /**
     * {@inheritdoc}
     *
     * @see \Psr\Log\LoggerInterface::log()
     */
    public function log($level, $message, array $context = [])
    {
        $this->output->writeln($message);
    }
}
