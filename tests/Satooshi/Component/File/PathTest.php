<?php

namespace Satooshi\Component\File;

use Satooshi\ProjectTestCase;

/**
 * @covers \Satooshi\Component\File\Path
 *
 * @author Kitamura Satoshi <with.no.parachute@gmail.com>
 */
class PathTest extends ProjectTestCase
{
    protected function setUp()
    {
        $currentDir = $this->getPathSeparator(__DIR__);
        $this->existingFile = $currentDir . 'existing.txt';
        $this->unreadablePath = $currentDir . 'unreadable.txt';
        $this->unwritablePath = $currentDir . 'unwritable.txt';
        $this->unwritableDir = $currentDir . 'unwritable.dir';

        $this->object = new Path();
    }

    protected function tearDown()
    {
        $this->rmFile($this->existingFile);
        $this->rmFile($this->unreadablePath);
        $this->rmFile($this->unwritablePath);

        $this->rmDir($this->unwritableDir);
    }

    protected function touchUnreadableFile()
    {
        $this->rmFile($this->unreadablePath);

        if (!Path::isWindowsOS()) {
            touch($this->unreadablePath);
            chmod($this->unreadablePath, 0377);
        } else {
            $command = 'attrib -A -H -I -R -S ' . $this->unreadablePath;
            exec($command, $result, $returnValue);

            if ($returnValue !== 0) {
                throw new \RuntimeException(sprintf('Failed to execute command: %s', $command), $returnValue);
            }
        }
    }

    protected function mkdirUnwritableDir()
    {
        $this->rmDir($this->unwritableDir);

        if (!Path::isWindowsOS()) {
            mkdir($this->unwritableDir);
            chmod($this->unwritableDir, 0577);
        } elseif (is_dir($this->unwritableDir)) {
            throw new InvalidConfigurationException(sprintf('Windows directory attribute is ng: %s', $this->unwritableDir));
        } else {
            $command = 'attrib -A -H -I +R -S ' . $this->unwritableDir;
            exec($command, $result, $returnValue);

            if ($returnValue !== 0) {
                throw new \RuntimeException(sprintf('Failed to execute command: %s', $command), $returnValue);
            }
        }
    }

    // provider

    public function provideRelativePaths()
    {
        return [
            [''],
            ['.'],
            ['..'],
        ];
    }

    public function provideAbsolutePaths()
    {
        if (Path::isWindowsOS()) {
            return [
                ['c:\\'],
                ['z:\\path\\to\\somewhere'],
            ];
        }

        return [
            ['/'],
            ['/path/to/somewhere'],
        ];
    }

    // isRelativePath()

    /**
     * @test
     * @dataProvider provideRelativePaths
     */
    public function shouldBeRelativePath($path)
    {
        $this->assertTrue($this->object->isRelativePath($path));
    }

    /**
     * @test
     * @dataProvider provideAbsolutePaths
     */
    public function shouldNotBeRelativePath($path)
    {
        $this->assertFalse($this->object->isRelativePath($path));
    }

    // toAbsolutePath()

    /**
     * @test
     */
    public function shouldNotConvertAbsolutePath()
    {
        $path = false;
        $rootDir = __DIR__;

        $this->assertFalse($this->object->toAbsolutePath($path, $rootDir));
    }

    /**
     * @test
     * @dataProvider provideRelativePaths
     */
    public function shouldConvertAbsolutePathIfRelativePathGiven($path)
    {
        $rootDir = $this->getPathToSeparator() . 'dir';

        $expected = $rootDir . DIRECTORY_SEPARATOR . $path;

        $this->assertSame($expected, $this->object->toAbsolutePath($path, $rootDir));
    }

    /**
     * @test
     */
    public function shouldConvertAbsolutePathIfAbsolutePathGiven()
    {
        $rootDir = $this->getPathToSeparator() . 'dir';
        $path = __DIR__;

        $expected = $path;

        $this->assertSame($expected, $this->object->toAbsolutePath($path, $rootDir));
    }

    // getRealPath()

    /**
     * @test
     */
    public function shouldNotBeRealPath()
    {
        $path = false;
        $rootDir = __DIR__;

        $this->assertFalse($this->object->getRealPath($path, $rootDir));
    }

    /**
     * @test
     * @dataProvider provideRelativePaths
     */
    public function shouldGetRealPathIfRelativePathGiven($path)
    {
        $rootDir = __DIR__;

        $expected = realpath($rootDir . DIRECTORY_SEPARATOR . $path);

        $this->assertSame($expected, $this->object->getRealPath($path, $rootDir));
    }

    /**
     * @test
     */
    public function shouldGetRealPathIfAbsolutePathGiven()
    {
        $path = __DIR__;
        $rootDir = '';

        $expected = realpath($path);

        $this->assertSame($expected, $this->object->getRealPath($path, $rootDir));
    }

    // getRealDir()

    /**
     * @test
     */
    public function shouldNotBeRealDir()
    {
        $path = false;
        $rootDir = __DIR__;

        $this->assertFalse($this->object->getRealDir($path, $rootDir));
    }

    /**
     * @test
     */
    public function shouldGetRealDirIfRelativePathGiven()
    {
        $path = '';
        $rootDir = __DIR__;

        $expected = realpath($rootDir . DIRECTORY_SEPARATOR . $path);

        $this->assertSame($expected, $this->object->getRealDir($path, $rootDir));
    }

