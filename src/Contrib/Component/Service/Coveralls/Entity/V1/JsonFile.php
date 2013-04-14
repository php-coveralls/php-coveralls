<?php
namespace Contrib\Component\Service\Coveralls\Entity\V1;

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
     * @var string.
     */
    protected $runAt;

    // API

    /**
     * {@inheritdoc}
     *
     * @see \Contrib\Component\Service\Coveralls\Entity\V1\Coveralls::toArray()
     */
    public function toArray()
    {
        $files = array();

        foreach ($this->sourceFiles as $file) {
            $files[] = $file->toArray();
        }

        $data = array(
            'source_files' => $files,
        );

        if (isset($this->serviceName)) {
            $data['service_name'] = $this->serviceName;
        }
        if (isset($this->serviceJobId)) {
            $data['service_job_id'] = $this->serviceJobId;
        }
        if (isset($this->repoToken)) {
            $data['repo_token'] = $this->repoToken;
        }
        if (isset($this->git)) {
            $data['git'] = $this->git;
        }
        if (isset($this->runAt)) {
            $data['run_at'] = $this->runAt;
        }

        return $data;
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

    public function fillJobs(array $env)
    {
        return $this
        ->fillTravis($env)
        ->fillRepoToken($env)
        ->fillGit($env)
        ->ensureJobs();
    }

    // internal method

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

        if (isset($this->serviceName) && isset($this->serviceJobId)) {
            return $this;
        }

        if (isset($this->repoToken)) {
            return $this;
        }

        $message = 'service_name and service_job_id are required for supported service, or repo_token is required for unsupported service';

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
    protected function fillTravis(array $env)
    {
        if (isset($env['TRAVIS_JOB_ID'])) {
            $this->setServiceJobId($env['TRAVIS_JOB_ID']);

            if (!isset($this->serviceName)) {
                $this->setServiceName('travis-ci');
            }
        }

        return $this;
    }

    /**
     * Fill repo_token for unsupported CI service.
     *
     * "COVERALLS_TOKEN" must be set.
     *
     * @param array $env $_SERVER environment.
     * @return \Contrib\Component\Service\Coveralls\Entity\V1\JsonFile
     */
    protected function fillRepoToken(array $env)
    {
        if (isset($env['COVERALLS_TOKEN'])) {
            return $this->setRepoToken($env['COVERALLS_TOKEN']);
        }

        return $this;
    }

    /**
     * Fill git repository info.
     *
     * @param array $env $_SERVER environment.
     * @return \Contrib\Component\Service\Coveralls\Entity\V1\JsonFile
     */
    protected function fillGit(array $env)
    {
        if (isset($this->repoToken) && isset($env['GIT_COMMIT'])) {
            //TODO fill git repository info
            return $this;
        }

        // git info is not required for supported CI service
        return $this;
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
     * @return string.
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
     * @return string.
     */
    public function getServiceJobId()
    {
        if (isset($this->serviceJobId)) {
            return $this->serviceJobId;
        }

        return null;
    }

    /**
     * Set git data.
     *
     * @param array $git Git data.
     * @return Coveralls
     */
    public function setGit(array $git)
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
