<?php
namespace Contrib\Component\Service\Coveralls\V1\Entity;

/**
 * @covers Contrib\Component\Service\Coveralls\V1\Entity\SourceFile
 * @covers Contrib\Component\Service\Coveralls\V1\Entity\Coveralls
 *
 * @author Kitamura Satoshi <with.no.parachute@gmail.com>
 */
class SourceFileTest extends \PHPUnit_Framework_TestCase
{
    protected function setUp()
    {
        $this->dir      = realpath(__DIR__ . '/../../../../../../');
        $this->rootDir  = realpath($this->dir . '/prj/files');
        $this->filename = 'test.php';
        $this->path     = $this->rootDir . DIRECTORY_SEPARATOR . $this->filename;

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
        $expected = trim(file_get_contents($this->path));

        $this->assertEquals($expected, $this->object->getSource());
    }

    // getCoverage()

    /**
     * @test
     */
    public function shouldHaveNullCoverageOnConstruction()
    {
        $expected = array_fill(0, 9, null);

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
        $this->assertEquals(9, $this->object->getFileLines());
    }

    // toArray()

    /**
     * @test
     */
    public function toArray()
    {
        $expected = array(
            'name'     => $this->filename,
            'source'   => trim(file_get_contents($this->path)),
            'coverage' => array_fill(0, 9, null),
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

        $expected = array_fill(0, 9, null);
        $expected[5] = 1;

        $this->assertEquals($expected, $this->object->getCoverage());
    }
}
