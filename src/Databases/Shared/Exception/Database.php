<?php
namespace Aviogram\DAL\Databases\Shared\Exception;

use Aviogram\DAL\Exception\Exception;

class Database extends Exception
{
    /**
     * @param string $name
     *
     * @return Database
     */
    public static function unknownDriver($name)
    {
        return new self("The driver '{$name}' is not known by the database.");
    }

    /**
     * @param string $name
     * @param string $class
     * @param string $interface
     *
     * @return Database
     */
    public static function invalidClassDriver($name, $class, $interface)
    {
        return new self("The driver '{$name}' with class '{$class}' does not implement the required interface '{$interface}'.");
    }

    /**
     * @param string $name
     * @param string $class
     *
     * @return Database
     */
    public static function driverClassNotFound($name, $class)
    {
        return new self("The driver '{$name}' with the class '{$class}' cannot be found.");
    }
    /**
     * @param string $name
     *
     * @return Database
     */
    public static function driverExists($name)
    {
        return new self("A driver with the name '{$name}' already exists.");
    }

    /**
     * @param  string $name
     *
     * @return Database
     */
    public static function driverAutodetectFailed($name)
    {
        return new self("There are no available drivers for database '{$name}'.");
    }

    /**
     * @param  string $name
     *
     * @return Database
     */
    public static function invalidMetaSupportClass($name)
    {
        return new self("The database '{$name}' has an invalid meta support class defined.");
    }
}
