<?php

namespace PhpCoveralls\Bundle\CoverallsBundle\Console;

use PhpCoveralls\Bundle\CoverallsBundle\Command\CoverallsJobsCommand;
use Symfony\Component\Console\Application as BaseApplication;
use Symfony\Component\Console\Input\InputDefinition;
use Symfony\Component\Console\Input\InputInterface;

/**
 * Coveralls API application.
 *
 * @author Kitamura Satoshi <with.no.parachute@gmail.com>
 */
class Application extends BaseApplication
{
    /**
     * Path to project root directory.
     *
     * @var string
     */
    private $rootDir;

    /**
     * Constructor.
     *
     * @param string $rootDir path to project root directory
     * @param string $name    The name of the application
     * @param string $version The version of the application
     */
    public function __construct(string $rootDir, string $name = 'UNKNOWN', string $version = 'UNKNOWN')
    {
        $this->rootDir = $rootDir;

        parent::__construct($name, $version);
    }

    // accessor

    /**
     * {@inheritdoc}
     *
     * @see \Symfony\Component\Console\Application::getDefinition()
     */
    public function getDefinition(): InputDefinition
    {
        $inputDefinition = parent::getDefinition();
        // clear out the normal first argument, which is the command name
        $inputDefinition->setArguments();

        return $inputDefinition;
    }

    // internal method

    /**
     * {@inheritdoc}
     *
     * @see \Symfony\Component\Console\Application::getCommandName()
     */
    protected function getCommandName(InputInterface $input): string
    {
        return 'coveralls:v1:jobs';
    }

    /**
     * {@inheritdoc}
     *
     * @see \Symfony\Component\Console\Application::getDefaultCommands()
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
     */
    protected function createCoverallsJobsCommand(): CoverallsJobsCommand
    {
        $command = new CoverallsJobsCommand();
        $command->setRootDir($this->rootDir);

        return $command;
    }
}
