<?php
namespace Aviogram\DAL\Meta\Exception;

use Aviogram\DAL\Exception\Exception;

class Type extends Exception
{
    /**
     * @param  string $name
     *
     * @return Type
     */
    public static function doesNotExists($name)
    {
        return new self("Type '{$name}' is not defined.");
    }

    /**
     * @param string $type
     * @param mixed  $value
     *
     * @return Type
     */
    public static function castToPHPFailed($type, $value)
    {
        return new self("Could not cast value: " . var_export($value, true) . " for type '{$type}' to PHP value.");
    }

    /**
     * @param string $type
     * @param mixed  $value
     *
     * @return Type
     */
    public static function castToDatabaseFailed($type, $value)
    {
        return new self("Could not cast value: " . var_export($value, true) . " for type '{$type}' to DB value.");
    }
}
