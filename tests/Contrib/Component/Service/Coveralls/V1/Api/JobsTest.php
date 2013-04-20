<?php
namespace Contrib\Component\Service\Coveralls\V1\Api;

use Contrib\Component\Service\Coveralls\V1\Entity\JsonFile;

use Contrib\Component\Http\Adapter\CurlAdapter;

use Contrib\Component\Service\Coveralls\V1\Config\Configuration;

use Contrib\Component\Http\HttpClient;
use Contrib\Component\Service\Coveralls\V1\Collector\CloverXmlCoverageCollector;

/**
 * @covers Contrib\Component\Service\Coveralls\V1\Api\Jobs
 * @covers Contrib\Component\Service\Coveralls\V1\Api\CoverallsApi
 *
 * @author Kitamura Satoshi <with.no.parachute@gmail.com>
 */
class JobsTest extends \PHPUnit_Framework_TestCase
{
    protected function setUp()
    {
        $this->dir           = realpath(__DIR__ . '/../../../../../../');
        $this->rootDir       = realpath($this->dir . '/prj/files');
        $this->srcDir        = $this->rootDir;
        $this->url           = 'https://coveralls.io/api/v1/jobs';
        $this->jsonPath      = __DIR__ . '/coveralls.json';
        $this->filename      = 'json_file';
        $this->cloverXmlPath = $this->rootDir . 'clover.xml';

        $this->post = array(
            $this->filename => class_exists('CurlFile') ? new \CurlFile($this->jsonPath) : '@' . $this->jsonPath,
        );
    }

    protected function tearDown()
    {
        $this->rmFile($this->jsonPath);
        $this->rmFile($this->cloverXmlPath);
    }

    protected function rmFile($file)
    {
        if (is_file($file)) {
            unlink($file);
        }
    }

    protected function createJobsWith()
    {
        $this->config = new Configuration($this->rootDir);

        $this->config
        ->setJsonPath($this->jsonPath)
        ->setDryRun(false);

        $this->adapter = $this->createAdapterMockWith($this->url, $this->post);
        $this->client = new HttpClient($this->adapter);

        return new Jobs($this->config, $this->client);
    }

    protected function createJobsNeverSend()
    {
        $this->config = new Configuration($this->rootDir);
        $this->config
        ->setJsonPath($this->jsonPath)
        ->setDryRun(false);

        $this->adapter = $this->createAdapterMockNeverCalled();
        $this->client = new HttpClient($this->adapter);

        return new Jobs($this->config, $this->client);
    }

