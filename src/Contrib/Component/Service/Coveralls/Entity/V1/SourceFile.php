<?php
namespace Contrib\Component\Service\Coveralls\Entity\V1;

/**
 * Data represents "source_files" element of Coveralls' "json_file".
 *
 * @author Kitamura Satoshi <with.no.parachute@gmail.com>
 */
class SourceFile extends Coveralls
{
    /**
     * Source filename.
     *
     * @var string
     */
    protected $name;

    /**
     * Source content.
     *
     * @var string
     */
    protected $source;

    /**
     * Coverage data of the source file.
     *
     * @var array
     */
    protected $coverage;

    /**
     * Absolute path.
     *
     * @var string
     */
    protected $path;

    /**
     * Line number of the source file.
     *
     * @var integer
     */
    protected $fileLines;

    /**
     * Constructor.
     *
     * @param string $path Absolute path.
     * @param string $name Source filename.
     * @param string $eol  End of line.
     */
    public function __construct($path, $name, $eol = "\n")
    {
        $this->path   = $path;
        $this->name   = $name;
        $this->source = file_get_contents($path);

        $lines = explode($eol, $this->source);
        $this->fileLines = count($lines);
        $this->coverage = array_fill(0, $this->fileLines, null);
    }

    /**
     * {@inheritdoc}
     *
     * @see \Contrib\Component\Service\Coveralls\Entity\ArrayConvertable::toArray()
     */
    public function toArray()
    {
        return array(
            'name'     => $this->name,
            'source'   => $this->source,
            'coverage' => $this->coverage,
        );
    }

    // API

    /**
     * Add coverage.
     *
     * @param integer $lineNum Line number.
     * @param integer $count   Number of covered.
     * @return void
     */
    public function addCoverage($lineNum, $count)
    {
        $this->coverage[$lineNum] = $count;
    }

    // accessor

    /**
     * Return source filename.
     *
     * @return string
     */
    public function getName()
    {
        return $this->name;
    }

    /**
     * Return source content.
     *
     * @return string
     */
    public function getSource()
    {
        return $this->source;
    }

    /**
     * Return coverage data of the source file.
     *
     * @return array
     */
    public function getCoverage()
    {
        return $this->coverage;
    }

    /**
     * Return absolute path.
     *
     * @return string
     */
    public function getPath()
    {
        return $this->path;
    }

    /**
     * Return line number of the source file.
     *
     * @return integer
     */
    public function getFileLines()
    {
        return $this->fileLines;
    }
}
