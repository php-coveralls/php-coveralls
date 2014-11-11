<?php
namespace Satooshi\Bundle\CoverallsV1Bundle\Repository;

use Ivory\HttpAdapter\HttpAdapterException;
use Satooshi\Bundle\CoverallsV1Bundle\Config\Configuration;
use Satooshi\Bundle\CoverallsV1Bundle\Entity\JsonFile;
use Satooshi\Bundle\CoverallsV1Bundle\Entity\Metrics;
use Satooshi\Bundle\CoverallsV1Bundle\Entity\SourceFile;
use Satooshi\ProjectTestCase;
use Satooshi\Bundle\CoverallsV1Bundle\Entity\Exception\RequirementsNotSatisfiedException;

/**
 * @covers Satooshi\Bundle\CoverallsV1Bundle\Repository\JobsRepository
 *
 * @author Kitamura Satoshi <with.no.parachute@gmail.com>
 */
class JobsRepositoryTest extends ProjectTestCase
{
    protected function setUp()
    {
        $this->projectDir = realpath(__DIR__ . '/../../../..');

        $this->setUpDir($this->projectDir);
    }

    // mock

    protected function createApiMockWithRequirementsNotSatisfiedException()
    {
        $jobsMethods = array(
            'collectCloverXml',
            'getJsonFile',
            'collectGitInfo',
            'collectEnvVars',
            'dumpJsonFile',
            'send'
        );
        $api = $this->getMockBuilder('Satooshi\Bundle\CoverallsV1Bundle\Api\Jobs')
        ->disableOriginalConstructor()
        ->setMethods($jobsMethods)
        ->getMock();

        $exception = new RequirementsNotSatisfiedException();

        $api
        ->expects($this->once())
        ->method('collectCloverXml')
        ->with()
        ->will($this->throwException($exception));

        $api
        ->expects($this->never())
        ->method('getJsonFile');

        $api
        ->expects($this->never())
        ->method('collectGitInfo');

        $api
        ->expects($this->never())
        ->method('collectEnvVars');

        $api
        ->expects($this->never())
        ->method('dumpJsonFile');

        $api
        ->expects($this->never())
        ->method('send');

        return $api;
    }

    protected function createApiMockWithException()
    {
        $jobsMethods = array(
            'collectCloverXml',
            'getJsonFile',
            'collectGitInfo',
            'collectEnvVars',
            'dumpJsonFile',
            'send'
        );
        $api = $this->getMockBuilder('Satooshi\Bundle\CoverallsV1Bundle\Api\Jobs')
        ->disableOriginalConstructor()
        ->setMethods($jobsMethods)
        ->getMock();

        $message = 'unexpected exception';
        $exception = new \Exception($message);

        $api
        ->expects($this->once())
        ->method('collectCloverXml')
        ->with()
        ->will($this->throwException($exception));

        $api
        ->expects($this->never())
        ->method('getJsonFile');

        $api
        ->expects($this->never())
        ->method('collectGitInfo');

        $api
        ->expects($this->never())
        ->method('collectEnvVars');

        $api
        ->expects($this->never())
        ->method('dumpJsonFile');

        $api
        ->expects($this->never())
        ->method('send');

        return $api;
    }

    protected function createApiMock($response, $statusCode = 200)
    {
        $jsonFile = $this->createJsonFile();

        $jobsMethods = array(
            'collectCloverXml',
            'getJsonFile',
            'collectGitInfo',
            'collectEnvVars',
            'dumpJsonFile',
            'send'
        );
        $api = $this->getMockBuilder('Satooshi\Bundle\CoverallsV1Bundle\Api\Jobs')
        ->disableOriginalConstructor()
        ->setMethods($jobsMethods)
        ->getMock();

        $api
        ->expects($this->once())
        ->method('collectCloverXml')
        ->with()
        ->will($this->returnSelf());

        $api
        ->expects($this->once())
        ->method('getJsonFile')
        ->with()
        ->will($this->returnValue($jsonFile));

        $api
        ->expects($this->once())
        ->method('collectGitInfo')
        ->with()
        ->will($this->returnSelf());

        $api
        ->expects($this->once())
        ->method('collectEnvVars')
        ->with($this->equalTo($_SERVER))
        ->will($this->returnSelf());

        $api
        ->expects($this->once())
        ->method('dumpJsonFile')
        ->with()
        ->will($this->returnSelf());

        if ($statusCode === 200) {
            $api
            ->expects($this->once())
            ->method('send')
            ->with()
            ->will($this->returnValue($response));
        } else {
            if ($response !== null) {
                $exception = new HttpAdapterException();
                $exception->setResponse($response);
            } else {
                $exception = new \Exception();
            }

            $api
            ->expects($this->once())
            ->method('send')
            ->with()
            ->will($this->throwException($exception));
        }

        return $api;
    }

