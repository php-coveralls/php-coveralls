<?php
namespace Contrib\Component\Service\Coveralls\Collector\V1;

use Contrib\Component\Service\Coveralls\Entity\V1\JsonFile;
use Contrib\Component\Service\Coveralls\Entity\V1\SourceFile;

class CloverXmlCoverageCollectorTest extends \PHPUnit_Framework_TestCase
{
    protected function setUp()
    {
        $this->dir  = realpath(__DIR__ . '/../../');
        $this->root = $this->dir . '/Entity/V1/files/';

        $this->object = new CloverXmlCoverageCollector();
    }

    protected function createCloverXml()
    {
        $xml = <<<XML
<?xml version="1.0" encoding="UTF-8"?>
<coverage generated="1365848893">
  <project timestamp="1365848893">
    <file name="%s/Entity/V1/files/test.php">
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
      <file name="%s/Entity/V1/files/test2.php">
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

        return simplexml_load_string(sprintf($xml, $this->dir, $this->dir));
    }

    // collect()

    /**
     * @test
     */
    public function collect()
    {
        $xml      = $this->createCloverXml();
        $jsonFile = $this->object->collect($xml, $this->root);

        $this->assertJsonFile($jsonFile, null, null, null, null, '2013-04-13 10:28:13 +0000');

        return $jsonFile;
    }

    /**
     * @test
     * @depends collect
     */
    public function collectSourceFiles(JsonFile $jsonFile)
    {
        $sourceFiles = $jsonFile->getSourceFiles();

        $this->assertCount(2, $sourceFiles);

        return $jsonFile;
    }

    /**
     * @test
     * @depends collectSourceFiles
     */
    public function collectSourceFileTest1(JsonFile $jsonFile)
    {
        $sourceFiles = $jsonFile->getSourceFiles();

        $this->assertSourceFileTest1($sourceFiles[0]);
    }

    /**
     * @test
     * @depends collectSourceFiles
     */
    public function collectSourceFileTest2(JsonFile $jsonFile)
    {
        $sourceFiles = $jsonFile->getSourceFiles();

        $this->assertSourceFileTest2($sourceFiles[1]);
    }

    // custom assert

    protected function assertJsonFile($jsonFile, $serviceName, $serviceJobId, $repoToken, $git, $runAt)
    {
        $this->assertEquals($serviceName, $jsonFile->getServiceName());
        $this->assertEquals($serviceJobId, $jsonFile->getServiceJobId());
        $this->assertEquals($repoToken, $jsonFile->getRepoToken());
        $this->assertSame($git, $jsonFile->getGit());
        $this->assertEquals($runAt, $jsonFile->getRunAt());
    }

    protected function assertSourceFile(SourceFile $sourceFile, $name, $path, $fileLines, array $coverage, $source)
    {
        $this->assertEquals($name, $sourceFile->getName());
        $this->assertEquals($path, $sourceFile->getPath());
        $this->assertEquals($fileLines, $sourceFile->getFileLines());
        $this->assertSame($coverage, $sourceFile->getCoverage());
        $this->assertEquals($source, $sourceFile->getSource());
    }

    protected function assertSourceFileTest1(SourceFile $sourceFile)
    {
        $name1        = 'test.php';
        $path1        = $this->dir . '/Entity/V1/files/' . $name1;
        $fileLines1   = 10;
        $coverage1    = array_fill(0, $fileLines1, null);
        $coverage1[6] = 0;
        $source1      = file_get_contents($path1);

        $this->assertSourceFile($sourceFile, $name1, $path1, $fileLines1, $coverage1, $source1);
    }

    protected function assertSourceFileTest2(SourceFile $sourceFile)
    {
        $name2        = 'test2.php';
        $path2        = $this->dir . '/Entity/V1/files/' . $name2;
        $fileLines2   = 11;
        $coverage2    = array_fill(0, $fileLines2, null);
        $coverage2[7] = 0;
        $source2      = file_get_contents($path2);

        $this->assertSourceFile($sourceFile, $name2, $path2, $fileLines2, $coverage2, $source2);
    }
}
