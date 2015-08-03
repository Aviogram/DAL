<?php
namespace Aviogram\DAL\Databases\Shared;

use Aviogram\DAL\Options;

interface DriverInterface
{
    /**
     * @param Options $options
     *
     * @return ConnectionInterface
     */
    public function connect(Options $options);

    /**
     * Checks if the driver is available on the system
     *
     * @return boolean
     */
    public function isAvailable();
}