    /**
     * @test
     */
    public function shouldGetRealDirIfAbsolutePathGiven()
    {
        $path = __DIR__;
        $rootDir = '';

        $expected = realpath($path . DIRECTORY_SEPARATOR . '..');

        $this->assertSame($expected, $this->object->getRealDir($path, $rootDir));
    }

    // getRealWritingFilePath()

    /**
     * @test
     */
    public function shouldNotBeRealWritingFilePath()
    {
        $path = false;
        $rootDir = __DIR__;

        $this->assertFalse($this->object->getRealWritingFilePath($path, $rootDir));
    }

    /**
     * @test
     */
    public function shouldGetRealWritingPathIfRelativePathGiven()
    {
        $path = 'test.txt';
        $rootDir = __DIR__;

        $expected = $rootDir . DIRECTORY_SEPARATOR . $path;

        $this->assertSame($expected, $this->object->getRealWritingFilePath($path, $rootDir));
    }

    // isRealPathExist()

    /**
     * @test
     */
    public function shouldNotExistRealPathIfFalseGiven()
    {
        $path = false;

        $this->assertFalse($this->object->isRealPathExist($path));
    }

    /**
     * @test
     */
    public function shouldNotExistRealPath()
    {
        $path = $this->getPathSeparator(__DIR__) . 'dummy.dir';

        $this->assertFalse($this->object->isRealPathExist($path));
    }

    /**
     * @test
     */
    public function shouldExistRealPath()
    {
        touch($this->existingFile);

        $this->assertTrue($this->object->isRealPathExist($this->existingFile));
    }

    // isRealFileExist()

    /**
     * @test
     */
    public function shouldNotExistRealFile()
    {
        $path = $this->getPathSeparator(__DIR__) . 'dummy.file';

        $this->assertFalse($this->object->isRealFileExist($path));
    }

    /**
     * @test
     */
    public function shouldNotExistRealFileIfDirGiven()
    {
        $path = __DIR__;

        $this->assertFalse($this->object->isRealFileExist($path));
    }

    /**
     * @test
     */
    public function shouldExistRealFile()
    {
        touch($this->existingFile);

        $this->assertTrue($this->object->isRealFileExist($this->existingFile));
    }

    // isRealFileReadable()

    /**
     * @test
     */
    public function shouldNotBeRealFileReadableIfFileNotFound()
    {
        $path = $this->getPathSeparator(__DIR__) . 'dummy.file';

        $this->assertFalse($this->object->isRealFileReadable($path));
    }

    /**
     * @test
     */
    public function shouldNotBeRealFileReadableIfFileUnreadable()
    {
        $this->touchUnreadableFile();

        $this->assertFalse($this->object->isRealFileReadable($this->unreadablePath));
    }

    /**
     * @test
     */
    public function shouldBeRealFileReadable()
    {
        touch($this->existingFile);

        $this->assertTrue($this->object->isRealFileReadable($this->existingFile));
    }

    // isRealFileWritable()

    /**
     * @test
     */
    public function shouldNotBeRealFileWritableIfFileNotFound()
    {
        $path = $this->getPathSeparator(__DIR__) . 'dummy.file';

        $this->assertFalse($this->object->isRealFileWritable($path));
    }

    /**
     * @test
     */
    public function shouldNotBeRealFileWritableIfFileUnwritable()
    {
        $this->rmFile($this->unwritablePath);
        touch($this->unwritablePath);
        $this->unwritableFile($this->unwritablePath);

        $this->assertFalse($this->object->isRealFileWritable($this->unwritablePath));
    }

    /**
     * @test
     */
    public function shouldBeRealFileWritable()
    {
        touch($this->existingFile);

        $this->assertTrue($this->object->isRealFileWritable($this->existingFile));
    }

    // isRealDirExist()

    /**
     * @test
     */
    public function shouldNotExistRealDir()
    {
        $path = $this->getPathSeparator(__DIR__) . 'dummy.dir';

        $this->assertFalse($this->object->isRealDirExist($path));
    }

    /**
     * @test
     */
    public function shouldNotExistRealDirIfFileGiven()
    {
        touch($this->existingFile);

        $this->assertFalse($this->object->isRealDirExist($this->existingFile));
    }

    /**
     * @test
     */
    public function shouldExistRealDir()
    {
        $path = __DIR__;

        $this->assertTrue($this->object->isRealDirExist($path));
    }

    // isRealDirWritable()

    /**
     * @test
     */
    public function shouldNotBeRealDirWritableIfDirNotFound()
    {
        $path = $this->getPathSeparator(__DIR__) . 'dummy.dir';

        $this->assertFalse($this->object->isRealDirWritable($path));
    }

    /**
     * @test
     */
    public function shouldNotBeRealDirWritableIfDirUnwritable()
    {
        $this->mkdirUnwritableDir();

        $this->assertFalse($this->object->isRealDirWritable($this->unwritableDir));
    }

    /**
     * @test
     */
    public function shouldBeRealDirWritable()
    {
        $path = __DIR__;

        $this->assertTrue($this->object->isRealDirWritable($path));
    }
}
