<?php
namespace Aviogram\DAL\Schema;

/**
 * @method Index current()
 * @method Index offsetGet($offset)
 */
class Indexes extends AbstractElements
{
    /**
     * Determines of the value is a valid collection value
     *
     * @param  mixed $value
     *
     * @return boolean
     */
    protected function isValidValue($value)
    {
        return ($value instanceof Index);
    }
}
