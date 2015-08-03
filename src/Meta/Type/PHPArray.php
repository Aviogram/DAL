<?php
namespace Aviogram\DAL\Meta\Type;

class PHPArray extends AbstractType
{
    /**
     * @param mixed $value
     *
     * @return mixed
     */
    public function castToDatabaseValue($value)
    {
        if ($value === null) {
            return null;
        }

        return serialize($value);
    }

    /**
     * @param  mixed $value
     *
     * @return mixed
     */
    public function castToPHPValue($value)
    {
        if ($value === null) {
            return null;
        }

        return unserialize($value);
    }
}
