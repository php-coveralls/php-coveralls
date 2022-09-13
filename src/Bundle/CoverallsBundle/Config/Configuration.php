<?php

namespace PhpCoveralls\Bundle\CoverallsBundle\Config;

/**
 * Coveralls API configuration.
 *
 * @author Kitamura Satoshi <with.no.parachute@gmail.com>
 */
class Configuration
{
    /**
     * Entry point which is used for api calls.
     *
     * @var string
     */
    protected $entryPoint;

    // same as ruby lib

    /**
     * repo_token.
     *
     * @var string
     */
    protected $repoToken;

    /**
     * service_name.
     *
     * @var string
     */
    protected $serviceName;

    // only for php lib

    /**
     * Absolute path to repository root directory.
     *
     * @var string
     */
    protected $rootDir;

    /**
     * Absolute paths to clover.xml.
     *
     * @var array
     */
    protected $cloverXmlPaths = [];

    /**
     * Absolute path to output json_file.
     *
     * @var string
     */
    protected $jsonPath;

    // from command option

    /**
     * Whether to send json_file to jobs API.
     *
     * @var bool
     */
    protected $dryRun = true;

    /**
     * Whether to exclude source files that have no executable statements.
     *
     * @var bool
     */
    protected $excludeNoStatements = false;

    /**
     * Whether to show log.
     *
     * @var bool
     */
    protected $verbose = false;

    /**
     * Runtime environment name.
     *
     * @var string
     */
    protected $env = 'prod';

    // accessor

    /**
     * Set api entry point.
     *
     * @param string $entryPoint
     *
     * @return $this
     */
    public function setEntryPoint($entryPoint): Configuration {
        $this->entryPoint = \rtrim($entryPoint, '/');

        return $this;
    }

    /**
     * Return api entry point.
     *
     * @return string
     */
    public function getEntryPoint(): string {
        return $this->entryPoint;
    }

    /**
     * Set repository token.
     *
     * @param string $repoToken
     *
     * @return $this
     */
    public function setRepoToken($repoToken): Configuration {
        $this->repoToken = $repoToken;

        return $this;
    }

    /**
     * Return whether repository token is configured.
     *
     * @return bool
     */
    public function hasRepoToken(): bool {
        return $this->repoToken !== null;
    }

    /**
     * Return repository token.
     *
     * @return null|string
     */
    public function getRepoToken(): ?string {
        return $this->repoToken;
    }

    /**
     * Set service name.
     *
     * @param string $serviceName
     *
     * @return $this
     */
    public function setServiceName(string $serviceName): Configuration {
        $this->serviceName = $serviceName;

        return $this;
    }

    /**
     * Return whether the service name is configured.
     *
     * @return bool
     */
    public function hasServiceName(): bool {
        return $this->serviceName !== null;
    }

    /**
     * Return service name.
     *
     * @return null|string
     */
    public function getServiceName(): ?string {
        return $this->serviceName;
    }

    public function setRootDir($rootDir): Configuration {
        $this->rootDir = $rootDir;

        return $this;
    }

    public function getRootDir(): string {
        return $this->rootDir;
    }

    /**
     * Set absolute paths to clover.xml.
     *
     * @param string[] $cloverXmlPaths
     *
     * @return $this
     */
    public function setCloverXmlPaths(array $cloverXmlPaths): Configuration {
        $this->cloverXmlPaths = $cloverXmlPaths;

        return $this;
    }

    /**
     * Add absolute path to clover.xml.
     *
     * @param string $cloverXmlPath
     *
     * @return $this
     */
    public function addCloverXmlPath($cloverXmlPath): Configuration {
        $this->cloverXmlPaths[] = $cloverXmlPath;

        return $this;
    }

    /**
     * Return absolute path to clover.xml.
     *
     * @return string[]
     */
    public function getCloverXmlPaths(): array {
        return $this->cloverXmlPaths;
    }

    /**
     * Set absolute path to output json_file.
     *
     * @param string $jsonPath
     *
     * @return $this
     */
    public function setJsonPath($jsonPath): Configuration {
        $this->jsonPath = $jsonPath;

        return $this;
    }

    /**
     * Return absolute path to output json_file.
     *
     * @return string
     */
    public function getJsonPath(): string
    {
        return $this->jsonPath;
    }

    /**
     * Set whether to send json_file to jobs API.
     *
     * @param bool $dryRun
     *
     * @return $this
     */
    public function setDryRun($dryRun): Configuration {
        $this->dryRun = $dryRun;

        return $this;
    }

    /**
     * Return whether to send json_file to jobs API.
     *
     * @return bool
     */
    public function isDryRun(): bool {
        return $this->dryRun;
    }

    /**
     * Set whether to exclude source files that have no executable statements.
     *
     * @param bool $excludeNoStatements
     *
     * @return $this
     */
    public function setExcludeNoStatements($excludeNoStatements): Configuration {
        $this->excludeNoStatements = $excludeNoStatements;

        return $this;
    }

    /**
     * Set whether to exclude source files that have no executable statements unless false.
     *
     * @param bool $excludeNoStatements
     *
     * @return $this
     */
    public function setExcludeNoStatementsUnlessFalse($excludeNoStatements): Configuration {
        if ($excludeNoStatements) {
            $this->excludeNoStatements = true;
        }

        return $this;
    }

    /**
     * Return whether to exclude source files that have no executable statements.
     *
     * @return bool
     */
    public function isExcludeNoStatements(): bool {
        return $this->excludeNoStatements;
    }

    /**
     * Set whether to show log.
     *
     * @param bool $verbose
     *
     * @return $this
     */
    public function setVerbose($verbose): Configuration {
        $this->verbose = $verbose;

        return $this;
    }

    /**
     * Return whether to show log.
     *
     * @return bool
     */
    public function isVerbose(): bool {
        return $this->verbose;
    }

    /**
     * Set runtime environment name.
     *
     * @param string $env runtime environment name
     *
     * @return $this
     */
    public function setEnv($env): Configuration {
        $this->env = $env;

        return $this;
    }

    /**
     * Return runtime environment name.
     *
     * @return string
     */
    public function getEnv(): string {
        return $this->env;
    }

    /**
     * Return whether the runtime environment is test.
     *
     * @return bool
     */
    public function isTestEnv(): bool {
        return $this->env === 'test';
    }

    /**
     * Return whether the runtime environment is dev.
     *
     * @return bool
     */
    public function isDevEnv(): bool {
        return $this->env === 'dev';
    }

    /**
     * Return whether the runtime environment is prod.
     *
     * @return bool
     */
    public function isProdEnv(): bool {
        return $this->env === 'prod';
    }
}
