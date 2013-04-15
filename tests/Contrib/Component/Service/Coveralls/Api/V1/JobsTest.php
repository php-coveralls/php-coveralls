<?php
namespace Contrib\Component\Service\Coveralls\Api\V1;

use Contrib\Component\Http\HttpClient;
use Contrib\Component\Service\Coveralls\Collector\V1\CloverXmlCoverageCollector;

class JobsTest extends \PHPUnit_Framework_TestCase
{
    protected function setUp()
    {
        $this->dir      = realpath(__DIR__ . '/../../');
        $this->root     = $this->dir . '/Entity/V1/files/';
        $this->url      = 'https://coveralls.io/api/v1/jobs';
        $this->path     = __DIR__ . '/coveralls.json';
        $this->filename = 'json_file';

        if (class_exists('CurlFile')) {
            $this->post = array(
                $this->filename => new \CurlFile($this->path),
            );
        } else {
            $this->post = array(
                $this->filename => '@' . $this->path,
            );
        }
    }

    protected function tearDown()
    {
        if (file_exists($this->path)) {
            unlink($this->path);
        }
    }

    protected function createJobsWith()
    {
        $this->adapter = $this->createAdapterMockWith($this->url, $this->post);
        $this->client = new HttpClient($this->adapter);

        return new Jobs($this->client);
    }

    protected function createJobsNeverSend()
    {
        $this->adapter = $this->createAdapterMockNeverCalled();
        $this->client = new HttpClient($this->adapter);

        return new Jobs($this->client);
    }

    protected function createAdapterMockNeverCalled()
    {
        $adapter = $this->getMock('Contrib\Component\Http\Adapter\CurlAdapter', array('send'));

        $adapter
        ->expects($this->never())
        ->method('send');

        return $adapter;
    }

    protected function createAdapterMockWith($url, $post)
    {
        $adapter = $this->getMock('Contrib\Component\Http\Adapter\CurlAdapter', array('send'));

        // expected parameters
        $params = array(
            CURLOPT_URL            => $url,
            CURLOPT_POST           => true,
            CURLOPT_POSTFIELDS     => $post,
            CURLOPT_RETURNTRANSFER => true,
            CURLOPT_FOLLOWLOCATION => true,
        );

        $adapter
        ->expects($this->once())
        ->method('send')
        ->with($this->equalTo($params));

        return $adapter;
    }

    protected function createCloverXml()
    {
        $xml = <<<XML
<?xml version="1.0" encoding="UTF-8"?>
<coverage generated="1365848893">
  <project timestamp="1365848893">
    <file name="%s/Entity/V1/files/test.php">
      <class name="TestFile" namespace="global">
        <metrics methods="1" coveredmethods="0" conditionals="0" coveredconditionals="0" statements="1" coveredstatements="0" elements="2" coveredelements="0"/>
      </class>
      <line num="5" type="method" name="__construct" crap="1" count="0"/>
      <line num="7" type="stmt" count="0"/>
    </file>
    <file name="dummy.php">
      <class name="TestFile" namespace="global">
        <metrics methods="1" coveredmethods="0" conditionals="0" coveredconditionals="0" statements="1" coveredstatements="0" elements="2" coveredelements="0"/>
      </class>
      <line num="5" type="method" name="__construct" crap="1" count="0"/>
      <line num="7" type="stmt" count="0"/>
    </file>
    <package name="Hoge">
      <file name="%s/Entity/V1/files/test2.php">
        <class name="TestFile" namespace="Hoge">
          <metrics methods="1" coveredmethods="0" conditionals="0" coveredconditionals="0" statements="1" coveredstatements="0" elements="2" coveredelements="0"/>
        </class>
        <line num="6" type="method" name="__construct" crap="1" count="0"/>
        <line num="8" type="stmt" count="0"/>
      </file>
    </package>
  </project>
</coverage>
XML;

        return simplexml_load_string(sprintf($xml, $this->dir, $this->dir));
    }

