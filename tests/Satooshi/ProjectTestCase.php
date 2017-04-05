<?php

namespace Satooshi;

use Satooshi\Component\File\Path;
use Symfony\Component\Config\Definition\Exception\InvalidConfigurationException;

class ProjectTestCase extends \PHPUnit_Framework_TestCase
{
    protected function setUp()
    {
        $this->projectDir = realpath(dirname(__DIR__));
        $this->setUpDir($this->projectDir);
    }

    protected function setUpDir($projectDir)
    {
        $this->rootDir = realpath($projectDir . DIRECTORY_SEPARATOR . 'prj');
        $this->srcDir = realpath($this->getRootDirSeparator() . 'files');

        $this->url = 'https://coveralls.io/api/v1/jobs';
        $this->filename = 'json_file';

        // build
        $this->buildDir = $this->getRootDirSeparator() . 'build';
        $this->logsDir = $this->getRootDirSeparator() . 'build' . DIRECTORY_SEPARATOR . 'logs';

        // log
        $this->cloverXmlPath = $this->getLogsDirSeparator() . 'clover.xml';
        $this->cloverXmlPath1 = $this->getLogsDirSeparator() . 'clover-part1.xml';
        $this->cloverXmlPath2 = $this->getLogsDirSeparator() . 'clover-part2.xml';
        $this->jsonPath = $this->getLogsDirSeparator() . 'coveralls-upload.json';
    }

    protected function getRootDirSeparator()
    {
        return $this->rootDir . DIRECTORY_SEPARATOR;
    }

    protected function getSrcDirSeparator()
    {
        return $this->srcDir . DIRECTORY_SEPARATOR;
    }

    private function getLogsDirSeparator()
    {
        return $this->logsDir . DIRECTORY_SEPARATOR;
    }

    protected function getPathSeparator($path)
    {
        return $path . DIRECTORY_SEPARATOR;
    }

    protected function makeProjectDir($srcDir = null, $logsDir = null, $cloverXmlPaths = null, $logsDirUnwritable = false, $jsonPathUnwritable = false)
    {
        if ($srcDir !== null && !is_dir($srcDir)) {
            mkdir($srcDir, 0777, true);
        }

        if ($logsDir !== null && !is_dir($logsDir)) {
            mkdir($logsDir, 0777, true);
        }

        if ($cloverXmlPaths !== null) {
            if (is_array($cloverXmlPaths)) {
                foreach ($cloverXmlPaths as $cloverXmlPath) {
                    touch($cloverXmlPath);
                }
            } else {
                touch($cloverXmlPaths);
            }
        }

        if ($logsDirUnwritable) {
            $this->unwritableFile($logsDir);
        }

        if ($jsonPathUnwritable) {
            touch($this->jsonPath);
            $this->unwritableFile($this->jsonPath);
        }
    }

    protected function rmFile($file)
    {
        if (is_file($file)) {
            if (!Path::isWindowsOS()) {
                chmod(dirname($file), 0777);
                unlink($file);
            } else {
                $command = 'del /Q /F ' . $file;
                exec($command, $result, $returnValue);

                if ($returnValue !== 0) {
                    throw new \RuntimeException(sprintf('Failed to execute command: %s', $command), $returnValue);
                }
            }
        }
    }

    protected function rmDir($dir)
    {
        if (is_dir($dir)) {
            if (!Path::isWindowsOS()) {
                chmod($dir, 0777);
                rmdir($dir);
            } else {
                $command = 'rmdir /q /s ' . $dir;
                exec($command, $result, $returnValue);

                if ($returnValue !== 0) {
                    throw new \RuntimeException(sprintf('Failed to execute command: %s', $command), $returnValue);
                }
            }
        }
    }

    protected function unwritableFile($file)
    {
        if (!file_exists($file)) {
            throw new InvalidConfigurationException(sprintf('Failed to directory exists: %s', $file));
        }

        if (!Path::isWindowsOS()) {
            chmod($file, 0577);
        } elseif (is_dir($file)) {
            throw new InvalidConfigurationException(sprintf('Windows directory attribute is ng: %s', $file));
        } else {
            $command = 'attrib -A -H -I +R -S ' . $file;
            exec($command, $result, $returnValue);

            if ($returnValue !== 0) {
                throw new \RuntimeException(sprintf('Failed to execute command: %s', $command), $returnValue);
            }
        }
    }
}
