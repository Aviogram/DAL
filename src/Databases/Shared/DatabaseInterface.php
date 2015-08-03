<?php
namespace Aviogram\DAL\Databases\Shared;

use Aviogram\DAL\Meta\Meta;
use Aviogram\DAL\Options;
use Aviogram\DAL\Schema\SchemaManager;

interface DatabaseInterface
{
    /**
     * Initialize the database
     *
     * @param Options $defaultOptions
     */
    public function __construct(Options $defaultOptions);

    /**
     * @return Options
     */
    public function getOptions();

    /**
     * @return string
     */
    public function getName();

    /**
     * Gets a new connection for the given options
     *
     * @param Options|NULL $options When no options are given the default configuration will be used
     *
     * @return ConnectionInterface
     */
    public function getConnection(Options $options = null);

    /**
     * Get a singleton connection for the given connection details. When no connection has been made, a new one will be established.
     *
     * @param Options|null $options When no options are given the default configuration will be used
     *
     * @return ConnectionInterface
     */
    public function getConnectionSingleton(Options $options = null);

    /**
     * @return Meta
     */
    public function getMetaData();

    /**
     * @return SchemaManager
     */
    public function getSchemaManager();
}
