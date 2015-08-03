<?php
namespace Aviogram\DAL\Exception;

class Manager extends Exception
{
    /**
     * @param string $name
     *
     * @return Manager
     */
    public static function unknownConnection($name)
    {
        return new self("The given connection '{$name}' is not known by the manager. Register it with registerConnection.");
    }

    /**
     * @param string $name
     * @param string $class
     * @param string $interface
     *
     * @return Manager
     */
    public static function invalidConnectionClass($name, $class, $interface)
    {
        return new self("The connection '{$name}' with class '{$class}' does not implement the required interface '{$interface}'.");
    }

    /**
     * @param string $name
     * @param string $class
     *
     * @return Manager
     */
    public static function connectionClassNotFound($name, $class)
    {
        return new self("The connection '{$name}' with the class '{$class}' cannot be found.");
    }

    /**
     * @param string $name
     *
     * @return Manager
     */
    public static function connectionExists($name)
    {
        return new self("An connection with the name '{$name}' already exists.");
    }
}
