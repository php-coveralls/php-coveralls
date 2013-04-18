<?php
namespace Contrib\Component\Service\Coveralls\V1\Config;

use Contrib\Component\File\Path;
use Symfony\Component\Config\Definition\ConfigurationInterface;
use Symfony\Component\Config\Definition\Builder\TreeBuilder;

/**
 * Definition of .coveralls.yml configuration.
 *
 * # same as ruby
 * repo_token: your-token
 * repo_secret_token: your-token
 * service_name: travis-pro
 *
 * # for php
 * src_dir: src
 * coverage_clover: build/logs/clover.xml
 * json_path: build/logs/coveralls-upload.json
 *
 * @author Kitamura Satoshi <with.no.parachute@gmail.com>
 */
class CoverallsConfiguration implements ConfigurationInterface
{
    // ConfigurationInterface

    /**
     * {@inheritdoc}
     *
     * @see \Symfony\Component\Config\Definition\ConfigurationInterface::getConfigTreeBuilder()
     */
    public function getConfigTreeBuilder()
    {
        /*
        $file    = new Path();
        $rootDir = $this->rootDir; // for PHP 5.3 compatibility

        // closures for the configuration value converter

        $realpath = function ($path) use ($file, $rootDir) {
            return $file->getRealPath($path, $rootDir);
        };

        $realWritingPath = function ($path) use ($file, $rootDir) {
            return $file->getRealWritingFilePath($path, $rootDir);
        };

        // closures for validation

        $fileNotReadable = function ($path) use ($file, $rootDir) {
            $realpath = $file->getRealPath($path, $rootDir);

            return !$file->isRealFileReadable($realpath);
        };

        $dirNotFound = function ($path) use ($file, $rootDir) {
            $realpath = $file->getRealPath($path, $rootDir);

            return !$file->isRealDirExist($realpath);
        };

        $fileNotWritable = function ($path) use ($file, $rootDir) {
            $realpath = $file->getRealPath($path, $rootDir);

            if ($realpath !== false) {
                return !$file->isRealFileWritable($realpath);
            }

            $realDir = $file->getRealDir($path, $rootDir);

            return !$file->isRealDirWritable($realDir);
        };
        */

        // define configuration

        $treeBuilder = new TreeBuilder();
        $rootNode = $treeBuilder->root('coveralls');

        $rootNode
            ->children()
                // same as ruby lib
                ->scalarNode('repo_token')
                    ->defaultNull()
                ->end()
                ->scalarNode('repo_secret_token')
                    ->defaultNull()
                ->end()
                ->scalarNode('service_name')
                    ->defaultNull()
                ->end()

                // for php lib
                ->scalarNode('src_dir')
                    ->defaultValue('src')
                    /*
                    ->defaultValue($file->getRealPath('src', $rootDir))
                    ->validate()
                        ->always($realpath)
                    ->end()
                    ->validate()
                        ->ifTrue($dirNotFound)
                            ->thenInvalid('src directory is not found')
                    ->end()
                    */
                ->end()
                ->scalarNode('coverage_clover')
                    ->defaultValue('build/logs/clover.xml')
                    /*
                    ->defaultValue($file->getRealPath('build/logs/clover.xml', $rootDir))
                    ->validate()
                        ->always($realpath)
                    ->end()
                    ->validate()
                        ->ifTrue($fileNotReadable)
                            ->thenInvalid('coverage_clover XML file is not readable')
                    ->end()
                    */
                ->end()
                ->scalarNode('json_path')
                    ->defaultValue('build/logs/coveralls-upload.json')
                    /*
                    ->defaultValue($file->getRealWritingFilePath('build/logs/coveralls-upload.json', $rootDir))
                    ->validate()
                        ->always($realWritingPath)
                    ->end()
                    ->validate()
                        ->ifTrue($fileNotWritable)
                            ->thenInvalid('json_path is not writable')
                    ->end()
                    */
                ->end()
            ->end();

        return $treeBuilder;
    }
}
