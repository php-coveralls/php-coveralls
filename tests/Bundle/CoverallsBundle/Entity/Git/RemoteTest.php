<?php

namespace PhpCoveralls\Tests\Bundle\CoverallsBundle\Entity\Git;

use PhpCoveralls\Bundle\CoverallsBundle\Entity\Git\Remote;
use PhpCoveralls\Tests\ProjectTestCase;

/**
 * @covers \PhpCoveralls\Bundle\CoverallsBundle\Entity\Coveralls
 * @covers \PhpCoveralls\Bundle\CoverallsBundle\Entity\Git\Remote
 *
 * @author Kitamura Satoshi <with.no.parachute@gmail.com>
 */
class RemoteTest extends ProjectTestCase
{
    /**
     * @var Remote
     */
    private $object;

    // getName()

    /**
     * @test
     */
    public function shouldNotHaveRemoteNameOnConstruction()
    {
        $this->assertNull($this->object->getName());
    }

    // getUrl()

    /**
     * @test
     */
    public function shouldNotHaveUrlOnConstruction()
    {
        $this->assertNull($this->object->getUrl());
    }

    // setName()

    /**
     * @test
     */
    public function shouldSetRemoteName()
    {
        $expected = 'remote_name';

        $obj = $this->object->setName($expected);

        $this->assertSame($expected, $this->object->getName());
        $this->assertSame($obj, $this->object);
    }

    // setUrl()

    /**
     * @test
     */
    public function shouldSetRemoteUrl()
    {
        $expected = 'git@github.com:php-coveralls/php-coveralls.git';

        $obj = $this->object->setUrl($expected);

        $this->assertSame($expected, $this->object->getUrl());
        $this->assertSame($obj, $this->object);
    }

    // toArray()

    /**
     * @test
     */
    public function shouldConvertToArray()
    {
        $expected = [
            'name' => null,
            'url' => null,
        ];

        $this->assertSame($expected, $this->object->toArray());
        $this->assertSame(json_encode($expected), (string) $this->object);
    }

    /**
     * @test
     */
    public function shouldConvertToFilledArray()
    {
        $name = 'name';
        $url = 'url';

        $this->object
            ->setName($name)
            ->setUrl($url)
        ;

        $expected = [
            'name' => $name,
            'url' => $url,
        ];

        $this->assertSame($expected, $this->object->toArray());
        $this->assertSame(json_encode($expected), (string) $this->object);
    }

    protected function legacySetUp()
    {
        $this->object = new Remote();
    }
}
