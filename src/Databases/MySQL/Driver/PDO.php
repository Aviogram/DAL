<?php
namespace Aviogram\DAL\Databases\MySQL\Driver;

use Aviogram\DAL\Databases\Shared\Exception\Driver;
use Aviogram\DAL\Databases\Shared\ConnectionInterface;
use Aviogram\DAL\Databases\Shared\DriverInterface;
use Aviogram\DAL\Databases\Shared\PDO\Connection;
use Aviogram\DAL\Options;

class PDO implements DriverInterface
{
    /**
     * @param Options $options
     *
     * @return ConnectionInterface
     * @throws \Aviogram\DAL\Exception\Exception
     */
    public function connect(Options $options)
    {
        try {
            $extraOptions = $options->getExtraOptions();
            $pdoOptions   = array();
            if (array_key_exists('pdo', $extraOptions) === true) {
                $pdoOptions = $extraOptions['pdo'];
            }

            return new Connection(
                $this->getDSN($options),
                $options->getUsername(),
                $options->getPassword(),
                $pdoOptions
            );
        } catch (\Aviogram\DAL\Databases\Shared\PDO\Exception\PDO $e) {
            throw Driver::adapterException($this, $e);
        }
    }

    /**
     * Get the DSN representation of the options given
     *
     * @param Options $options
     *
     * @return string
     */
    protected function getDSN(Options $options)
    {
        $extra    = $options->getExtraOptions();
        $dsnParts = array();
        $dsnParts[] = "host={$options->getHost()}";
        $dsnParts[] = "port={$options->getPort()}";

        if ($options->getDatabaseName() !== null) {
            $dsnParts[] = "dbname={$options->getDatabaseName()}";
        }

        if (array_key_exists('unix_socket', $extra) === true) {
            $dsnParts[] = "unix_socket={$extra['unix_socket']}";
        }

        if (array_key_exists('charset', $extra) === true) {
            $dsnParts[] = "charset={$extra['charset']}";
        }

        return 'mysql:' . implode(';', $dsnParts);
    }

    /**
     * Checks if the driver is available on the system
     *
     * @return boolean
     */
    public function isAvailable()
    {
        if (extension_loaded('PDO') === false) {
            return false;
        }

        return in_array('mysql', \PDO::getAvailableDrivers());
    }
}
