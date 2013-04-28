<?php
namespace Contrib\Component\Service\Coveralls\V1\Config;

/**
 * Coveralls API configuration.
 *
 * @author Kitamura Satoshi <with.no.parachute@gmail.com>
 */
class Configuration
{
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
     * Absolute path to src directory to include coverage report.
     *
     * @var string
     */
    protected $srcDir;

    /**
     * Absolute path to clover.xml.
     *
     * @var string
     */
    protected $cloverXmlPath;

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
     * @var boolean
     */
    protected $dryRun = true;

    /**
     * Whether to show log.
     *
     * @var boolean
     */
    protected $verbose = false;

    // accessor

    /**
     * Set repository token.
     *
     * @param string $repoToken
     * @return \Contrib\Component\Service\Coveralls\V1\Config\Configuration
     */
    public function setRepoToken($repoToken)
    {
        $this->repoToken = $repoToken;

        return $this;
    }

    /**
     * Return whether repository token is configured.
     *
     * @return boolean
     */
    public function hasRepoToken()
    {
        return isset($this->repoToken);
    }

    /**
     * Return repository token.
     *
     * @return string|NULL
     */
    public function getRepoToken()
    {
        return $this->repoToken;
    }

    /**
     * Set service name.
     *
     * @param string $serviceName
     * @return \Contrib\Component\Service\Coveralls\V1\Config\Configuration
     */
    public function setServiceName($serviceName)
    {
        $this->serviceName = $serviceName;

        return $this;
    }

    /**
     * Return whether the service name is configured.
     *
     * @return boolean
     */
    public function hasServiceName()
    {
        return isset($this->serviceName);
    }

    /**
     * Return service name.
     *
     * @return string|NULL
     */
    public function getServiceName()
    {
        return $this->serviceName;
    }


    /**
     * Set absolute path to src directory to include coverage report.
     *
     * @param string $srcDir
     * @return \Contrib\Component\Service\Coveralls\V1\Config\Configuration
     */
    public function setSrcDir($srcDir)
    {
        $this->srcDir = $srcDir;

        return $this;
    }

    /**
     * Return absolute path to src directory to include coverage report.
     *
     * @return string
     */
    public function getSrcDir()
    {
        return $this->srcDir;
    }

    /**
     * Set absolute path to clover.xml.
     *
     * @param string $cloverXmlPath
     * @return \Contrib\Component\Service\Coveralls\V1\Config\Configuration
     */
    public function setCloverXmlPath($cloverXmlPath)
    {
        $this->cloverXmlPath = $cloverXmlPath;

        return $this;
    }

    /**
     * Return absolute path to clover.xml.
     *
     * @return string
     */
    public function getCloverXmlPath()
    {
        return $this->cloverXmlPath;
    }

    /**
     * Set absolute path to output json_file.
     *
     * @param string $jsonPath
     * @return \Contrib\Component\Service\Coveralls\V1\Config\Configuration
     */
    public function setJsonPath($jsonPath)
    {
        $this->jsonPath = $jsonPath;

        return $this;
    }

    /**
     * Return absolute path to output json_file.
     *
     * @return string
     */
    public function getJsonPath()
    {
        return $this->jsonPath;
    }

    /**
     * Set whether to send json_file to jobs API.
     *
     * @param boolean $dryRun
     * @return \Contrib\Component\Service\Coveralls\V1\Config\Configuration
     */
    public function setDryRun($dryRun)
    {
        $this->dryRun = $dryRun;

        return $this;
    }

    /**
     * Return whether to send json_file to jobs API.
     *
     * @return boolean
     */
    public function isDryRun()
    {
        return $this->dryRun;
    }

    /**
     * Set whether to show log.
     *
     * @param boolean $verbose
     * @return \Contrib\Component\Service\Coveralls\V1\Config\Configuration
     */
    public function setVerbose($verbose)
    {
        $this->verbose = $verbose;

        return $this;
    }

    /**
     * Return whether to show log.
     *
     * @return boolean
     */
    public function isVerbose()
    {
        return $this->verbose;
    }
}
