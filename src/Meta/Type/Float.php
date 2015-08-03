<?php
namespace Aviogram\DAL\Meta\Type;

class Float extends AbstractType
{
    /**
     * @param  mixed $value
     *
     * @return mixed
     */
    public function castToPHPValue($value)
    {
        return (float) $value;
    }
}
