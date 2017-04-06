<?php

namespace Satooshi\Bundle\CoverallsV1Bundle\Config;

use Satooshi\Component\File\Path;
use Satooshi\ProjectTestCase;
use Symfony\Component\Console\Input\ArrayInput;
use Symfony\Component\Console\Input\InputDefinition;
use Symfony\Component\Console\Input\InputOption;

/**
 * @covers \Satooshi\Bundle\CoverallsV1Bundle\Config\Configurator
 * @covers \Satooshi\Bundle\CoverallsV1Bundle\Config\CoverallsConfiguration
 *
 * @author Kitamura Satoshi <with.no.parachute@gmail.com>
 */
class ConfiguratorTest extends ProjectTestCase
{
    protected function setUp()
    {
        parent::setUp();
        $this->srcDir = $this->getRootDirSeparator() . 'src';

        $this->object = new Configurator();
    }

    protected function tearDown()
    {
        $this->rmFile($this->cloverXmlPath);
        $this->rmFile($this->cloverXmlPath1);
        $this->rmFile($this->cloverXmlPath2);
        $this->rmFile($this->jsonPath);
        $this->rmDir($this->srcDir);
        $this->rmDir($this->logsDir);
        $this->rmDir($this->buildDir);
    }

    private static function getYmlFilePath($fileName, $env)
    {
        if (Path::isWindowsOS() && $env) {
            return realpath(__DIR__ . '\yaml\\' . $fileName . '_win.yml');
        }

        return realpath(__DIR__ . '/yaml/' . $fileName . '.yml');
    }

    // custom assertion

    protected function assertConfiguration(Configuration $config, array $cloverXml, $jsonPath, $excludeNoStatements = false)
    {
        $this->assertSame($cloverXml, $config->getCloverXmlPaths());
        $this->assertSame($jsonPath, $config->getJsonPath());
        $this->assertSame($excludeNoStatements, $config->isExcludeNoStatements());
    }

    // load()

    /**
     * @test
     */
    public function shouldLoadNonExistingYml()
    {
        $this->makeProjectDir($this->srcDir, $this->logsDir, $this->cloverXmlPath);

        $path = self::getYmlFilePath('dummy', false);

        $config = $this->object->load($path, $this->rootDir);

        $this->assertConfiguration($config, [$this->cloverXmlPath], $this->jsonPath);
    }

    // default src_dir not found, it doesn't throw anything now.

    /**
     * @test
     */
    public function throwInvalidConfigurationExceptionOnLoadEmptyYmlIfSrcDirNotFound()
    {
        $this->makeProjectDir(null, $this->logsDir, $this->cloverXmlPath);

        $path = self::getYmlFilePath('dummy', false);

        $this->object->load($path, $this->rootDir);
    }

    // default coverage_clover not found

    /**
     * @test
     * @expectedException \Symfony\Component\Config\Definition\Exception\InvalidConfigurationException
     */
    public function throwInvalidConfigurationExceptionOnLoadEmptyYmlIfCoverageCloverNotFound()
    {
        $this->makeProjectDir($this->srcDir, $this->logsDir, null);

        $path = self::getYmlFilePath('dummy', false);

        $this->object->load($path, $this->rootDir);
    }

    // default json_path not writable

    /**
     * @test
     * @expectedException \Symfony\Component\Config\Definition\Exception\InvalidConfigurationException
     */
    public function throwInvalidConfigurationExceptionOnLoadEmptyYmlIfJsonPathDirNotWritable()
    {
        $this->makeProjectDir($this->srcDir, $this->logsDir, $this->cloverXmlPath, true);

        $path = self::getYmlFilePath('dummy', false);

        $this->object->load($path, $this->rootDir);
    }

    /**
     * @test
     * @expectedException \Symfony\Component\Config\Definition\Exception\InvalidConfigurationException
     */
    public function throwInvalidConfigurationExceptionOnLoadEmptyYmlIfJsonPathNotWritable()
    {
        $this->makeProjectDir($this->srcDir, $this->logsDir, $this->cloverXmlPath, false, true);

        $path = self::getYmlFilePath('dummy', false);

        $this->object->load($path, $this->rootDir);
    }

    // no configuration

    /**
     * @test
     */
    public function shouldLoadEmptyYml()
    {
        $this->makeProjectDir($this->srcDir, $this->logsDir, $this->cloverXmlPath);

        $path = self::getYmlFilePath('empty', false);

        $config = $this->object->load($path, $this->rootDir);

        $this->assertConfiguration($config, [$this->cloverXmlPath], $this->jsonPath);
    }

    // load default value yml

    /**
     * @test
     * @expectedException \Symfony\Component\Config\Definition\Exception\InvalidConfigurationException
     */
    public function shouldThrowInvalidConfigurationExceptionUponLoadingSrcDirYml()
    {
        $this->makeProjectDir($this->srcDir, $this->logsDir, $this->cloverXmlPath);

        $path = self::getYmlFilePath('src_dir', false);

        $config = $this->object->load($path, $this->rootDir);
    }

    /**
     * @test
     */
    public function shouldLoadCoverageCloverYmlContainingDefaultValue()
    {
        $this->makeProjectDir($this->srcDir, $this->logsDir, $this->cloverXmlPath);

        $path = self::getYmlFilePath('coverage_clover', true);

        $config = $this->object->load($path, $this->rootDir);

        $this->assertConfiguration($config, [$this->cloverXmlPath], $this->jsonPath);
    }

