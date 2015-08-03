<?php
namespace Aviogram\DAL\Meta\Type;

use Aviogram\DAL\Meta\Exception\Type;

class DefinedList extends AbstractType
{
    /**
     * @param mixed $value
     *
     * @return string|NULL
     * @throws Type
     */
    public function castToDatabaseValue($value)
    {
        if ($value === null) {
            return null;
        }

        if (in_array($value, $this->getList()) === false) {
            throw Type::castToDatabaseFailed(__CLASS__, $value);
        }

        return $value;
    }

    /**
     * @param  mixed $value
     *
     * @return string|NULL
     * @throws Type
     */
    public function castToPHPValue($value)
    {
        if ($value === null) {
            return null;
        }

        if (in_array($value, $this->getList()) === false) {
            throw Type::castToPHPFailed(__CLASS__, $value);
        }

        return $value;
    }

    /**
     * @return array
     */
    protected function getList()
    {
        return $this->getOption('list', array());
    }
}
