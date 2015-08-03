<?php
namespace Aviogram\DAL\Schema;

/**
 * @method Column current()
 * @method Column offsetGet($offset)
 */
class Columns extends AbstractElements
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
        return ($value instanceof Column);
    }
}