    protected function createNoSourceCloverXml()
    {
        $xml = <<<XML
<?xml version="1.0" encoding="UTF-8"?>
<coverage generated="1365848893">
  <project timestamp="1365848893">
    <file name="dummy.php">
      <class name="TestFile" namespace="global">
        <metrics methods="1" coveredmethods="0" conditionals="0" coveredconditionals="0" statements="1" coveredstatements="0" elements="2" coveredelements="0"/>
      </class>
      <line num="5" type="method" name="__construct" crap="1" count="0"/>
      <line num="7" type="stmt" count="0"/>
    </file>
  </project>
</coverage>
XML;

        return simplexml_load_string($xml);
    }

    protected function collectJsonFile()
    {
        $xml       = $this->createCloverXml();
        $collector = new CloverXmlCoverageCollector();

        return $collector->collect($xml, $this->root);
    }

    protected function collectJsonFileWithoutSourceFiles()
    {
        $xml       = $this->createNoSourceCloverXml();
        $collector = new CloverXmlCoverageCollector();

        return $collector->collect($xml, $this->root);
    }

    // send()

    /**
     * @test
     */
    public function sendTravisCiJob()
    {
        $this->object = $this->createJobsWith();

        unset($_SERVER['COVERALLS_REPO_TOKEN']);
        unset($_SERVER['GIT_COMMIT']);
        unset($_SERVER['CIRCLECI']);
        unset($_SERVER['CIRCLE_BUILD_NUM']);
        unset($_SERVER['JENKINS_URL']);
        unset($_SERVER['BUILD_NUMBER']);
        unset($_SERVER['COVERALLS_RUN_LOCALLY']);
        $_SERVER['TRAVIS'] = true;
        $_SERVER['TRAVIS_JOB_ID'] = '1.1';

        $jsonFile = $this->collectJsonFile();

        $this->object->send($jsonFile, $this->path);
    }

    /**
     * @test
     */
    public function sendTravisProJob()
    {
        $this->object = $this->createJobsWith();

        unset($_SERVER['COVERALLS_REPO_TOKEN']);
        unset($_SERVER['GIT_COMMIT']);
        unset($_SERVER['CIRCLECI']);
        unset($_SERVER['CIRCLE_BUILD_NUM']);
        unset($_SERVER['JENKINS_URL']);
        unset($_SERVER['BUILD_NUMBER']);
        unset($_SERVER['COVERALLS_RUN_LOCALLY']);
        $_SERVER['TRAVIS'] = true;
        $_SERVER['TRAVIS_JOB_ID'] = '1.1';

        $jsonFile = $this->collectJsonFile()
        ->setServiceName('travis-pro');

        $this->object->send($jsonFile, $this->path);
    }

    /**
     * @test
     */
    public function sendCircleCiJob()
    {
        $this->object = $this->createJobsWith();

        unset($_SERVER['COVERALLS_REPO_TOKEN']);
        unset($_SERVER['GIT_COMMIT']);
        unset($_SERVER['TRAVIS']);
        unset($_SERVER['TRAVIS_JOB_ID']);
        unset($_SERVER['JENKINS_URL']);
        unset($_SERVER['BUILD_NUMBER']);
        unset($_SERVER['COVERALLS_RUN_LOCALLY']);
        $_SERVER['CIRCLECI'] = true;
        $_SERVER['CIRCLE_BUILD_NUM'] = '123';

        $jsonFile = $this->collectJsonFile();

        $this->object->send($jsonFile, $this->path);
    }

    /**
     * @test
     */
    public function sendJenkinsJob()
    {
        $this->object = $this->createJobsWith();

        unset($_SERVER['COVERALLS_REPO_TOKEN']);
        unset($_SERVER['GIT_COMMIT']);
        unset($_SERVER['TRAVIS']);
        unset($_SERVER['TRAVIS_JOB_ID']);
        unset($_SERVER['CIRCLECI']);
        unset($_SERVER['CIRCLE_BUILD_NUM']);
        unset($_SERVER['COVERALLS_RUN_LOCALLY']);
        $_SERVER['JENKINS_URL'] = 'http://localhost:8080';
        $_SERVER['BUILD_NUMBER'] = '123';

        $jsonFile = $this->collectJsonFile();

        $this->object->send($jsonFile, $this->path);
    }

