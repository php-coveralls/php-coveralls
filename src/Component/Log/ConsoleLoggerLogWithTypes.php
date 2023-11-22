<?php

namespace PhpCoveralls\Component\Log;

/**
 * @internal
 */
trait ConsoleLoggerLogWithTypes
{
    /**
     * {@inheritdoc}
     *
     * @see \Psr\Log\LoggerInterface::log()
     */
    public function log($level, string|\Stringable $message, array $context = []): void
    {
        $this->output->writeln($message);
    }
}
