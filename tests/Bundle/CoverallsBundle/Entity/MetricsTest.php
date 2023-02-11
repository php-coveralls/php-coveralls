<?php

namespace PhpCoveralls\Tests\Bundle\CoverallsBundle\Entity;

use PhpCoveralls\Bundle\CoverallsBundle\Entity\Metrics;
use PhpCoveralls\Tests\ProjectTestCase;

/**
 * @covers \PhpCoveralls\Bundle\CoverallsBundle\Entity\Metrics
 *
 * @author Kitamura Satoshi <with.no.parachute@gmail.com>
 *
 * @internal
 */
final class MetricsTest extends ProjectTestCase
{
    /**
     * @var array
     */
    private $coverage;

    // hasStatements()
    // getStatements()

    /**
     * @test
     */
    public function shouldNotHaveStatementsOnConstructionWithoutCoverage()
    {
        $object = new Metrics();

        static::assertFalse($object->hasStatements());
        static::assertSame(0, $object->getStatements());
    }

    /**
     * @test
     */
    public function shouldHaveStatementsOnConstructionWithCoverage()
    {
        $object = new Metrics($this->coverage);

        static::assertTrue($object->hasStatements());
        static::assertSame(3, $object->getStatements());
    }

    // getCoveredStatements()

    /**
     * @test
     */
    public function shouldNotHaveCoveredStatementsOnConstructionWithoutCoverage()
    {
        $object = new Metrics();

        static::assertSame(0, $object->getCoveredStatements());
    }

    /**
     * @test
     */
    public function shouldHaveCoveredStatementsOnConstructionWithCoverage()
    {
        $object = new Metrics($this->coverage);

        static::assertSame(2, $object->getCoveredStatements());
    }

    // getLineCoverage()

    /**
     * @test
     */
    public function shouldNotHaveLineCoverageOnConstructionWithoutCoverage()
    {
        $object = new Metrics();

        static::assertSame(0, $object->getLineCoverage());
    }

    /**
     * @test
     */
    public function shouldHaveLineCoverageOnConstructionWithCoverage()
    {
        $object = new Metrics($this->coverage);

        $this->assertWithDelta(200 / 3, $object->getLineCoverage(), 0.0000000000001);
    }

    // merge()

    /**
     * @test
     */
    public function shouldMergeThatWithEmptyMetrics()
    {
        $object = new Metrics();
        $that = new Metrics($this->coverage);
        $object->merge($that);

        static::assertSame(3, $object->getStatements());
        static::assertSame(2, $object->getCoveredStatements());
        $this->assertWithDelta(200 / 3, $object->getLineCoverage(), 0.0000000000001);
    }

    /**
     * @test
     */
    public function shouldMergeThat()
    {
        $object = new Metrics($this->coverage);
        $that = new Metrics($this->coverage);
        $object->merge($that);

        static::assertSame(6, $object->getStatements());
        static::assertSame(4, $object->getCoveredStatements());
        $this->assertWithDelta(400 / 6, $object->getLineCoverage(), 0.0000000000001);
    }

    protected function setUp() : void
    {
        $this->coverage = array_fill(0, 5, null);
        $this->coverage[1] = 1;
        $this->coverage[3] = 1;
        $this->coverage[4] = 0;
    }

    private function assertWithDelta($expected, $actual, $delta, $message = '')
    {
        if (method_exists(ProjectTestCase::class, 'assertEqualsWithDelta')) {
            parent::assertEqualsWithDelta($expected, $actual, $delta, $message);

            return;
        }

        static::assertEquals($expected, $actual, $message, $delta);
    }
}
