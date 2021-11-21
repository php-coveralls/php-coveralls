<?php

declare(strict_types=1);

namespace PhpCoveralls\Bundle\CoverallsBundle\Console;

use PhpCoveralls\Bundle\CoverallsBundle\Command\CoverallsJobsCommand;
use Symfony\Component\Console\Application;
use Symfony\Component\Console\Input\InputDefinition;
use Symfony\Component\Console\Input\InputInterface;

/**
 * Coveralls API application for Symfony 6 and higher
 *
 * @author Viktor Linkin <adrenalinkin@gmail.com>
 */
class ApplicationSf6 extends Application
{
    /**
     * Path to project root directory.
     *
     * @var string
     */
    private $rootDir;

    /**
     * @param string $rootDir path to project root directory
     * @param string $name    The name of the application
     * @param string $version The version of the application
     */
    public function __construct($rootDir, $name = 'UNKNOWN', $version = 'UNKNOWN')
    {
        $this->rootDir = $rootDir;

        parent::__construct($name, $version);
    }

    /**
     * {@inheritdoc}
     */
    public function getDefinition(): InputDefinition
    {
        $inputDefinition = parent::getDefinition();
        // clear out the normal first argument, which is the command name
        $inputDefinition->setArguments();

        return $inputDefinition;
    }

    /**
     * {@inheritdoc}
     */
    protected function getCommandName(InputInterface $input): ?string
    {
        return 'coveralls:v1:jobs';
    }

    /**
     * {@inheritdoc}
     */
    protected function getDefaultCommands(): array
    {
        // Keep the core default commands to have the HelpCommand
        // which is used when using the --help option
        $defaultCommands = parent::getDefaultCommands();

        $defaultCommands[] = $this->createCoverallsJobsCommand();

        return $defaultCommands;
    }

    /**
     * Create CoverallsJobsCommand.
     *
     * @return CoverallsJobsCommand
     */
    protected function createCoverallsJobsCommand()
    {
        $command = new CoverallsJobsCommand();
        $command->setRootDir($this->rootDir);

        return $command;
    }
}
