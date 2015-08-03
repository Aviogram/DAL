<?php
namespace Aviogram\DAL\Databases\Shared\Exception;

use Aviogram\DAL\Databases\Shared\Exception\Database;
use Aviogram\DAL\Databases\Shared\DriverInterface;

class Driver extends Database
{
    /**
     * @param DriverInterface $driver
     * @param \Exception      $exception
     *
     * @return Driver
     */
    public static function adapterException(DriverInterface $driver, \Exception $exception)
    {
        $message = "An exception occurred in the driver: {$exception->getMessage()}";

        return new self($message, 0, $exception);
    }
}
