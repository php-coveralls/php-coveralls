<?php
namespace Contrib\Component\Service\Coveralls\Collector\V1;

use Contrib\Component\Service\Coveralls\Entity\V1\JsonFile;
use Contrib\Component\Service\Coveralls\Entity\V1\SourceFile;

/**
 * Coverage collector for clover.xml.
 *
 * @author Kitamura Satoshi <with.no.parachute@gmail.com>
 */
class CloverXmlCoverageCollector
{
    // API

    /**
     * Collect coverage from XML object.
     *
     * @param SimpleXMLElement $xml     Clover XML object.
     * @param string           $rootDir Path to src directory.
     * @return \Contrib\Component\Service\Coveralls\Entity\V1\JsonFile
     */
    public function collect(\SimpleXMLElement $xml, $rootDir)
    {
        //TODO assert rootDir exists
        $root = realpath($rootDir) . DIRECTORY_SEPARATOR;
        $jsonFile = new JsonFile();

        $runAt = $this->collectRunAt($xml);
        $jsonFile->setRunAt($runAt);

        $xpaths = array(
            '/coverage/project/file',
            '/coverage/project/package/file',
        );

        foreach ($xpaths as $xpath) {
            foreach ($xml->xpath($xpath) as $file) {
                $srcFile = $this->collectFileCoverage($file, $root);

                if ($srcFile !== null) {
                    $jsonFile->addSourceFile($srcFile);
                }
            }
        }

        return $jsonFile;
    }

    // Internal method

    /**
     * Collect timestamp when the job ran.
     *
     * @param SimpleXMLElement $xml    Clover XML object of a file.
     * @param string           $format DateTime format.
     * @return string
     */
    protected function collectRunAt(\SimpleXMLElement $xml, $format = 'Y-m-d H:i:s O')
    {
        $timestamp = $xml->project['timestamp'];
        $runAt     = new \DateTime('@' . $timestamp);

        return $runAt->format($format);
    }

    /**
     * Collect coverage data of a file.
     *
     * @param SimpleXMLElement $file Clover XML object of a file.
     * @param string           $root Path to src directory.
     * @return NULL|\Contrib\Component\Service\Coveralls\Entity\V1\SourceFile
     */
    protected function collectFileCoverage(\SimpleXMLElement $file, $root)
    {
        $fullpath = (string)$file['name'];

        if (false === strpos($fullpath, $root)) {
            return null;
        }

        $filename = str_replace($root, '', $fullpath);

        return $this->collectCoverage($file, $fullpath, $filename);
    }

    /**
     * Collect coverage data.
     *
     * @param SimpleXMLElement $file     Clover XML object of a file.
     * @param string           $path     Path to source file.
     * @param string           $filename Filename.
     * @return \Contrib\Component\Service\Coveralls\Entity\V1\SourceFile
     */
    protected function collectCoverage(\SimpleXMLElement $file, $path, $filename)
    {
        $srcFile = new SourceFile($path, $filename);

        foreach ($file->line as $line) {
            if ((string)$line['type'] === 'stmt') {
                $lineNum = (int)$line['num'];

                if ($lineNum > 0) {
                    $srcFile->addCoverage($lineNum - 1, (int)$line['count']);
                }
            }
        }

        return $srcFile;
    }
}
