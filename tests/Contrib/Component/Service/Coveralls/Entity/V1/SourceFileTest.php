<?php
namespace Contrib\Component\Service\Coveralls\Entity\V1;

class SourceFileTest extends \PHPUnit_Framework_TestCase
{
    protected function setUp()
    {
        $this->path = __DIR__ . '/files/test.php';
        $this->filename = 'test.php';

        $this->object = new SourceFile($this->path, $this->filename);
    }

    // getName()

    /**
     * @test
     */
    public function shouldHaveNameOnConstruction()
    {
        $this->assertEquals($this->filename, $this->object->getName());
    }

    // getSource()

    /**
     * @test
     */
    public function shouldHaveSourceOnConstruction()
    {
        $expected = file_get_contents($this->path);

        $this->assertEquals($expected, $this->object->getSource());
    }

    // getCoverage()

    /**
     * @test
     */
    public function shouldHaveNullCoverageOnConstruction()
    {
        $expected = array_fill(0, 10, null);

        $this->assertEquals($expected, $this->object->getCoverage());
    }

    // getPath()

    /**
     * @test
     */
    public function shouldHavePathOnConstruction()
    {
        $this->assertEquals($this->path, $this->object->getPath());
    }

    // getFileLines()

    /**
     * @test
     */
    public function shouldHaveFileLinesOnConstruction()
    {
        $this->assertEquals(10, $this->object->getFileLines());
    }

    // toArray()

    /**
     * @test
     */
    public function toArray()
    {
        $expected = array(
            'name' => $this->filename,
            'source' => file_get_contents($this->path),
            'coverage' => array_fill(0, 10, null),
        );

        $this->assertEquals($expected, $this->object->toArray());
        $this->assertEquals(json_encode($expected), (string)$this->object);
    }

    // addCoverage()

    /**
     * @test
     */
    public function addCoverage()
    {
        $this->object->addCoverage(5, 1);

        $expected = array_fill(0, 10, null);
        $expected[5] = 1;

        $this->assertEquals($expected, $this->object->getCoverage());
    }
}
