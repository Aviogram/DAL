<?php
namespace Aviogram\DAL\Databases\Shared;

use Aviogram\DAL\Meta\Meta;
use Aviogram\DAL\Meta\Type;
use Aviogram\DAL\Options;
use Aviogram\DAL\Schema\SchemaManager;
use Aviogram\DAL\Schema\SchemaBuilderInterface;

abstract class AbstractDatabase implements DatabaseInterface
{
    /**
     * @var Options
     */
    protected $options;

    /**
     * @var Meta
     */
    protected $meta;

    /**
     * @var SchemaManager
     */
    protected $schema;

    /**
     * @var ConnectionInterface[]
     */
    protected $singletons = array();

    /**
     * @var array
     */
    private $drivers = array();

    /**
     * Initialize the database
     *
     * @param Options $defaultOptions
     *
     * @throws Exception\Database
     */
    public function __construct(Options $defaultOptions)
    {
        $this->options = $defaultOptions;

        foreach ($this->getDrivers() as $alias => $class) {
            $this->registerDriver($alias, $class);
        }
    }

    /**
     * @return Options
     */
    public function getOptions()
    {
        return $this->options;
    }

    /**
     * Get the Meta data for the database
     *
     * @return Meta
     * @throws Exception\Database
     */
    public function getMetaData()
    {
        if ($this->meta === null) {
            $supportClass = $this->getMetaSupportClass();

            if (is_subclass_of($supportClass, 'Aviogram\DAL\Meta\SupportInterface') === false) {
                throw Exception\Database::invalidMetaSupportClass($this->getName());
            }

            $this->meta = new Meta($this, new $supportClass, new Type($this, $this->getTypeMapping()));
        }

        return $this->meta;
    }

    /**
     * @return SchemaManager
     * @throws Exception\Database
     */
    public function getSchemaManager()
    {
        if ($this->schema === null) {
            $schemaBuilderClass = $this->getSchemaBuilderClass();

            if (is_subclass_of($schemaBuilderClass, 'Aviogram\DAL\Schema\SchemaBuilderInterface') === false) {
                throw Exception\Database::invalidMetaSupportClass($this->getName());
            }

            $this->schema = new SchemaManager($this, new $schemaBuilderClass($this));
        }

        return $this->schema;
    }

    /**
     * Gets a new connection for the given options
     *
     * @param Options $options
     *
     * @return ConnectionInterface
     */
    public function getConnection(Options $options = null)
    {
        // Get the set of options
        $options = $options ?: $this->getOptions();

        // Get the driver to use
        $driver  = $options->getDatabaseDriver() ?: $this->getOptions()->getDatabaseDriver() ?: $this->autodetectDriver();

        // Create a connection via the database driver
        $connection =  $this->getDriver($driver)->connect($options);

        // Perform user defined queries. Such as timezone, charset settings
        foreach ($options->getQueries() as $query) {
            $connection->exec($query);
        }

        // Return the connection
        return $connection;
    }

    /**
     * Get a singleton connection for the given connection details. When no connection has been made, a new one will be established.
     *
     * @param Options|null $options When no options are given the default configuration will be used
     *
     * @return ConnectionInterface
     */
    public function getConnectionSingleton(Options $options = null)
    {
        // Get the set of options
        $options = $options ?: $this->getOptions();

        // Use the options object as the unique identifier
        $hash    = spl_object_hash($options);

        // Check if the connection is already been made
        if (array_key_exists($hash, $this->singletons) === false) {
            $this->singletons[$hash] = $this->getConnection($options);
        }

        // Return the singleton
        return $this->singletons[$hash];
    }

    /**
     * Automatic driver detection
     *
     * @return string
     * @throws Exception\Database
     */
    protected function autodetectDriver()
    {
        foreach ($this->drivers as $driver => $class) {
            if ($this->getDriver($driver)->isAvailable() === true) {
                return $driver;
            }
        }

        throw Exception\Database::driverAutodetectFailed($this->getName());
    }

    /**
     * @return string
     */
    protected function getMetaSupportClass()
    {
        return 'Aviogram\DAL\Meta\Support';
    }

    /**
     * @return SchemaBuilderInterface
     */
    abstract protected function getSchemaBuilderClass();

    /**
     * Return a list of drivers <alias> => <driverClass>
     *
     * @return array
     */
    abstract protected function getDrivers();

    /**
     * Returns a type mapping for mapping database values to PHP values and visa versa
     *
     * @return array array('<db_type>' => Type::TYPE_*);
     */
    abstract protected function getTypeMapping();

    /**
     * Get a new instance of a driver
     *
     * @param  string $name
     *
     * @return DriverInterface
     * @throws Exception\Database
     */
    protected function getDriver($name)
    {
        // check if the driver exists
        if (array_key_exists($name, $this->drivers) === false) {
            throw Exception\Driver::unknownDriver($name);
        }

        $class = $this->drivers[$name];
        return new $class();
    }

    /**
     * Register a new driver with the Database
     *
     * @param string $name  The name/alias of the driver
     * @param string $class The class name of the driver (Full class name required)
     *
     * @return $this
     * @throws Exception\Database
     */
    public function registerDriver($name, $class)
    {
        $interface = 'Aviogram\DAL\Databases\Shared\DriverInterface';

        // Check if the name is not already taken
        if (array_key_exists($name, $this->drivers) === true) {
            throw Exception\Driver::driverExists($name);
        }

        // Check if the class even exists
        if (class_exists($class) === false) {
            throw Exception\Driver::driverClassNotFound($name, $class);
        }

        // Check if the class implements the required interface
        if (is_subclass_of($class, $interface) === false) {
            throw Exception\Driver::invalidClassDriver($name, $class, $interface);
        }

        // Register the driver
        $this->drivers[$name] = $class;

        // Return the platform instance
        return $this;
    }
}
