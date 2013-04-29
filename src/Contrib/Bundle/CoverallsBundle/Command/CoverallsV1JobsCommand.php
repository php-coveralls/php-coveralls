<?php
namespace Contrib\Bundle\CoverallsBundle\Command;

use Psr\Log\NullLogger;
use Contrib\Component\Log\ConsoleLogger;
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
    protected $rootDir;

    /**
     * Coveralls Jobs API.
     *
     * @var \Contrib\Component\Service\Coveralls\V1\Api\Jobs
     */
    protected $api;

    /**
     * Logger.
     *
     * @var \Psr\Log\LoggerInterface
     */
    protected $logger;

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
        $this->logger = $config->isVerbose() && !$config->isTestEnv() ? new ConsoleLogger($output) : new NullLogger();

        $this->runApi($config);

        return 0;
    }

    // for Jobs API

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
     * @param Configuration $config Configuration.
     * @return void
     */
    protected function runApi(Configuration $config)
    {
        $client    = new Client();
        $this->api = new Jobs($config, $client);

        $this
        ->collectCloverXml($config)
        ->collectGitInfo()
        ->collectEnvVars()
        ->dumpJsonFile($config)
        ->send();
    }

    /**
     * Collect clover XML into json_file.
     *
     * @param Configuration $config Configuration.
     * @return \Contrib\Bundle\CoverallsBundle\Command\CoverallsV1JobsCommand
     */
    protected function collectCloverXml(Configuration $config)
    {
        $this->logger->info(sprintf('Load coverage clover log: %s', $config->getCloverXmlPath()));
        $this->api->collectCloverXml();

        $jsonFile = $this->api->getJsonFile();

        if ($jsonFile->hasSourceFiles()) {
            $this->logger->info('Found source file: ');

            foreach ($jsonFile->getSourceFiles() as $sourceFile) {
                $this->logger->info(sprintf('  - %s', $sourceFile->getName()));
            }
        }

        return $this;
    }

    /**
     * Collect git repository info into json_file.
     *
     * @return \Contrib\Bundle\CoverallsBundle\Command\CoverallsV1JobsCommand
     */
    protected function collectGitInfo()
    {
        $this->logger->info('Collect git info');

        $this->api->collectGitInfo();

        return $this;
    }

    /**
     * Collect environment variables.
     *
     * @return \Contrib\Bundle\CoverallsBundle\Command\CoverallsV1JobsCommand
     */
    protected function collectEnvVars()
    {
        $this->logger->info('Read environment variables');

        $this->api->collectEnvVars($_SERVER);

        return $this;
    }

    /**
     * Dump uploading json file.
     *
     * @param Configuration $config Configuration.
     * @return \Contrib\Bundle\CoverallsBundle\Command\CoverallsV1JobsCommand
     */
    protected function dumpJsonFile(Configuration $config)
    {
        $this->logger->info(sprintf('Dump uploading json file: %s', $config->getJsonPath()));

        $this->api->dumpJsonFile();

        return $this;
    }

    /**
     * Send json_file to jobs API.
     *
     * @return void
     */
    protected function send()
    {
        $this->logger->info(sprintf('Upload json file to %s', Jobs::URL));

        $response = $this->api->send();

        $message =
            $response
            ? sprintf('Finish uploading. status: %s %s', $response->getStatusCode(), $response->getReasonPhrase())
            : 'Finish dry run';

        $this->logger->info($message);
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
