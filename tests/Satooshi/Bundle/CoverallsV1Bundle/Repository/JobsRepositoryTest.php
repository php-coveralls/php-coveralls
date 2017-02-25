<?php

namespace Satooshi\Bundle\CoverallsV1Bundle\Repository;

use Satooshi\Bundle\CoverallsV1Bundle\Config\Configuration;
use Satooshi\Bundle\CoverallsV1Bundle\Entity\Exception\RequirementsNotSatisfiedException;
use Satooshi\Bundle\CoverallsV1Bundle\Entity\JsonFile;
use Satooshi\Bundle\CoverallsV1Bundle\Entity\Metrics;
use Satooshi\Bundle\CoverallsV1Bundle\Entity\SourceFile;
use Satooshi\ProjectTestCase;

/**
 * @covers \Satooshi\Bundle\CoverallsV1Bundle\Repository\JobsRepository
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

    private function setUpJobsApiMethods()
    {
        return array(
            'collectCloverXml',
            'getJsonFile',
            'collectGitInfo',
            'collectEnvVars',
            'dumpJsonFile',
            'send',
        );
    }

    private function setUpJobsApiWithCollectCloverXmlCalled($api)
    {
        $api
            ->expects($this->once())
            ->method('collectCloverXml')
            ->with()
            ->will($this->returnSelf());
    }

    private function setUpJobsApiWithCollectCloverXmlThrow($api, $exception)
    {
        $api
            ->expects($this->once())
            ->method('collectCloverXml')
            ->with()
            ->will($this->throwException($exception));
    }

    private function setUpJobsApiWithGetJsonFileCalled($api, $jsonFile)
    {
        $api
            ->expects($this->once())
            ->method('getJsonFile')
            ->with()
            ->will($this->returnValue($jsonFile));
    }

    private function setUpJobsApiWithGetJsonFileNotCalled($api)
    {
        $api
            ->expects($this->never())
            ->method('getJsonFile');
    }

    private function setUpJobsApiWithCollectGitInfoCalled($api)
    {
        $api
            ->expects($this->once())
            ->method('collectGitInfo')
            ->with()
            ->will($this->returnSelf());
    }

    private function setUpJobsApiWithCollectGitInfoNotCalled($api)
    {
        $api
            ->expects($this->never())
            ->method('collectGitInfo');
    }

    private function setUpJobsApiWithCollectEnvVarsCalled($api)
    {
        $api
            ->expects($this->once())
            ->method('collectEnvVars')
            ->with($this->equalTo($_SERVER))
            ->will($this->returnSelf());
    }

    private function setUpJobsApiWithCollectEnvVarsNotCalled($api)
    {
        $api
            ->expects($this->never())
            ->method('collectEnvVars');
    }

    private function setUpJobsApiWithDumpJsonFileCalled($api)
    {
        $api
            ->expects($this->once())
            ->method('dumpJsonFile')
            ->with()
            ->will($this->returnSelf());
    }

    private function setUpJobsApiWithDumpJsonFileNotCalled($api)
    {
        $api
            ->expects($this->never())
            ->method('dumpJsonFile');
    }

    private function setUpJobsApiWithSendCalled($api, $statusCode, $request, $response)
    {
        if ($statusCode === 200) {
            $api
                ->expects($this->once())
                ->method('send')
                ->with()
                ->will($this->returnValue($response));
        } else {
            if ($statusCode === null) {
                $exception = \GuzzleHttp\Exception\ConnectException::create($request);
            } elseif ($statusCode === 422) {
                $exception = \GuzzleHttp\Exception\ClientException::create($request, $response);
            } else {
                $exception = \GuzzleHttp\Exception\ServerException::create($request, $response);
            }

            $api
                ->expects($this->once())
                ->method('send')
                ->with()
                ->will($this->throwException($exception));
        }
    }

    private function setUpJobsApiWithSendNotCalled($api)
    {
        $api
            ->expects($this->never())
            ->method('send');
    }

    protected function createApiMockWithRequirementsNotSatisfiedException()
    {
        $jobsMethods = $this->setUpJobsApiMethods();

        if (method_exists(__CLASS__, 'createPartialMock')) {
            $api = $this->createPartialMock('Satooshi\Bundle\CoverallsV1Bundle\Api\Jobs', $jobsMethods);
        } else {
            $api = $this->getMockBuilder('Satooshi\Bundle\CoverallsV1Bundle\Api\Jobs')
            ->disableOriginalConstructor()
            ->setMethods($jobsMethods)
            ->getMock();
        }

        $this->setUpJobsApiWithCollectCloverXmlThrow($api, new RequirementsNotSatisfiedException());
        $this->setUpJobsApiWithGetJsonFileNotCalled($api);
        $this->setUpJobsApiWithCollectGitInfoNotCalled($api);
        $this->setUpJobsApiWithCollectEnvVarsNotCalled($api);
        $this->setUpJobsApiWithDumpJsonFileNotCalled($api);
        $this->setUpJobsApiWithSendNotCalled($api);

        return $api;
    }

    protected function createApiMockWithException()
    {
        $jobsMethods = $this->setUpJobsApiMethods();

        if (method_exists(__CLASS__, 'createPartialMock')) {
            $api = $this->createPartialMock('Satooshi\Bundle\CoverallsV1Bundle\Api\Jobs', $jobsMethods);
        } else {
            $api = $this->getMockBuilder('Satooshi\Bundle\CoverallsV1Bundle\Api\Jobs')
            ->disableOriginalConstructor()
            ->setMethods($jobsMethods)
            ->getMock();
        }

        $this->setUpJobsApiWithCollectCloverXmlThrow($api, new \Exception('unexpected exception'));
        $this->setUpJobsApiWithGetJsonFileNotCalled($api);
        $this->setUpJobsApiWithCollectGitInfoNotCalled($api);
        $this->setUpJobsApiWithCollectEnvVarsNotCalled($api);
        $this->setUpJobsApiWithDumpJsonFileNotCalled($api);
        $this->setUpJobsApiWithSendNotCalled($api);

        return $api;
    }

    protected function createApiMock($response, $statusCode = '', $uri = '/')
    {
        $jobsMethods = $this->setUpJobsApiMethods();

        if (method_exists(__CLASS__, 'createPartialMock')) {
            $api = $this->createPartialMock('Satooshi\Bundle\CoverallsV1Bundle\Api\Jobs', $jobsMethods);
        } else {
            $api = $this->getMockBuilder('Satooshi\Bundle\CoverallsV1Bundle\Api\Jobs')
            ->disableOriginalConstructor()
            ->setMethods($jobsMethods)
            ->getMock();
        }

        $this->setUpJobsApiWithCollectCloverXmlCalled($api);
        $this->setUpJobsApiWithGetJsonFileCalled($api, $this->createJsonFile());
        $this->setUpJobsApiWithCollectGitInfoCalled($api);
        $this->setUpJobsApiWithCollectEnvVarsCalled($api);
        $this->setUpJobsApiWithDumpJsonFileCalled($api);
        $this->setUpJobsApiWithSendCalled($api, $statusCode, new \GuzzleHttp\Psr7\Request('POST', $uri), $response);

        return $api;
    }

    protected function createLoggerMock()
    {
        $logger = $this->prophesize('\Psr\Log\NullLogger');
        $logger
            ->info();
        $logger
            ->error();

        return $logger->reveal();
    }

    // dependent object

    protected function createCoverage($percent)
    {
        // percent = (covered / stmt) * 100;
        // (percent * stmt) / 100 = covered
        $stmt = 100;
        $covered = ($percent * $stmt) / 100;
        $coverage = array_fill(0, 100, 0);

        for ($i = 0; $i < $covered; ++$i) {
            $coverage[$i] = 1;
        }

        return $coverage;
    }

    protected function createJsonFile()
    {
        $jsonFile = new JsonFile();

        $repositoryTestDir = $this->srcDir . '/RepositoryTest';

        $sourceFiles = [
            0 => new SourceFile($repositoryTestDir . '/Coverage0.php', 'Coverage0.php'),
            10 => new SourceFile($repositoryTestDir . '/Coverage10.php', 'Coverage10.php'),
            70 => new SourceFile($repositoryTestDir . '/Coverage70.php', 'Coverage70.php'),
            80 => new SourceFile($repositoryTestDir . '/Coverage80.php', 'Coverage80.php'),
            90 => new SourceFile($repositoryTestDir . '/Coverage90.php', 'Coverage90.php'),
            100 => new SourceFile($repositoryTestDir . '/Coverage100.php', 'Coverage100.php'),
        ];

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
        ->addCloverXmlPath($this->cloverXmlPath);
    }

    // persist()

    /**
     * @test
     */
    public function shouldPersist()
    {
        $statusCode = 200;
        $url        = 'https://coveralls.io/jobs/67528';
        $response   = new \GuzzleHttp\Psr7\Response(
            $statusCode, array(), json_encode(array(
                'message' => 'Job #115.3',
                'url'     => $url,
            )), '1.1', 'OK'
        );
        $api    = $this->createApiMock($response, $statusCode, $url);
        $config = $this->createConfiguration();
        $logger = $this->createLoggerMock();

        $object = new JobsRepository($api, $config);

        $object->setLogger($logger);
        $object->persist();
    }

    /**
     * @test
     */
    public function shouldPersistDryRun()
    {
        $api = $this->createApiMock(null);
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
        $api = $this->createApiMockWithException();
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
        $api = $this->createApiMockWithRequirementsNotSatisfiedException();
        $config = $this->createConfiguration();
        $logger = $this->createLoggerMock();

        $object = new JobsRepository($api, $config);

        $object->setLogger($logger);
        $object->persist();
    }

    // curl error

    /**
     * @test
     */
    public function networkDisconnected()
    {
        $api = $this->createApiMock(null, null);
        $config = $this->createConfiguration();
        $logger = $this->createLoggerMock();

        $object = new JobsRepository($api, $config);

        $object->setLogger($logger);
        $object->persist();
    }

    // response 422

    /**
     * @test
     */
    public function response422()
    {
        $statusCode = 422;
        $response   = new \GuzzleHttp\Psr7\Response(
            $statusCode, array(), json_encode(array(
                'message' => 'Build processing error.',
                'url'     => '',
                'error'   => true,
            )), '1.1', 'Unprocessable Entity'
        );
        $api    = $this->createApiMock($response, $statusCode);
        $config = $this->createConfiguration();
        $logger = $this->createLoggerMock();

        $object = new JobsRepository($api, $config);

        $object->setLogger($logger);
        $object->persist();
    }

    // response 500

    /**
     * @test
     */
    public function response500()
    {
        $statusCode = 500;
        $response   = new \GuzzleHttp\Psr7\Response($statusCode, array(), null, '1.1', 'Internal Server Error');
        $api        = $this->createApiMock($response, $statusCode);
        $config     = $this->createConfiguration();
        $logger     = $this->createLoggerMock();

        $object = new JobsRepository($api, $config);

        $object->setLogger($logger);
        $object->persist();
    }
}
