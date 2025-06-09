<?php

namespace PhpCoveralls\Bundle\CoverallsBundle\Repository\Logging;

use Psr\Log\LoggerInterface;

class LoggerAwareLegacyTrait
{
    /**
     * Logger.
     *
     * @var \Psr\Log\LoggerInterface
     */
    protected $logger;

    /**
     * @see \Psr\Log\LoggerAwareInterface::setLogger()
     */
    public function setLogger(LoggerInterface $logger)
    {
        $this->logger = $logger;
    }
}
