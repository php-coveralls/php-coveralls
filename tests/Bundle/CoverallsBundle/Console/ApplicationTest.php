<?php

namespace PhpCoveralls\Tests\Bundle\CoverallsBundle\Console;

use PhpCoveralls\Bundle\CoverallsBundle\Console\ApplicationFactory;
use PhpCoveralls\Tests\ProjectTestCase;
use Symfony\Component\Console\Tester\ApplicationTester;

/**
 * @author Kitamura Satoshi <with.no.parachute@gmail.com>
 *
 * @internal
 *
 * @info   This test is also covering \PhpCoveralls\Bundle\CoverallsBundle\Console\Application, but we cannot declare it as if so, PCOV will autoload it and it will fail on non-compat Sf/PHP combination, as we cannot declare partial loading anymore
 *
 * @covers \PhpCoveralls\Bundle\CoverallsBundle\Console\ApplicationFactory
 */
final class ApplicationTest extends ProjectTestCase
{
    /**
     * @test
     */
    public function shouldExecuteCoverallsJobsCommand()
    {
        $this->makeProjectDir(null, $this->logsDir);
        $this->dumpCloverXml();

        $app = ApplicationFactory::create($this->rootDir);
        $app->setAutoExit(false); // avoid to call exit() in Application

        // run
        $_SERVER['TRAVIS'] = true;
        $_SERVER['TRAVIS_BUILD_NUMBER'] = 'application_build';
        $_SERVER['TRAVIS_JOB_ID'] = 'application_test';

        $tester = new ApplicationTester($app);
        $actual = $tester->run(
            [
                '--dry-run' => true,
                '--config' => 'coveralls.yml',
            ]
        );

        self::assertSame(0, $actual);
    }

    protected function legacySetUp()
    {
        $this->setUpDir(realpath(__DIR__.'/../../..'));
    }

    protected function legacyTearDown()
    {
        $this->rmFile($this->cloverXmlPath);
        $this->rmFile($this->jsonPath);
        $this->rmDir($this->logsDir);
        $this->rmDir($this->buildDir);
    }

    /**
     * @return string
     */
    protected function getCloverXml()
    {
        $xml = <<<'XML'
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

        return \sprintf($xml, $this->srcDir, $this->srcDir);
    }

    protected function dumpCloverXml()
    {
        file_put_contents($this->cloverXmlPath, $this->getCloverXml());
    }
}
