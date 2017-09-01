<?php

namespace PhpCoveralls\Bundle\CoverallsBundle\Entity;

/**
 * Data for Coveralls API.
 *
 * @author Kitamura Satoshi <with.no.parachute@gmail.com>
 */
abstract class Coveralls implements ArrayConvertable
{
    /**
     * String expression (convert to json).
     *
     * @return string
     */
    public function __toString()
    {
        return json_encode($this->toArray());
    }
}