    /**
     * @test
     */
    public function shouldLoadCoverageCloverOverriddenByInput()
    {
        $this->makeProjectDir($this->srcDir, $this->logsDir, [$this->cloverXmlPath1, $this->cloverXmlPath2]);

        $path = self::getYmlFilePath('coverage_clover', true);

        // Mocking command line options.
        $defs = new InputDefinition(
            [
                new InputOption(
                    'coverage_clover',
                    'x',
                    InputOption::VALUE_REQUIRED | InputOption::VALUE_IS_ARRAY
                ),
            ]
        );
        $defaultFilePath = CoverallsConfiguration::getDefaultFilePath();
        $inputArray = [
            '--coverage_clover' => [
                $defaultFilePath . 'clover-part1.xml',
                $defaultFilePath . 'clover-part2.xml',
            ],
        ];
        $input = new ArrayInput($inputArray, $defs);
        $config = $this->object->load($path, $this->rootDir, $input);
        $this->assertConfiguration($config, [$this->cloverXmlPath1, $this->cloverXmlPath2], $this->jsonPath);
    }

    /**
     * @test
     */
    public function shouldLoadCoverageCloverYmlContainingGlobValue()
    {
        $this->makeProjectDir($this->srcDir, $this->logsDir, [$this->cloverXmlPath1, $this->cloverXmlPath2]);

        $path = self::getYmlFilePath('coverage_clover_glob', true);

        $config = $this->object->load($path, $this->rootDir);

        $this->assertConfiguration($config, [$this->cloverXmlPath1, $this->cloverXmlPath2], $this->jsonPath);
    }

    /**
     * @test
     */
    public function shouldLoadCoverageCloverYmlContainingArrayValue()
    {
        $this->makeProjectDir($this->srcDir, $this->logsDir, [$this->cloverXmlPath1, $this->cloverXmlPath2]);

        $path = self::getYmlFilePath('coverage_clover_array', true);

        $config = $this->object->load($path, $this->rootDir);

        $this->assertConfiguration($config, [$this->cloverXmlPath1, $this->cloverXmlPath2], $this->jsonPath);
    }

    /**
     * @test
     */
    public function shouldLoadJsonPathYmlContainingDefaultValue()
    {
        $this->makeProjectDir($this->srcDir, $this->logsDir, $this->cloverXmlPath);

        $path = self::getYmlFilePath('json_path', true);

        $config = $this->object->load($path, $this->rootDir);

        $this->assertConfiguration($config, [$this->cloverXmlPath], $this->jsonPath);
    }

    /**
     * @test
     */
    public function shouldLoadExcludeNoStmtYmlContainingTrue()
    {
        $this->makeProjectDir($this->srcDir, $this->logsDir, $this->cloverXmlPath);

        $path = self::getYmlFilePath('exclude_no_stmt_true', false);

        $config = $this->object->load($path, $this->rootDir);

        $this->assertConfiguration($config, [$this->cloverXmlPath], $this->jsonPath, true);
    }

    /**
     * @test
     */
    public function shouldLoadExcludeNoStmtYmlContainingFalse()
    {
        $this->makeProjectDir($this->srcDir, $this->logsDir, $this->cloverXmlPath);

        $path = self::getYmlFilePath('exclude_no_stmt_false', false);

        $config = $this->object->load($path, $this->rootDir);

        $this->assertConfiguration($config, [$this->cloverXmlPath], $this->jsonPath, false);
    }

    // configured coverage_clover not found

    /**
     * @test
     * @expectedException \Symfony\Component\Config\Definition\Exception\InvalidConfigurationException
     */
    public function throwInvalidConfigurationExceptionOnLoadCoverageCloverYmlIfCoverageCloverNotFound()
    {
        $this->makeProjectDir($this->srcDir, $this->logsDir, $this->cloverXmlPath);

        $path = self::getYmlFilePath('coverage_clover_not_found', true);

        $this->object->load($path, $this->rootDir);
    }

    /**
     * @test
     * @expectedException \Symfony\Component\Config\Definition\Exception\InvalidConfigurationException
     */
    public function throwInvalidConfigurationExceptionOnLoadCoverageCloverYmlIfCoverageCloverIsNotString()
    {
        $this->makeProjectDir($this->srcDir, $this->logsDir, $this->cloverXmlPath);

        $path = self::getYmlFilePath('coverage_clover_invalid', false);

        $this->object->load($path, $this->rootDir);
    }

    // configured json_path not found

    /**
     * @test
     * @expectedException \Symfony\Component\Config\Definition\Exception\InvalidConfigurationException
     */
    public function throwInvalidConfigurationExceptionOnLoadJsonPathYmlIfJsonPathNotFound()
    {
        $this->makeProjectDir($this->srcDir, $this->logsDir, $this->cloverXmlPath);

        $path = self::getYmlFilePath('json_path_not_found', true);

        $this->object->load($path, $this->rootDir);
    }

    // configured exclude_no_stmt invalid

    /**
     * @test
     * @expectedException \Symfony\Component\Config\Definition\Exception\InvalidConfigurationException
     */
    public function throwInvalidConfigurationExceptionOnLoadExcludeNoStmtYmlIfInvalid()
    {
        $this->makeProjectDir($this->srcDir, $this->logsDir, $this->cloverXmlPath);

        $path = self::getYmlFilePath('exclude_no_stmt_invalid', false);

        $this->object->load($path, $this->rootDir);
    }
}
