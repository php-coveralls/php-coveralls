<?php

namespace PhpCoveralls\Tests\Component\File;

use PhpCoveralls\Component\File\Path;
use PhpCoveralls\Tests\ProjectTestCase;

/**
 * @covers \PhpCoveralls\Component\File\Path
 *
 * @author Kitamura Satoshi <with.no.parachute@gmail.com>
 *
 * @internal
 */
final class PathTest extends ProjectTestCase
{
    /**
     * @var string
     */
    private $existingFile;

    /**
     * @var string
     */
    private $unreadablePath;

    /**
     * @var string
     */
    private $unwritablePath;

    /**
     * @var string
     */
    private $unwritableDir;

    /**
     * @var Path
     */
    private $object;

    // isRelativePath()

    /**
     * @test
     *
     * @dataProvider provideRelativePaths
     *
     * @param mixed $path
     */
    public function shouldBeRelativePath($path)
    {
        self::assertTrue($this->object->isRelativePath($path));
    }

    /**
     * @test
     *
     * @dataProvider provideShouldNotBeRelativePathCases
     *
     * @param mixed $path
     */
    public function shouldNotBeRelativePath($path)
    {
        self::assertFalse($this->object->isRelativePath($path));
    }

    public static function provideShouldNotBeRelativePathCases(): array
    {
        if (self::isWindowsOS()) {
            return [
                ['c:\\'],
                ['z:\path\to\somewhere'],
            ];
        }

        return [
            ['/'],
            ['/path/to/somewhere'],
        ];
    }

    // toAbsolutePath()

    /**
     * @test
     */
    public function shouldNotConvertAbsolutePath()
    {
        $path = false;
        $rootDir = __DIR__;

        self::assertFalse($this->object->toAbsolutePath($path, $rootDir));
    }

    /**
     * @test
     *
     * @dataProvider provideRelativePaths
     *
     * @param mixed $path
     */
    public function shouldConvertAbsolutePathIfRelativePathGiven($path)
    {
        $rootDir = '/path/to/dir';

        $expected = $rootDir.\DIRECTORY_SEPARATOR.$path;

        self::assertSame($expected, $this->object->toAbsolutePath($path, $rootDir));
    }

    /**
     * @test
     */
    public function shouldConvertAbsolutePathIfAbsolutePathGiven()
    {
        $rootDir = '/path/to/dir';
        $path = __DIR__;

        $expected = $path;

        self::assertSame($expected, $this->object->toAbsolutePath($path, $rootDir));
    }

    // getRealPath()

    /**
     * @test
     */
    public function shouldNotBeRealPath()
    {
        $path = false;
        $rootDir = __DIR__;

        self::assertFalse($this->object->getRealPath($path, $rootDir));
    }

    /**
     * @test
     *
     * @dataProvider provideRelativePaths
     *
     * @param mixed $path
     */
    public function shouldGetRealPathIfRelativePathGiven($path)
    {
        $rootDir = __DIR__;

        $expected = realpath($rootDir.\DIRECTORY_SEPARATOR.$path);

        self::assertSame($expected, $this->object->getRealPath($path, $rootDir));
    }

    // provider

    public static function provideRelativePaths(): array
    {
        return [
            [''],
            ['.'],
            ['..'],
        ];
    }

    /**
     * @test
     */
    public function shouldGetRealPathIfAbsolutePathGiven()
    {
        $path = __DIR__;
        $rootDir = '';

        $expected = realpath($path);

        self::assertSame($expected, $this->object->getRealPath($path, $rootDir));
    }

    // getRealDir()

    /**
     * @test
     */
    public function shouldNotBeRealDir()
    {
        $path = false;
        $rootDir = __DIR__;

        self::assertFalse($this->object->getRealDir($path, $rootDir));
    }

    /**
     * @test
     */
    public function shouldGetRealDirIfRelativePathGiven()
    {
        $path = '';
        $rootDir = __DIR__;

        $expected = realpath($rootDir.\DIRECTORY_SEPARATOR.$path);

        self::assertSame($expected, $this->object->getRealDir($path, $rootDir));
    }

    /**
     * @test
     */
    public function shouldGetRealDirIfAbsolutePathGiven()
    {
        $path = __DIR__;
        $rootDir = '';

        $expected = realpath($path.'/..');

        self::assertSame($expected, $this->object->getRealDir($path, $rootDir));
    }

    // getRealWritingFilePath()

    /**
     * @test
     */
    public function shouldNotBeRealWritingFilePath()
    {
        $path = false;
        $rootDir = __DIR__;

        self::assertFalse($this->object->getRealWritingFilePath($path, $rootDir));
    }

    /**
     * @test
     */
    public function shouldGetRealWritingPathIfRelativePathGiven()
    {
        $path = 'test.txt';
        $rootDir = __DIR__;

        $expected = $rootDir.\DIRECTORY_SEPARATOR.$path;

        self::assertSame($expected, $this->object->getRealWritingFilePath($path, $rootDir));
    }

    // isRealPathExist()

    /**
     * @test
     */
    public function shouldNotExistRealPathIfFalseGiven()
    {
        $path = false;

        self::assertFalse($this->object->isRealPathExist($path));
    }

