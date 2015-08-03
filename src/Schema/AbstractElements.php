<?php
namespace Aviogram\DAL\Schema;

use Aviogram\Common\AbstractCollection;

/**
 * @method AbstractElement current()
 * @method AbstractElement offsetGet($offset)
 */
abstract class AbstractElements extends AbstractCollection
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
        return ($value instanceof AbstractElement);
    }

    /**
     * Get an unique hash for the current collection
     *
     * @return string
     */
    public function getIdentifier()
    {
        $hash = array();

        foreach ($this as $element) {
            $hash[] = $element->getName();
        }

        return md5(serialize($hash));
    }
}
