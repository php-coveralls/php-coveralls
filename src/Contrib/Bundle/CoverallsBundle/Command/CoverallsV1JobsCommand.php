<?php
namespace Contrib\Bundle\CoverallsBundle\Command;

use Contrib\Component\Service\Coveralls\V1\Api\Jobs;
use Contrib\Component\Service\Coveralls\V1\Config\Configurator;
use Contrib\Component\Service\Coveralls\V1\Config\Configuration;
use Guzzle\Http\Client;
use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Input\InputOption;
use Symfony\Component\Console\Output\OutputInterface;

/**
 * Coveralls Jobs API v1 command.
 *
 * @author Kitamura Satoshi <with.no.parachute@gmail.com>
 */
class CoverallsV1JobsCommand extends Command
{
    /**
     * Path to project root directory.
     *
     * @var string
     */
    private $rootDir;

    // internal method

    /**
     * {@inheritdoc}
     *
     * @see \Symfony\Component\Console\Command\Command::configure()
     */
    protected function configure()
    {
        $this
        ->setName('coveralls:v1:jobs')
        ->setDescription('Coveralls Jobs API v1')
        ->addOption(
            'config',
            '-c',
            InputOption::VALUE_OPTIONAL,
            '.coveralls.yml path',
            '.coveralls.yml'
        )
        ->addOption(
            'dry-run',
            null,
            InputOption::VALUE_NONE,
            'Do not send json_file to Jobs API'
        )
        ->addOption(
            'env',
            '-e',
            InputOption::VALUE_OPTIONAL,
            'Runtime environment name: test, dev, prod',
            'prod'
        );
    }

    /**
     * {@inheritdoc}
     *
     * @see \Symfony\Component\Console\Command\Command::execute()
     */
    protected function execute(InputInterface $input, OutputInterface $output)
    {
        $config = $this->loadConfiguration($input, $this->rootDir);

        $this->runApi($config);

        return 0;
    }

    /**
     * Load configuration.
     *
     * @param InputInterface $input   Input arguments.
     * @param string         $rootDir Path to project root directory.
     * @return \Contrib\Component\Service\Coveralls\V1\Config\Configuration
     */
    protected function loadConfiguration(InputInterface $input, $rootDir)
    {
        $coverallsYmlPath = $input->getOption('config');
        $isDryRun         = $input->getOption('dry-run');
        $verbose          = $input->getOption('verbose');
        $env              = $input->getOption('env');

        $ymlPath      = $this->rootDir . DIRECTORY_SEPARATOR . $coverallsYmlPath;
        $configurator = new Configurator();

        return $configurator
        ->load($ymlPath, $rootDir)
        ->setDryRun($isDryRun)
        ->setVerbose($verbose)
        ->setEnv($env);
    }

    /**
     * Run Jobs API.
     *
     * @param Configuration $config Configuration
     * @return array|null
     */
    protected function runApi(Configuration $config)
    {
        $client = new Client();
        $api    = new Jobs($config, $client);

        $response = $api
        ->collectCloverXml()
        ->collectGitInfo()
        ->send();

        if ($response !== null && $config->isVerbose()) {
            echo sprintf('Finish uploading. status: %s %s', $response->getStatusCode(), $response->getReasonPhrase()), PHP_EOL;
        }

        return $response;
    }

    // accessor

    /**
     * Set root directory.
     *
     * @param string $rootDir Path to project root directory.
     */
    public function setRootDir($rootDir)
    {
        $this->rootDir = $rootDir;
    }
}
