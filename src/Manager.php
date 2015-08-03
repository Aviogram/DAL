<?php
namespace Aviogram\DAL;

use Aviogram\DAL\Connections\ConnectionInterface;

class Manager
{
    const CONNECTION_DEFAULT = 'standard';

    /**
     * A list of available connection adapters
     *
     * @var array
     */
    private $databases = array(
        'mysql' => 'Aviogram\DAL\Databases\MySQL\Database',
    );

    /**
     * A list of connection connections
     *
     * @var array
     */
    private $connections = array(
        'standard' => 'Aviogram\DAL\Connections\Standard',
    );

    /**
     * Create a new connection
     *
     * @param Options $options        The options for the adapter
     * @param string  $connectionName The name of the connection you want to use
     *
     * @return ConnectionInterface
     * @throws Exception\Manager
     */
    public function createConnection(Options $options, $connectionName = self::CONNECTION_DEFAULT)
    {
        if (array_key_exists($options->getDatabase(), $this->databases) === false) {
            throw Exception\Manager::unknownAdapter($options->getDatabaseName());
        }

        if (array_key_exists($connectionName, $this->connections) === false) {
            throw Exception\Manager::unknownConnection($connectionName);
        }

        // Get the class names
        $databaseClass   = $this->databases[$options->getDatabase()];
        $connectionClass = $this->connections[$connectionName];

        // Create the adapter for this connection
        $database      = new $databaseClass($options);

        // Return the new connection
        return new $connectionClass($database);
    }

    /**
     * Register a connection connection with the manager
     *
     * @param string $name  The name/alias of the connection
     * @param string $class The classname of the connection
     *
     * @return $this
     * @throws Exception\Manager
     */
    public function registerConnection($name, $class)
    {
        $interface = 'Aviogram\DAL\Connections\ConnectionInterface';

        // Check if the name is not already taken
        if (array_key_exists($name, $this->connections) === true) {
            throw Exception\Manager::connectionExists($name);
        }

        // Check if the class even exists
        if (class_exists($class) === false) {
            throw Exception\Manager::connectionClassNotFound($name, $class);
        }

        // Check if the class implements the required interface
        if (is_subclass_of($class, $interface) === false) {
            throw Exception\Manager::invalidConnectionClass($name, $class, $interface);
        }

        // Register the connection
        $this->connections[$name] = $class;

        // Return the manager
        return $this;
    }
}
