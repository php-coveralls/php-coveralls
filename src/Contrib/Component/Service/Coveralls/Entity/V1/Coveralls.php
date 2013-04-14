<?php
namespace Contrib\Component\Service\Coveralls\Entity\V1;

use Contrib\Component\Service\Coveralls\Entity\ArrayConvertable;

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
