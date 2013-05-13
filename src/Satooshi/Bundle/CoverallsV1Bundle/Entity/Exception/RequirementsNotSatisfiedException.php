<?php
namespace Satooshi\Bundle\CoverallsV1Bundle\Entity\Exception;

/**
 * Requirements of json_file are not satisfied.
 *
 * @author Kitamura Satoshi <with.no.parachute@gmail.com>
 */
class RequirementsNotSatisfiedException extends \RuntimeException
{
    /**
     * Error message.
     *
     * @var string
     */
    protected $message = 'Requirements are not satisfied.';

    /**
     * Read environment variables.
     *
     * @var array
     */
    protected $readEnv;

    /**
     * Return help message.
     *
     * @return string
     */
    public function getHelpMessage()
    {
        $message = $this->message . "\n";

        if (isset($this->readEnv) && is_array($this->readEnv)) {
            foreach ($this->readEnv as $envVarName => $value) {
                $message .= sprintf("  - %s=%s\n", $envVarName, var_export($value, true));
            }
        }

        $message .= <<< EOL

Set environment variables properly like the following.
For Travis users:

  - TRAVIS
  - TRAVIS_JOB_ID

For CircleCI users:

  - CIRCLECI
  - CIRCLE_BUILD_NUM
  - COVERALLS_REPO_TOKEN

For Jenkins users:

  - JENKINS_URL
  - BUILD_NUMBER
  - COVERALLS_REPO_TOKEN

From local environment:

  - COVERALLS_RUN_LOCALLY
  - COVERALLS_REPO_TOKEN

EOL;

        return $message;
    }

    /**
     * Set read environment variables.
     *
     * @param array $readEnv Read environment variables.
     *
     * @return void
     */
    public function setReadEnv(array $readEnv)
    {
        $this->readEnv = $readEnv;
    }

    /**
     * Return read environment variables.
     *
     * @return array
     */
    public function getReadEnv()
    {
        return $this->readEnv;
    }
}
