<?php
namespace Aviogram\DAL\Schema;

/**
 * @method Table current()
 * @method Table offsetGet($offset)
 */
class Tables extends AbstractElements
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
        return ($value instanceof Table);
    }
}