    protected function createResponseMock($statusCode, $reasonPhrase, $body)
    {
        $json     = is_array($body) ? json_encode($body) : $body;
        $response = $this->getMock('Psr\Http\Message\ResponseInterface');

        $response
        ->expects($this->any())
        ->method('getStatusCode')
        ->with()
        ->will($this->returnValue($statusCode));

        $response
        ->expects($this->any())
        ->method('getReasonPhrase')
        ->with()
        ->will($this->returnValue($reasonPhrase));

        $response
        ->expects($this->any())
        ->method('getBody')
        ->with()
        ->will($this->returnValue($json));

        return $response;
    }

    protected function createLoggerMock()
    {
        $logger = $this->getMock('Psr\Log\NullLogger', array('info', 'error'));

        $logger
        ->expects($this->any())
        ->method('info')
        ->with();

        $logger
        ->expects($this->any())
        ->method('error')
        ->with();

        return $logger;
    }

    // dependent object

    protected function createCoverage($percent)
    {
        // percent = (covered / stmt) * 100;
        // (percent * stmt) / 100 = covered
        $stmt     = 100;
        $covered  = ($percent * $stmt) / 100;
        $coverage = array_fill(0, 100, 0);

        for ($i = 0; $i < $covered; $i++) {
            $coverage[$i] = 1;
        }

        return $coverage;
    }

    protected function createJsonFile()
    {
        $jsonFile = new JsonFile();

        $repositoryTestDir = $this->srcDir  . '/RepositoryTest';

        $sourceFiles = array(
            0   => new SourceFile($repositoryTestDir . '/Coverage0.php',   'Coverage0.php'),
            10  => new SourceFile($repositoryTestDir . '/Coverage10.php',  'Coverage10.php'),
            70  => new SourceFile($repositoryTestDir . '/Coverage70.php',  'Coverage70.php'),
            80  => new SourceFile($repositoryTestDir . '/Coverage80.php',  'Coverage80.php'),
            90  => new SourceFile($repositoryTestDir . '/Coverage90.php',  'Coverage90.php'),
            100 => new SourceFile($repositoryTestDir . '/Coverage100.php', 'Coverage100.php'),
        );

        foreach ($sourceFiles as $percent => $sourceFile) {
            $sourceFile->getMetrics()->merge(new Metrics($this->createCoverage($percent)));
            $jsonFile->addSourceFile($sourceFile);
        }

        return $jsonFile;
    }

    protected function createConfiguration()
    {
        $config = new Configuration();

        return $config
        ->setSrcDir($this->srcDir)
        ->addCloverXmlPath($this->cloverXmlPath);
    }

    // persist()

    /**
     * @test
     */
    public function shouldPersist()
    {
        $statusCode = 200;
        $json       = array('message' => 'Job #115.3', 'url' => 'https://coveralls.io/jobs/67528');
        $response   = $this->createResponseMock($statusCode, 'OK', $json);
        $api        = $this->createApiMock($response, $statusCode);
        $config     = $this->createConfiguration();
        $logger     = $this->createLoggerMock();

        $object = new JobsRepository($api, $config);

        $object->setLogger($logger);
        $object->persist();
    }

    /**
     * @test
     */
    public function shouldPersistDryRun()
    {
        $api    = $this->createApiMock(null);
        $config = $this->createConfiguration();
        $logger = $this->createLoggerMock();

        $object = new JobsRepository($api, $config);

        $object->setLogger($logger);
        $object->persist();
    }

    // unexpected Exception
    // source files not found

    /**
     * @test
     */
    public function unexpectedException()
    {
        $api    = $this->createApiMockWithException();
        $config = $this->createConfiguration();
        $logger = $this->createLoggerMock();

        $object = new JobsRepository($api, $config);

        $object->setLogger($logger);
        $object->persist();
    }

    /**
     * @test
     */
    public function requirementsNotSatisfiedException()
    {
        $api    = $this->createApiMockWithRequirementsNotSatisfiedException();
        $config = $this->createConfiguration();
        $logger = $this->createLoggerMock();

        $object = new JobsRepository($api, $config);

        $object->setLogger($logger);
        $object->persist();
    }

    // error

    /**
     * @test
     */
    public function networkDisconnected()
    {
        $api    = $this->createApiMock(null, null);
        $config = $this->createConfiguration();
        $logger = $this->createLoggerMock();

        $object = new JobsRepository($api, $config);

        $object->setLogger($logger);
        $object->persist();
    }

    // http error

    /**
     * @test
     */
    public function responseError()
    {
        $statusCode = 422;
        $response   = $this->createResponseMock($statusCode, 'Unprocessable Entity', null);
        $api        = $this->createApiMock($response, $statusCode);
        $config     = $this->createConfiguration();
        $logger     = $this->createLoggerMock();

        $object = new JobsRepository($api, $config);

        $object->setLogger($logger);
        $object->persist();
    }

    // json error

    /**
     * @test
     */
    public function jsonError()
    {
        $statusCode = 500;
        $response   = $this->createResponseMock($statusCode, 'Error', '{"error":"foo","message":"bar"}');
        $api        = $this->createApiMock($response, $statusCode);
        $config     = $this->createConfiguration();
        $logger     = $this->createLoggerMock();

        $object = new JobsRepository($api, $config);

        $object->setLogger($logger);
        $object->persist();
    }
}
