<?php
namespace Contrib\Component\Service\Coveralls\Entity\V1;

class JsonFileTest extends \PHPUnit_Framework_TestCase
{
    protected function setUp()
    {
        $this->path = __DIR__ . '/files/test.php';
        $this->filename = 'test.php';

        $this->object = new JsonFile();
    }

    protected function createSourceFile()
    {
        return new SourceFile($this->path, $this->filename);
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
        $expected = array('git');

        $obj = $this->object->setGit($expected);

        $this->assertEquals($expected, $this->object->getGit());
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
            'source_files' => array(),
            'service_name' => $item,
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
            'source_files' => array(),
            'service_job_id' => $item,
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
            'source_files' => array(),
            'repo_token' => $item,
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
        $item = array('git');

        $expected = array(
            'source_files' => array(),
            'git' => $item,
        );

        $this->assertEquals($expected, $object->toArray());
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
            'source_files' => array(),
            'run_at' => $item,
        );

        $this->assertEquals($expected, $object->toArray());
        $this->assertEquals(json_encode($expected), (string)$object);
    }
}