    /**
     * @test
     */
    public function shouldNotExistRealPath()
    {
        $path = __DIR__.'/dummy.dir';

        self::assertFalse($this->object->isRealPathExist($path));
    }

    /**
     * @test
     */
    public function shouldExistRealPath()
    {
        touch($this->existingFile);

        self::assertTrue($this->object->isRealPathExist($this->existingFile));
    }

    // isRealFileExist()

    /**
     * @test
     */
    public function shouldNotExistRealFile()
    {
        $path = __DIR__.'/dummy.file';

        self::assertFalse($this->object->isRealFileExist($path));
    }

    /**
     * @test
     */
    public function shouldNotExistRealFileIfDirGiven()
    {
        $path = __DIR__;

        self::assertFalse($this->object->isRealFileExist($path));
    }

    /**
     * @test
     */
    public function shouldExistRealFile()
    {
        touch($this->existingFile);

        self::assertTrue($this->object->isRealFileExist($this->existingFile));
    }

    // isRealFileReadable()

    /**
     * @test
     */
    public function shouldNotBeRealFileReadableIfFileNotFound()
    {
        $path = __DIR__.'/dummy.file';

        self::assertFalse($this->object->isRealFileReadable($path));
    }

    /**
     * @test
     */
    public function shouldNotBeRealFileReadableIfFileUnreadable()
    {
        if (self::isWindowsOS()) {
            // On Windows there is no write-only attribute.
            self::markTestSkipped('Unable to run on Windows');
        }

        $this->touchUnreadableFile();

        self::assertFalse($this->object->isRealFileReadable($this->unreadablePath));
    }

    /**
     * @test
     */
    public function shouldBeRealFileReadable()
    {
        touch($this->existingFile);

        self::assertTrue($this->object->isRealFileReadable($this->existingFile));
    }

    // isRealFileWritable()

    /**
     * @test
     */
    public function shouldNotBeRealFileWritableIfFileNotFound()
    {
        $path = __DIR__.'/dummy.file';

        self::assertFalse($this->object->isRealFileWritable($path));
    }

    /**
     * @test
     */
    public function shouldNotBeRealFileWritableIfFileUnwritable()
    {
        $this->touchUnwritableFile();

        self::assertFalse($this->object->isRealFileWritable($this->unwritablePath));
    }

    /**
     * @test
     */
    public function shouldBeRealFileWritable()
    {
        touch($this->existingFile);

        self::assertTrue($this->object->isRealFileWritable($this->existingFile));
    }

    // isRealDirExist()

    /**
     * @test
     */
    public function shouldNotExistRealDir()
    {
        $path = __DIR__.'/dummy.dir';

        self::assertFalse($this->object->isRealDirExist($path));
    }

    /**
     * @test
     */
    public function shouldNotExistRealDirIfFileGiven()
    {
        touch($this->existingFile);

        self::assertFalse($this->object->isRealDirExist($this->existingFile));
    }

    /**
     * @test
     */
    public function shouldExistRealDir()
    {
        $path = __DIR__;

        self::assertTrue($this->object->isRealDirExist($path));
    }

    // isRealDirWritable()

    /**
     * @test
     */
    public function shouldNotBeRealDirWritableIfDirNotFound()
    {
        $path = __DIR__.'/dummy.dir';

        self::assertFalse($this->object->isRealDirWritable($path));
    }

    /**
     * @test
     */
    public function shouldNotBeRealDirWritableIfDirUnwritable()
    {
        if (self::isWindowsOS()) {
            // On Windows read-only attribute on dir applies to files in dir, but not the dir itself.
            self::markTestSkipped('Unable to run on Windows');
        }

        $this->mkdirUnwritableDir();

        self::assertFalse($this->object->isRealDirWritable($this->unwritableDir));
    }

    /**
     * @test
     */
    public function shouldBeRealDirWritable()
    {
        $path = __DIR__;

        self::assertTrue($this->object->isRealDirWritable($path));
    }

    protected function legacySetUp()
    {
        $this->existingFile = __DIR__.'/existing.txt';
        $this->unreadablePath = __DIR__.'/unreadable.txt';
        $this->unwritablePath = __DIR__.'/unwritable.txt';
        $this->unwritableDir = __DIR__.'/unwritable.dir';

        $this->object = new Path();
    }

    protected function legacyTearDown()
    {
        $this->rmFile($this->existingFile);
        $this->rmFile($this->unreadablePath);
        $this->rmFile($this->unwritablePath);

        $this->rmDir($this->unwritableDir);
    }

    protected function touchUnreadableFile()
    {
        $this->rmFile($this->unreadablePath);

        touch($this->unreadablePath);
        chmod($this->unreadablePath, 0377);
    }

    protected function touchUnwritableFile()
    {
        $this->rmFile($this->unwritablePath);

        touch($this->unwritablePath);
        chmod($this->unwritablePath, 0577);
    }

    protected function mkdirUnwritableDir()
    {
        $this->rmDir($this->unwritableDir);

        mkdir($this->unwritableDir);
        chmod($this->unwritableDir, 0577);
    }

    /**
     * @return bool
     */
    private static function isWindowsOS()
    {
        static $isWindows;

        if (null === $isWindows) {
            $isWindows = 'WIN' === strtoupper(substr(PHP_OS, 0, 3));
        }

        return $isWindows;
    }
}