    /**
     * @test
     */
    public function sendLocalJob()
    {
        $this->object = $this->createJobsWith();

        unset($_SERVER['COVERALLS_REPO_TOKEN']);
        unset($_SERVER['GIT_COMMIT']);
        unset($_SERVER['TRAVIS']);
        unset($_SERVER['TRAVIS_JOB_ID']);
        unset($_SERVER['CIRCLECI']);
        unset($_SERVER['CIRCLE_BUILD_NUM']);
        unset($_SERVER['JENKINS_URL']);
        unset($_SERVER['BUILD_NUMBER']);
        $_SERVER['COVERALLS_RUN_LOCALLY'] = true;

        $jsonFile = $this->collectJsonFile();

        $this->object->send($jsonFile, $this->path);
    }

    /**
     * @test
     */
    public function sendUnsupportedJob()
    {
        $this->object = $this->createJobsWith();

        unset($_SERVER['TRAVIS']);
        unset($_SERVER['TRAVIS_JOB_ID']);
        unset($_SERVER['GIT_COMMIT']);
        unset($_SERVER['CIRCLECI']);
        unset($_SERVER['CIRCLE_BUILD_NUM']);
        unset($_SERVER['JENKINS_URL']);
        unset($_SERVER['BUILD_NUMBER']);
        unset($_SERVER['COVERALLS_RUN_LOCALLY']);
        $_SERVER['COVERALLS_REPO_TOKEN'] = 'token';

        $jsonFile = $this->collectJsonFile();

        $this->object->send($jsonFile, $this->path);
    }

    /**
     * @test
     */
    public function sendUnsupportedGitJob()
    {
        $this->object = $this->createJobsWith();

        unset($_SERVER['TRAVIS']);
        unset($_SERVER['TRAVIS_JOB_ID']);
        unset($_SERVER['CIRCLECI']);
        unset($_SERVER['CIRCLE_BUILD_NUM']);
        unset($_SERVER['JENKINS_URL']);
        unset($_SERVER['BUILD_NUMBER']);
        unset($_SERVER['COVERALLS_RUN_LOCALLY']);
        $_SERVER['COVERALLS_REPO_TOKEN'] = 'token';
        $_SERVER['GIT_COMMIT'] = 'abc123';

        $jsonFile = $this->collectJsonFile();

        $this->object->send($jsonFile, $this->path);
    }

    /**
     * @test
     * @expectedException RuntimeException
     */
    public function throwRuntimeExceptionIfInvalidEnv()
    {
        $this->object = $this->createJobsNeverSend();

        unset($_SERVER['COVERALLS_REPO_TOKEN']);
        unset($_SERVER['TRAVIS']);
        unset($_SERVER['TRAVIS_JOB_ID']);
        unset($_SERVER['CIRCLECI']);
        unset($_SERVER['CIRCLE_BUILD_NUM']);
        unset($_SERVER['JENKINS_URL']);
        unset($_SERVER['BUILD_NUMBER']);
        unset($_SERVER['GIT_COMMIT']);
        unset($_SERVER['COVERALLS_RUN_LOCALLY']);

        $jsonFile = $this->collectJsonFile();

        $this->object->send($jsonFile, $this->path);
    }

    /**
     * @test
     * @expectedException RuntimeException
     */
    public function throwRuntimeExceptionIfNoSourceFiles()
    {
        $this->object = $this->createJobsNeverSend();

        $_SERVER['TRAVIS'] = true;
        $_SERVER['TRAVIS_JOB_ID'] = '1.1';
        $_SERVER['COVERALLS_REPO_TOKEN'] = 'token';
        $_SERVER['GIT_COMMIT'] = 'abc123';

        $jsonFile = $this->collectJsonFileWithoutSourceFiles();

        $this->object->send($jsonFile, $this->path);
    }
}
