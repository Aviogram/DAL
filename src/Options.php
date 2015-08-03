<?php
namespace Aviogram\DAL;

class Options
{
    /**
     * @var string
     */
    protected $host;

    /**
     * @var integer
     */
    protected $port;

    /**
     * @var string
     */
    protected $username;

    /**
     * @var string
     */
    protected $password;

    /**
     * @var string
     */
    protected $databaseName;

    /**
     * @var array
     */
    protected $queries;

    /**
     * @var array
     */
    protected $extraOptions;

    /**
     * @var string
     */
    protected $database;

    /**
     * @var null|string
     */
    protected $databaseDriver;

    /**
     * Create connection options
     *
     * @param string      $database         The name of the database you want to use
     * @param string      $host             The hostname of the database
     * @param int         $port             The port of the database
     * @param string|null $username         The username of the database
     * @param string|null $password         The password of the database
     * @param string|null $databaseName     The name of the database
     * @param array       $queries          Queries that will be executed during establishing the connection
     * @param string|null $databaseDriver   Force the driver you want to use for the connection
     * @param array       $extraOptions     Extra options depends on the platform/driver that will be used
     */
    public function __construct(
              $database,
              $host,
              $port,
              $username       = null,
              $password       = null,
              $databaseName   = null,
        array $queries        = array(),
              $databaseDriver = null,
        array $extraOptions   = array()
    ) {
        $this->host           = $host;
        $this->port           = $port;
        $this->username       = $username;
        $this->password       = $password;
        $this->databaseName   = $databaseName;
        $this->queries        = $queries;
        $this->extraOptions   = $extraOptions;
        $this->database       = $database;
        $this->databaseDriver = $databaseDriver;
    }

    /**
     * @return string
     */
    public function getDatabase()
    {
        return $this->database;
    }

    /**
     * @return null|string
     */
    public function getDatabaseDriver()
    {
        return $this->databaseDriver;
    }

    /**
     * @return string
     */
    public function getHost()
    {
        return $this->host;
    }

    /**
     * @return int
     */
    public function getPort()
    {
        return $this->port;
    }

    /**
     * @return string
     */
    public function getUsername()
    {
        return $this->username;
    }

    /**
     * @return string
     */
    public function getPassword()
    {
        return $this->password;
    }

    /**
     * @return string
     */
    public function getDatabaseName()
    {
        return $this->databaseName;
    }

    /**
     * @return array
     */
    public function getQueries()
    {
        return $this->queries;
    }

    /**
     * @return array
     */
    public function getExtraOptions()
    {
        return $this->extraOptions;
    }
}
