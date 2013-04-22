<?php
namespace Contrib\Component\Service\Coveralls\V1\Entity;

use Contrib\Component\Service\Coveralls\V1\Entity\Git\Git;

/**
 * Data represents "json_file" of Coveralls API.
 *
 * @author Kitamura Satoshi <with.no.parachute@gmail.com>
 */
class JsonFile extends Coveralls
{
    /**
     * Service name.
     *
     * @var string
     */
    protected $serviceName;

    /**
     * Service job id.
     *
     * @var string
     */
    protected $serviceJobId;

    /**
     * Service number (not documented).
     *
     * @var string
     */
    protected $serviceNumber;

    /**
     * Service event type (not documented).
     *
     * @var string
     */
    protected $serviceEventType;

    /**
     * Repository token.
     *
     * @var string
     */
    protected $repoToken;

    /**
     * Source files.
     *
     * @var SourceFile[]
     */
    protected $sourceFiles = array();

    /**
     * Git data.
     *
     * @var array
     */
    protected $git;

    /**
     * A timestamp when the job ran. Must be parsable by Ruby.
     *
     * "2013-02-18 00:52:48 -0800"
     *
     * @var string
     */
    protected $runAt;

    // API

    /**
     * {@inheritdoc}
     *
     * @see \Contrib\Component\Service\Coveralls\Entity\ArrayConvertable::toArray()
     */
    public function toArray()
    {
        $array = array();

        $arrayMap = array(
            // json key => property name
            'service_name'       => 'serviceName',
            'service_job_id'     => 'serviceJobId',
            'service_number'     => 'serviceNumber',
            'service_event_type' => 'serviceEventType',
            'repo_token'         => 'repoToken',
            'git'                => 'git',
            'run_at'             => 'runAt',
            'source_files'       => 'sourceFiles',
        );

        foreach ($arrayMap as $jsonKey => $propName) {
            if (isset($this->$propName)) {
                $array[$jsonKey] = $this->toJsonProperty($this->$propName);
            }
        }

        return $array;
    }

    /**
     * Add SourceFile.
     *
     * @param SourceFile $sourceFile
     */
    public function addSourceFile(SourceFile $sourceFile)
    {
        $this->sourceFiles[] = $sourceFile;
    }

    /**
     * Return whether the SourceFile object exists.
     *
     * @return boolean
     */
    public function hasSourceFiles()
    {
        return count($this->sourceFiles) > 0;
    }

    /**
     * Fill environment variables.
     *
     * @param array $env $_SERVER environment.
     * @return \Contrib\Component\Service\Coveralls\Entity\V1\JsonFile
     * @throws \RuntimeException
     */
    public function fillJobs(array $env)
    {
        return $this
        ->fillTravisCi($env)
        ->fillCircleCi($env)
        ->fillJenkins($env)
        //->fillCodeship($env)
        ->fillLocal($env)
        ->fillRepoToken($env)
        ->ensureJobs();
    }

    // internal method

    /**
     * Convert to json property.
     *
     * @param mixed $prop
     * @return mixed
     */
    protected function toJsonProperty($prop)
    {
        if ($prop instanceof Coveralls) {
            return $prop->toArray();
        } else if (is_array($prop)) {
            return $this->toJsonPropertyArray($prop);
        }

        return $prop;
    }

    /**
     * Convert to array as json property.
     *
     * @param array $propArray
     * @return array
     */
    protected function toJsonPropertyArray(array $propArray)
    {
        $array = array();

        foreach ($propArray as $prop) {
            $array[] = $this->toJsonProperty($prop);
        }

        return $array;
    }

    /**
     * Ensure data consistency for jobs API.
     *
     * @return \Contrib\Component\Service\Coveralls\Entity\V1\JsonFile
     * @throws \RuntimeException
     */
    protected function ensureJobs()
    {
        if (!$this->hasSourceFiles()) {
            throw new \RuntimeException('source_files must be set');
        }

        if ($this->requireServiceJobId()) {
            return $this;
        }

        if ($this->requireServiceNumber()) {
            return $this;
        }

        if ($this->requireServiceEventType()) {
            return $this;
        }

        if ($this->isUnsupportedServiceJob()) {
            return $this;
        }

        $message = 'requirements are not satisfied.';

        throw new \RuntimeException($message);
    }

    /**
     * Fill Travis CI environment variables.
     *
     * "TRAVIS_JOB_ID" must be set.
     *
     * @param array $env $_SERVER environment.
     * @return \Contrib\Component\Service\Coveralls\Entity\V1\JsonFile
     */
    protected function fillTravisCi(array $env)
    {
        if (isset($env['TRAVIS']) && $env['TRAVIS'] && isset($env['TRAVIS_JOB_ID'])) {
            $this->serviceJobId = $env['TRAVIS_JOB_ID'];

            if (!isset($this->serviceName)) {
                $this->serviceName = 'travis-ci';
            }
        }

        return $this;
    }

    /**
     * Fill CircleCI environment variables.
     *
     * "CIRCLE_BUILD_NUM" must be set.
     *
     * @param array $env $_SERVER environment.
     * @return \Contrib\Component\Service\Coveralls\Entity\V1\JsonFile
     */
    protected function fillCircleCi(array $env)
    {
        if (isset($env['CIRCLECI']) && $env['CIRCLECI'] && isset($env['CIRCLE_BUILD_NUM'])) {
            $this->serviceNumber = $env['CIRCLE_BUILD_NUM'];
            $this->serviceName   = 'circleci';
        }

        return $this;
    }