    protected function createJobsNeverSendOnDryRun()
    {
        $this->config = new Configuration($this->rootDir);
        $this->config
        ->setJsonPath($this->jsonPath)
        ->setDryRun(true);

        $this->adapter = $this->createAdapterMockNeverCalled();
        $this->client = new HttpClient($this->adapter);

        return new Jobs($this->config, $this->client);
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

    protected function createConfiguration()
    {
        $config = new Configuration($this->rootDir);

        return $config
        ->setSrcDir($this->srcDir)
        ->setCloverXmlPath($this->cloverXmlPath);
    }

    protected function getCloverXml()
    {
        $xml = <<<XML
<?xml version="1.0" encoding="UTF-8"?>
<coverage generated="1365848893">
  <project timestamp="1365848893">
    <file name="%s/test.php">
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
      <file name="%s/test2.php">
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
        return sprintf($xml, $this->rootDir, $this->rootDir);
    }

    protected function createCloverXml()
    {
        $xml = $this->getCloverXml();

        return simplexml_load_string($xml);
    }

    protected function getNoSourceCloverXml()
    {
        return <<<XML
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
    }

    protected function createNoSourceCloverXml()
    {
        $xml = $this->getNoSourceCloverXml();

        return simplexml_load_string($xml);
    }

    protected function collectJsonFile()
    {
        $xml       = $this->createCloverXml();
        $collector = new CloverXmlCoverageCollector();

        return $collector->collect($xml, $this->rootDir);
    }

    protected function collectJsonFileWithoutSourceFiles()
    {
        $xml       = $this->createNoSourceCloverXml();
        $collector = new CloverXmlCoverageCollector();

        return $collector->collect($xml, $this->rootDir);
    }

    // getJsonFile()

    /**
     * @test
     */
    public function shouldNotHaveJsonFileOnConstruction()
    {
        $object = $this->createJobsNeverSendOnDryRun();

        $this->assertNull($object->getJsonFile());
    }

    // setJsonFile()

    /**
     * @test
     */
    public function setJsonFile()
    {
        $jsonFile = $this->collectJsonFile();

        $object = $this->createJobsNeverSendOnDryRun()->setJsonFile($jsonFile);

        $this->assertSame($jsonFile, $object->getJsonFile());
    }

    // getConfiguration()

    /**
     * @test
     */
    public function getConfiguration()
    {
        $config = $this->createConfiguration();

        $object = new Jobs($config);

        $this->assertSame($config, $object->getConfiguration());
    }

    // getHttpClient()

    /**
     * @test
     */
    public function shouldNotHaveHttpClientOnConstructionWithoutHttpClient()
    {
        $config = $this->createConfiguration();

        $object = new Jobs($config);

        $this->assertNull($object->getHttpClient());
    }

    /**
     * @test
     */
    public function shouldHaveHttpClientOnConstructionWithHttpClient()
    {
        $config = $this->createConfiguration();
        $client = new HttpClient(new CurlAdapter());

        $object = new Jobs($config, $client);

        $this->assertSame($client, $object->getHttpClient());
    }

    // setHttpClient()

    /**
     * @test
     */
    public function setHttpClient()
    {
        $config = $this->createConfiguration();
        $client = new HttpClient(new CurlAdapter());

        $object = new Jobs($config);
        $object->setHttpClient($client);

        $this->assertSame($client, $object->getHttpClient());
    }

    // collectCloverXml()

    /**
     * @test
     */
    public function collectCloverXml()
    {
        $xml = $this->getCloverXml();

        file_put_contents($this->cloverXmlPath, $xml);

        $config = $this->createConfiguration();

        $object = new Jobs($config);

        $same = $object->collectCloverXml();

        // return $this
        $this->assertSame($same, $object);

        return $object;
    }

    /**
     * @test
     * @depends collectCloverXml
     */
    public function shouldHaveJsonFileAfterCollectCloverXml(Jobs $object)
    {
        $jsonFile = $object->getJsonFile();

        $this->assertNotNull($jsonFile);

        return $jsonFile;
    }

    /**
     * @test
     * @depends shouldHaveJsonFileAfterCollectCloverXml
     */
    public function shouldNotHaveGitAfterCollectCloverXml(JsonFile $jsonFile)
    {
        $git = $jsonFile->getGit();

        $this->assertNull($git);
    }

    // collectGitInfo()

    /**
     * @test
     * @depends collectCloverXml
     */
    public function collectGitInfo(Jobs $object)
    {
        $same = $object->collectGitInfo();

        // return $this
        $this->assertSame($same, $object);

        return $object;
    }

    /**
     * @test
     * @depends collectGitInfo
     */
    public function shouldHaveJsonFileAfterCollectGitInfo(Jobs $object)
    {
        $jsonFile = $object->getJsonFile();

        $this->assertNotNull($jsonFile);

        return $jsonFile;
    }

    /**
     * @test
     * @depends shouldHaveJsonFileAfterCollectGitInfo
     */
    public function shouldHaveGitAfterCollectGitInfo(JsonFile $jsonFile)
    {
        $git = $jsonFile->getGit();

        $this->assertNotNull($git);
    }

    // send()

    /**
     * @test
     */
    public function sendTravisCiJob()
    {
        $serviceName  = 'travis-ci';
        $serviceJobId = '1.1';

        $object = $this->createJobsWith();

        unset($_SERVER['COVERALLS_REPO_TOKEN']);
        unset($_SERVER['GIT_COMMIT']);
        unset($_SERVER['CIRCLECI']);
        unset($_SERVER['CIRCLE_BUILD_NUM']);
        unset($_SERVER['JENKINS_URL']);
        unset($_SERVER['BUILD_NUMBER']);
        unset($_SERVER['COVERALLS_RUN_LOCALLY']);
        $_SERVER['TRAVIS']        = true;
        $_SERVER['TRAVIS_JOB_ID'] = $serviceJobId;

        $jsonFile = $this->collectJsonFile();

        $object->setJsonFile($jsonFile);
        $object->send();

        $this->assertEquals($serviceName, $jsonFile->getServiceName());
        $this->assertEquals($serviceJobId, $jsonFile->getServiceJobId());
    }

    /**
     * @test
     */
    public function sendTravisProJob()
    {
        $serviceName  = 'travis-pro';
        $serviceJobId = '1.1';

        $object = $this->createJobsWith();
        $object->getConfiguration()->setServiceName($serviceName);

        unset($_SERVER['COVERALLS_REPO_TOKEN']);
        unset($_SERVER['GIT_COMMIT']);
        unset($_SERVER['CIRCLECI']);
        unset($_SERVER['CIRCLE_BUILD_NUM']);
        unset($_SERVER['JENKINS_URL']);
        unset($_SERVER['BUILD_NUMBER']);
        unset($_SERVER['COVERALLS_RUN_LOCALLY']);
        $_SERVER['TRAVIS']        = true;
        $_SERVER['TRAVIS_JOB_ID'] = $serviceJobId;

        $jsonFile = $this->collectJsonFile();

        $object->setJsonFile($jsonFile);
        $object->send();

        $this->assertEquals($serviceName, $jsonFile->getServiceName());
        $this->assertEquals($serviceJobId, $jsonFile->getServiceJobId());
    }

    /**
     * @test
     */
    public function sendCircleCiJob()
    {
        $serviceName   = 'circleci';
        $serviceNumber = '123';

        $object = $this->createJobsWith();

        unset($_SERVER['COVERALLS_REPO_TOKEN']);
        unset($_SERVER['GIT_COMMIT']);
        unset($_SERVER['TRAVIS']);
        unset($_SERVER['TRAVIS_JOB_ID']);
        unset($_SERVER['JENKINS_URL']);
        unset($_SERVER['BUILD_NUMBER']);
        unset($_SERVER['COVERALLS_RUN_LOCALLY']);
        $_SERVER['CIRCLECI']         = true;
        $_SERVER['CIRCLE_BUILD_NUM'] = $serviceNumber;

        $jsonFile = $this->collectJsonFile();

        $object->setJsonFile($jsonFile);
        $object->send();

        $this->assertEquals($serviceName, $jsonFile->getServiceName());
        $this->assertEquals($serviceNumber, $jsonFile->getServiceNumber());
    }

    /**
     * @test
     */
    public function sendJenkinsJob()
    {
        $serviceName   = 'jenkins';
        $serviceNumber = '123';

        $object = $this->createJobsWith();

        unset($_SERVER['COVERALLS_REPO_TOKEN']);
        unset($_SERVER['GIT_COMMIT']);
        unset($_SERVER['TRAVIS']);
        unset($_SERVER['TRAVIS_JOB_ID']);
        unset($_SERVER['CIRCLECI']);
        unset($_SERVER['CIRCLE_BUILD_NUM']);
        unset($_SERVER['COVERALLS_RUN_LOCALLY']);
        $_SERVER['JENKINS_URL']  = 'http://localhost:8080';
        $_SERVER['BUILD_NUMBER'] = $serviceNumber;

        $jsonFile = $this->collectJsonFile();

        $object->setJsonFile($jsonFile);
        $object->send();

        $this->assertEquals($serviceName, $jsonFile->getServiceName());
        $this->assertEquals($serviceNumber, $jsonFile->getServiceNumber());
    }

    /**
     * @test
     */
    public function sendLocalJob()
    {
        $serviceName      = 'php-coveralls';
        $serviceEventType = 'manual';

        $object = $this->createJobsWith();
        $object->getConfiguration()->setRepoToken('token');

        unset($_SERVER['COVERALLS_REPO_TOKEN']);
        unset($_SERVER['GIT_COMMIT']);
        unset($_SERVER['TRAVIS']);
        unset($_SERVER['TRAVIS_JOB_ID']);
        unset($_SERVER['CIRCLECI']);
        unset($_SERVER['CIRCLE_BUILD_NUM']);
        unset($_SERVER['JENKINS_URL']);
        unset($_SERVER['BUILD_NUMBER']);
        $_SERVER['COVERALLS_RUN_LOCALLY'] = '1';

        $jsonFile = $this->collectJsonFile();

        $object->setJsonFile($jsonFile);
        $object->send();

        $this->assertNull($jsonFile->getServiceJobId());
        $this->assertEquals($serviceName, $jsonFile->getServiceName());
        $this->assertEquals($serviceEventType, $jsonFile->getServiceEventType());
    }

    /**
     * @test
     */
    public function sendUnsupportedJob()
    {
        $object = $this->createJobsWith();

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

        $object->setJsonFile($jsonFile);
        $object->send();
    }

    /**
     * @test
     */
    public function sendUnsupportedGitJob()
    {
        $object = $this->createJobsWith();

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

        $object->setJsonFile($jsonFile);
        $object->send();
    }

    /**
     * @test
     */
    public function dryRun()
    {
        $object = $this->createJobsNeverSendOnDryRun();

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

        $object->setJsonFile($jsonFile);
        $object->send();
    }

    /**
     * @test
     * @expectedException RuntimeException
     */
    public function throwRuntimeExceptionIfInvalidEnv()
    {
        $object = $this->createJobsNeverSend();

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

        $object->setJsonFile($jsonFile);
        $object->send();
    }

    /**
     * @test
     * @expectedException RuntimeException
     */
    public function throwRuntimeExceptionIfNoSourceFiles()
    {
        $object = $this->createJobsNeverSend();

        $_SERVER['TRAVIS'] = true;
        $_SERVER['TRAVIS_JOB_ID'] = '1.1';
        $_SERVER['COVERALLS_REPO_TOKEN'] = 'token';
        $_SERVER['GIT_COMMIT'] = 'abc123';

        $jsonFile = $this->collectJsonFileWithoutSourceFiles();

        $object->setJsonFile($jsonFile);
        $object->send();
    }
}
