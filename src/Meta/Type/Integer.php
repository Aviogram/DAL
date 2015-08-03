<?php
namespace Aviogram\DAL\Meta\Type;

class Integer extends AbstractType
{
    /**
     * @param  mixed $value
     *
     * @return mixed
     */
    public function castToPHPValue($value)
    {
        return (integer) $value;
    }
}