    /**
     * Fill Jenkins environment variables.
     *
     * "BUILD_NUMBER" must be set.
     *
     * @param array $env $_SERVER environment.
     * @return \Contrib\Component\Service\Coveralls\Entity\V1\JsonFile
     */
    protected function fillJenkins(array $env)
    {
        if (isset($env['JENKINS_URL']) && isset($env['BUILD_NUMBER'])) {
            $this->serviceNumber = $env['BUILD_NUMBER'];
            $this->serviceName   = 'jenkins';
        }

        return $this;
    }

    /**
     * Fill Codeship environment variables.
     *
     * "CODESHIP" must be set.
     *
     * @param array $env $_SERVER environment.
     * @return \Contrib\Component\Service\Coveralls\V1\Entity\JsonFile
     * @codeCoverageIgnore
     */
    protected function fillCodeship(array $env)
    {
        if (isset($env['CODESHIP']) && $env['CODESHIP']) {
            $this->serviceName = 'codeship';

            // Coveralls needs some kind of build number as its service number
            // but Codeship currently does not have correspondance env var.
            //$this->serviceNumber = $env['BUILD_NUMBER'];
        }

        return $this;
    }

    /**
     * Fill local environment variables.
     *
     * "COVERALLS_RUN_LOCALLY" must be set.
     *
     * @param array $env $_SERVER environment.
     * @return \Contrib\Component\Service\Coveralls\Entity\V1\JsonFile
     */
    protected function fillLocal(array $env)
    {
        if (isset($env['COVERALLS_RUN_LOCALLY']) && $env['COVERALLS_RUN_LOCALLY']) {
            $this->serviceJobId     = null;
            $this->serviceName      = 'php-coveralls';
            $this->serviceEventType = 'manual';
        }

        return $this;
    }

    /**
     * Fill repo_token for unsupported CI service.
     *
     * "COVERALLS_REPO_TOKEN" must be set.
     *
     * @param array $env $_SERVER environment.
     * @return \Contrib\Component\Service\Coveralls\Entity\V1\JsonFile
     */
    protected function fillRepoToken(array $env)
    {
        if (isset($env['COVERALLS_REPO_TOKEN'])) {
            $this->repoToken = $env['COVERALLS_REPO_TOKEN'];
        }

        return $this;
    }

    /**
     * Return whether the job requires "service_job_id" (for Travis CI).
     *
     * @return boolean
     */
    protected function requireServiceJobId()
    {
        return isset($this->serviceName) && isset($this->serviceJobId) && !isset($this->repoToken);
    }

    /**
     * Return whether the job requires "service_number" (for CircleCI, Jenkins, Codeship).
     *
     * @return boolean
     */
    protected function requireServiceNumber()
    {
        return isset($this->serviceName) && isset($this->serviceNumber) && isset($this->repoToken);
    }

    /**
     * Return whether the job requires "service_event_type" (for local environment).
     *
     * @return boolean
     */
    protected function requireServiceEventType()
    {
        return isset($this->serviceName) && isset($this->serviceEventType) && isset($this->repoToken);
    }

    /**
     * Return whether the job is running on unsupported service.
     *
     * @return boolean
     */
    protected function isUnsupportedServiceJob()
    {
        return !isset($this->serviceJobId) && !isset($this->serviceNumber) && !isset($this->serviceEventType) && isset($this->repoToken);
    }

    // accessor

    /**
     * Set service name.
     *
     * @param string $serviceName Service name.
     * @return Coveralls
     */
    public function setServiceName($serviceName)
    {
        $this->serviceName = $serviceName;

        return $this;
    }

    /**
     * Return service name.
     *
     * @return string
     */
    public function getServiceName()
    {
        if (isset($this->serviceName)) {
            return $this->serviceName;
        }

        return null;
    }

    /**
     * Set repository token.
     *
     * @param string $repoToken Repository token.
     * @return Coveralls
     */
    public function setRepoToken($repoToken)
    {
        $this->repoToken = $repoToken;

        return $this;
    }

    /**
     * Return repository token.
     *
     * @return string
     */
    public function getRepoToken()
    {
        if (isset($this->repoToken)) {
            return $this->repoToken;
        }

        return null;
    }

    /**
     * Return source files.
     *
     * @return SourceFile[]
     */
    public function getSourceFiles()
    {
        return $this->sourceFiles;
    }

    /**
     * Set service job id.
     *
     * @param string $serviceJobId Service job id.
     * @return Coveralls
     */
    public function setServiceJobId($serviceJobId)
    {
        $this->serviceJobId = $serviceJobId;

        return $this;
    }

    /**
     * Return service job id.
     *
     * @return string
     */
    public function getServiceJobId()
    {
        if (isset($this->serviceJobId)) {
            return $this->serviceJobId;
        }

        return null;
    }

    /**
     * Return service number.
     *
     * @return string
     */
    public function getServiceNumber()
    {
        return $this->serviceNumber;
    }

    /**
     * Return service event type.
     *
     * @return string
     */
    public function getServiceEventType()
    {
        return $this->serviceEventType;
    }

    /**
     * Set git data.
     *
     * @param array $git Git data.
     * @return Coveralls
     */
    public function setGit(Git $git)
    {
        $this->git = $git;

        return $this;
    }

    /**
     * Return git data.
     *
     * @return array
     */
    public function getGit()
    {
        if (isset($this->git)) {
            return $this->git;
        }

        return null;
    }

    /**
     * Set timestamp when the job ran.
     *
     * @param string $runAt Timestamp.
     * @return Coveralls
     */
    public function setRunAt($runAt)
    {
        $this->runAt = $runAt;

        return $this;
    }

    /**
     * Return timestamp when the job ran.
     *
     * @return string
     */
    public function getRunAt()
    {
        if (isset($this->runAt)) {
            return $this->runAt;
        }

        return null;
    }
}
