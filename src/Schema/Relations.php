<?php
namespace Aviogram\DAL\Schema;

/**
 * @method Relation current()
 * @method Relation offsetGet($offset)
 */
class Relations extends AbstractElements
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
        return ($value instanceof Relation);
    }
}
