<?php
namespace Satooshi\Bundle\CoverallsV1Bundle\Repository;

use Ivory\HttpAdapter\HttpAdapterException;
use Psr\Http\Message\IncomingResponseInterface;
use Psr\Log\LoggerAwareInterface;
use Psr\Log\LoggerInterface;
use Satooshi\Bundle\CoverallsV1Bundle\Api\Jobs;
use Satooshi\Bundle\CoverallsV1Bundle\Config\Configuration;
use Satooshi\Bundle\CoverallsV1Bundle\Entity\JsonFile;

/**
 * Jobs API client.
 *
 * Just wrap for logging.
 *
 * @author Kitamura Satoshi <with.no.parachute@gmail.com>
 */
class JobsRepository implements LoggerAwareInterface
{
    /**
     * Jobs API.
     *
     * @var \Satooshi\Bundle\CoverallsV1Bundle\Api\Jobs
     */
    protected $api;

    /**
     * Configuration.
     *
     * @var \Satooshi\Bundle\CoverallsV1Bundle\Config\Configuration
     */
    protected $config;

    /**
     * Logger.
     *
     * @var \Psr\Log\LoggerInterface
     */
    protected $logger;

    /**
     * Constructor.
     *
     * @param Jobs          $api    API.
     * @param Configuration $config Configuration.
     */
    public function __construct(Jobs $api, Configuration $config)
    {
        $this->api    = $api;
        $this->config = $config;
    }

    // API

    /**
     * Persist coverage data to Coveralls.
     *
     * @return void
     */
    public function persist()
    {
        try {
            $this
            ->collectCloverXml()
            ->collectGitInfo()
            ->collectEnvVars()
            ->dumpJsonFile()
            ->send();
        } catch (\Satooshi\Bundle\CoverallsV1Bundle\Entity\Exception\RequirementsNotSatisfiedException $e) {
            $this->logger->error($e->getHelpMessage());
        } catch (\Exception $e) {
            $this->logger->error(sprintf("%s\n\n%s", $e->getMessage(), $e->getTraceAsString()));
        }
    }

    // internal method

    /**
     * Collect clover XML into json_file.
     *
     * @return \Satooshi\Bundle\CoverallsV1Bundle\Repository\JobsRepository
     */
    protected function collectCloverXml()
    {
        $this->logger->info('Load coverage clover log:');

        foreach ($this->config->getCloverXmlPaths() as $path) {
            $this->logger->info(sprintf('  - %s', $path));
        }

        $jsonFile = $this->api->collectCloverXml()->getJsonFile();

        if ($jsonFile->hasSourceFiles()) {
            $this->logCollectedSourceFiles($jsonFile);
        }

        return $this;
    }

    /**
     * Collect git repository info into json_file.
     *
     * @return \Satooshi\Bundle\CoverallsV1Bundle\Repository\JobsRepository
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
     * @return \Satooshi\Bundle\CoverallsV1Bundle\Repository\JobsRepository
     */
    protected function collectEnvVars()
    {
        $this->logger->info('Read environment variables');

        $this->api->collectEnvVars($_SERVER);

        return $this;
    }

    /**
     * Dump submitting json file.
     *
     * @return \Satooshi\Bundle\CoverallsV1Bundle\Repository\JobsRepository
     */
    protected function dumpJsonFile()
    {
        $jsonPath = $this->config->getJsonPath();
        $this->logger->info(sprintf('Dump submitting json file: %s', $jsonPath));

        $this->api->dumpJsonFile();

        $filesize = number_format(filesize($jsonPath) / 1024, 2); // kB
        $this->logger->info(sprintf('File size: <info>%s</info> kB', $filesize));

        return $this;
    }

    /**
     * Send json_file to Jobs API.
     *
     * @return void
     */
    protected function send()
    {
        $this->logger->info(sprintf('Submitting to %s', Jobs::URL));

        try {
            $response = $this->api->send();

            $message = $response
                ? sprintf('Finish submitting. status: %s %s', $response->getStatusCode(), $response->getReasonPhrase())
                : 'Finish dry run';

            $this->logger->info($message);

            if ($response instanceof IncomingResponseInterface) {
                $this->logResponse($response);
            }

            return;
        } catch (HttpAdapterException $e) {
            $this->logger->error($e->getMessage());

            if ($e->hasResponse()) {
                $this->logResponse($e->getResponse());
            }
        } catch (\Exception $e) {
            $this->logger->error(sprintf("%s\n\n%s", $e->getMessage(), $e->getTraceAsString()));
        }
    }

    // logging

    /**
     * Colorize coverage.
     *
     * * green  90% - 100% <info>
     * * yellow 80% -  90% <comment>
     * * red     0% -  80% <fg=red>
     *
     * @param float  $coverage Coverage.
     * @param string $format   Format string to colorize.
     *
     * @return string
     */
    protected function colorizeCoverage($coverage, $format)
    {
        if ($coverage >= 90) {
            return sprintf('<info>%s</info>', $format);
        } elseif ($coverage >= 80) {
            return sprintf('<comment>%s</comment>', $format);
        } else {
            return sprintf('<fg=red>%s</fg=red>', $format);
        }
    }

    /**
     * Log collected source files.
     *
     * @param JsonFile $jsonFile Json file.
     *
     * @return void
     */
    protected function logCollectedSourceFiles(JsonFile $jsonFile)
    {
        $sourceFiles = $jsonFile->getSourceFiles();
        $numFiles    = count($sourceFiles);

        $this->logger->info(sprintf('Found <info>%s</info> source file%s:', number_format($numFiles), $numFiles > 1 ? 's' : ''));

        foreach ($sourceFiles as $sourceFile) {
            /* @var $sourceFile \Satooshi\Bundle\CoverallsV1Bundle\Entity\SourceFile */
            $coverage = $sourceFile->reportLineCoverage();
            $template = '  - ' . $this->colorizeCoverage($coverage, '%6.2f%%') . ' %s';

            $this->logger->info(sprintf($template, $coverage, $sourceFile->getName()));
        }

        $coverage = $jsonFile->reportLineCoverage();
        $template = 'Coverage: ' . $this->colorizeCoverage($coverage, '%6.2f%% (%d/%d)');
        $metrics  = $jsonFile->getMetrics();

        $this->logger->info(sprintf($template, $coverage, $metrics->getCoveredStatements(), $metrics->getStatements()));
    }

    /**
     * Log response.
     *
     * @param IncomingResponseInterface $response API response.
     *
     * @return void
     */
    protected function logResponse(IncomingResponseInterface $response)
    {
        $body = (string) $response->getBody();
        $json = json_decode($body, true);

        if (empty($json)) {
            $this->logger->error($body);

            return;
        }

        if (isset($json['error'])) {
            if (isset($json['message'])) {
                $this->logger->error($json['message']);
            }
        } else {
            if (isset($json['message'])) {
                $this->logger->info(sprintf('Accepted %s', $json['message']));
            }

            if (isset($json['url'])) {
                $this->logger->info(sprintf('You can see the build on %s', $json['url']));
            }
        }
    }

    // LoggerAwareInterface

    /**
     * {@inheritdoc}
     *
     * @return void
     *
     * @see \Psr\Log\LoggerAwareInterface::setLogger()
     */
    public function setLogger(LoggerInterface $logger)
    {
        $this->logger = $logger;
    }
}
