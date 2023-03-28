<?php

if (PHP_VERSION_ID >= 80000) {
    $logMethod = new ReflectionMethod(\Psr\Log\AbstractLogger::class, 'log');

    if ($logMethod->hasReturnType()) {
        class_alias(
            \PhpCoveralls\Component\Log\ConsoleLoggerLogWithTypes::class,
            \PhpCoveralls\Component\Log\ConsoleLoggerLog::class
        );

        return;
    }
}

class_alias(
    \PhpCoveralls\Component\Log\ConsoleLoggerLogWithoutTypes::class,
    \PhpCoveralls\Component\Log\ConsoleLoggerLog::class
);
