<?php
namespace Contrib\Component\Service\Coveralls\V1\Entity;

use Contrib\Component\Service\Coveralls\V1\Entity\Git\Remote;
use Contrib\Component\Service\Coveralls\V1\Entity\Git\Commit;
use Contrib\Component\Service\Coveralls\V1\Entity\Git\Git;
use Contrib\Component\Service\Coveralls\V1\Collector\CloverXmlCoverageCollector;

/**
 * @covers Contrib\Component\Service\Coveralls\V1\Entity\JsonFile
 * @covers Contrib\Component\Service\Coveralls\V1\Entity\Coveralls
 *
 * @author Kitamura Satoshi <with.no.parachute@gmail.com>
 */
class JsonFileTest extends \PHPUnit_Framework_TestCase
{
    protected function setUp()
    {
        $this->dir      = realpath(__DIR__ . '/../../../../../../');
        $this->rootDir  = realpath($this->dir . '/prj/files');
        $this->filename = 'test.php';
        $this->path     = $this->rootDir . DIRECTORY_SEPARATOR . $this->filename;

        $this->object = new JsonFile();
    }


    protected function createSourceFile()
    {
        return new SourceFile($this->path, $this->filename);
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

    protected function collectJsonFile()
    {
        $xml       = $this->createCloverXml();
        $collector = new CloverXmlCoverageCollector();

        return $collector->collect($xml, $this->rootDir);
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

    protected function collectJsonFileWithoutSourceFiles()
    {
        $xml       = $this->createNoSourceCloverXml();
        $collector = new CloverXmlCoverageCollector();

        return $collector->collect($xml, $this->rootDir);
    }


    // getServiceName()

    /**
     * @test
     */
    public function shouldNotHaveServiceNameOnConstruction()
    {
        $this->assertNull($this->object->getServiceName());
    }

    // getRepoToken()

    /**
     * @test
     */
    public function shouldNotHaveRepoTokenOnConstruction()
    {
        $this->assertNull($this->object->getRepoToken());
    }

    // getSourceFiles()

    /**
     * @test
     */
    public function shouldHaveEmptySourceFilesOnConstruction()
    {
        $this->assertEmpty($this->object->getSourceFiles());
    }

    // getServiceJobId()

    /**
     * @test
     */
    public function shouldNotHaveServiceJobIdOnConstruction()
    {
        $this->assertNull($this->object->getServiceJobId());
    }

    // getServiceNumber()

    /**
     * @test
     */
    public function shouldNotHaveServiceNumberOnConstruction()
    {
        $this->assertNull($this->object->getServiceNumber());
    }

    // getServiceEventType()

    /**
     * @test
     */
    public function shouldNotHaveServiceEventTypeOnConstruction()
    {
        $this->assertNull($this->object->getServiceEventType());
    }

    // getGit()

    /**
     * @test
     */
    public function shouldNotHaveGitOnConstruction()
    {
        $this->assertNull($this->object->getGit());
    }

    // getRunAt()

    /**
     * @test
     */
    public function shouldNotHaveRunAtOnConstruction()
    {
        $this->assertNull($this->object->getRunAt());
    }



    // setServiceName()

    /**
     * @test
     */
    public function setServiceName()
    {
        $expected = 'travis-ci';

        $obj = $this->object->setServiceName($expected);

        $this->assertEquals($expected, $this->object->getServiceName());
        $this->assertSame($obj, $this->object);

        return $this->object;
    }

    // setRepoToken()

    /**
     * @test
     */
    public function setRepoToken()
    {
        $expected = 'token';

        $obj = $this->object->setRepoToken($expected);

        $this->assertEquals($expected, $this->object->getRepoToken());
        $this->assertSame($obj, $this->object);

        return $this->object;
    }

    // setServiceJobId()

    /**
     * @test
     */
    public function setServiceJobId()
    {
        $expected = 'job_id';

        $obj = $this->object->setServiceJobId($expected);

        $this->assertEquals($expected, $this->object->getServiceJobId());
        $this->assertSame($obj, $this->object);

        return $this->object;
    }

    //TODO refactor to user Git object
    // setGit()

    /**
     * @test
     */
    public function setGit()
    {
        $remotes = array(new Remote());
        $head    = new Commit();
        $git     = new Git('master', $head, $remotes);

        $obj = $this->object->setGit($git);

        $this->assertSame($git, $this->object->getGit());
        $this->assertSame($obj, $this->object);

        return $this->object;
    }

    // setRunAt()

    /**
     * @test
     */
    public function setRunAt()
    {
        $expected = '2013-04-04 11:22:33 +0900';

        $obj = $this->object->setRunAt($expected);

        $this->assertEquals($expected, $this->object->getRunAt());
        $this->assertSame($obj, $this->object);

        return $this->object;
    }



    // addSourceFile()

    /**
     * @test
     */
    public function addSourceFile()
    {
        $sourceFile = $this->createSourceFile();

        $this->object->addSourceFile($sourceFile);

        $this->assertEquals(1, $this->object->hasSourceFiles());
        $this->assertSame(array($sourceFile), $this->object->getSourceFiles());
    }

    // hasSourceFiles()

    /**
     * @test
     */
    public function countZeroSourceFilesOnConstruction()
    {
        $this->assertEquals(0, $this->object->hasSourceFiles());
    }

    // toArray()

    /**
     * @test
     */
    public function toArray()
    {
        $expected = array(
            'source_files' => array(),
        );

        $this->assertEquals($expected, $this->object->toArray());
        $this->assertEquals(json_encode($expected), (string)$this->object);
    }

    /**
     * @test
     */
    public function toArrayWithSourceFiles()
    {
        $sourceFile = $this->createSourceFile();

        $this->object->addSourceFile($sourceFile);

        $expected = array(
            'source_files' => array($sourceFile->toArray()),
        );

        $this->assertEquals($expected, $this->object->toArray());
        $this->assertEquals(json_encode($expected), (string)$this->object);
    }

    // service_name

    /**
     * @test
     * @depends setServiceName
     */
    public function toArrayWithServiceName($object)
    {
        $item = 'travis-ci';

        $expected = array(
            'service_name' => $item,
            'source_files' => array(),
        );

        $this->assertEquals($expected, $object->toArray());
        $this->assertEquals(json_encode($expected), (string)$object);
    }

    // service_job_id

    /**
     * @test
     * @depends setServiceJobId
     */
    public function toArrayWithServiceJobId($object)
    {
        $item = 'job_id';

        $expected = array(
            'service_job_id' => $item,
            'source_files'   => array(),
        );

        $this->assertEquals($expected, $object->toArray());
        $this->assertEquals(json_encode($expected), (string)$object);
    }

    // repo_token

    /**
     * @test
     * @depends setRepoToken
     */
    public function toArrayWithRepoToken($object)
    {
        $item = 'token';

        $expected = array(
            'repo_token'   => $item,
            'source_files' => array(),
        );

        $this->assertEquals($expected, $object->toArray());
        $this->assertEquals(json_encode($expected), (string)$object);
    }

    // git

    /**
     * @test
     * @depends setGit
     */
    public function toArrayWithGit($object)
    {
        $remotes = array(new Remote());
        $head    = new Commit();
        $git     = new Git('master', $head, $remotes);

        $expected = array(
            'git'          => $git->toArray(),
            'source_files' => array(),
        );

        $this->assertSame($expected, $object->toArray());
        $this->assertEquals(json_encode($expected), (string)$object);
    }

    // run_at

    /**
     * @test
     * @depends setRunAt
     */
    public function toArrayWithRunAt($object)
    {
        $item = '2013-04-04 11:22:33 +0900';

        $expected = array(
            'run_at'       => $item,
            'source_files' => array(),
        );

        $this->assertEquals($expected, $object->toArray());
        $this->assertEquals(json_encode($expected), (string)$object);
    }


    // fillJobs()

    /**
     * @test
     */
    public function sendTravisCiJob()
    {
        $serviceName  = 'travis-ci';
        $serviceJobId = '1.1';

        $env = array();
        $env['TRAVIS']        = true;
        $env['TRAVIS_JOB_ID'] = $serviceJobId;

        $object = $this->collectJsonFile();

        $same = $object->fillJobs($env);

        $this->assertSame($same, $object);
        $this->assertEquals($serviceName, $object->getServiceName());
        $this->assertEquals($serviceJobId, $object->getServiceJobId());
    }

    /**
     * @test
     */
    public function sendTravisProJob()
    {
        $serviceName  = 'travis-pro';
        $serviceJobId = '1.2';

        $env = array();
        $env['TRAVIS']        = true;
        $env['TRAVIS_JOB_ID'] = $serviceJobId;

        $object = $this
        ->collectJsonFile()
        ->setServiceName($serviceName);

        $same = $object->fillJobs($env);

        $this->assertSame($same, $object);
        $this->assertEquals($serviceName, $object->getServiceName());
        $this->assertEquals($serviceJobId, $object->getServiceJobId());
    }

    /**
     * @test
     */
    public function sendCircleCiJob()
    {
        $serviceName   = 'circleci';
        $serviceNumber = '123';

        $env = array();
        $env['CIRCLECI']         = true;
        $env['CIRCLE_BUILD_NUM'] = $serviceNumber;

        $object = $this->collectJsonFile();

        $same = $object->fillJobs($env);

        $this->assertSame($same, $object);
        $this->assertEquals($serviceName, $object->getServiceName());
        $this->assertEquals($serviceNumber, $object->getServiceNumber());
    }

    /**
     * @test
     */
    public function sendJenkinsJob()
    {
        $serviceName   = 'jenkins';
        $serviceNumber = '123';

        $env = array();
        $env['JENKINS_URL']  = 'http://localhost:8080';
        $env['BUILD_NUMBER'] = $serviceNumber;

        $object = $this->collectJsonFile();

        $same = $object->fillJobs($env);

        $this->assertSame($same, $object);
        $this->assertEquals($serviceName, $object->getServiceName());
        $this->assertEquals($serviceNumber, $object->getServiceNumber());
    }

    /**
     * @test
     */
    public function sendLocalJob()
    {
        $serviceName      = 'php-coveralls';
        $serviceEventType = 'manual';

        $env = array();
        $env['COVERALLS_REPO_TOKEN'] = 'token';
        $env['COVERALLS_RUN_LOCALLY'] = '1';

        $object = $this->collectJsonFile();

        $same = $object->fillJobs($env);

        $this->assertSame($same, $object);
        $this->assertEquals($serviceName, $object->getServiceName());
        $this->assertNull($object->getServiceJobId());
        $this->assertEquals($serviceEventType, $object->getServiceEventType());
    }

    /**
     * @test
     */
    public function sendUnsupportedJob()
    {
        $repoToken = 'token';

        $env = array();
        $env['COVERALLS_REPO_TOKEN'] = $repoToken;

        $object = $this->collectJsonFile();

        $same = $object->fillJobs($env);

        $this->assertSame($same, $object);
        $this->assertEquals($repoToken, $object->getRepoToken());
    }


    /**
     * @test
     * @expectedException RuntimeException
     */
    public function throwRuntimeExceptionIfInvalidEnv()
    {
        $env = array();

        $object = $this->collectJsonFile();

        $object->fillJobs($env);
    }

    /**
     * @test
     * @expectedException RuntimeException
     */
    public function throwRuntimeExceptionIfNoSourceFiles()
    {
        $env = array();
        $env['TRAVIS']        = true;
        $env['TRAVIS_JOB_ID'] = '1.1';

        $object = $this->collectJsonFileWithoutSourceFiles();

        $object->fillJobs($env);
    }
}
