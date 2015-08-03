<?php
namespace Aviogram\DAL\Connections;

use Aviogram\DAL\Databases\Shared\DatabaseInterface;
use Aviogram\DAL\Databases\Shared\StatementInterface;
use Aviogram\DAL\Exception\Connection;
use Closure;

interface ConnectionInterface
{
    /**
     * Initialize a new connection
     *
     * @param DatabaseInterface $database
     */
    public function __construct(DatabaseInterface $database);

    /**
     * @return DatabaseInterface
     */
    public function getDatabase();

    /**
     * Returns a active connection. If no connection is established a connection will be made
     *
     * @return \Aviogram\DAL\Databases\Shared\ConnectionInterface
     */
    public function getActiveConnection();

    /**
     * Establish the connection with the database
     *
     * @return boolean
     */
    public function connect();

    /**
     * Ping the server
     *
     * @return boolean FALSE on connection errors | TRUE connection established
     */
    public function ping();

    /**
     * Disconnect the connection with the database
     *
     * @return boolean
     */
    public function disconnect();

    /**
     * Start a new transaction
     *
     * @return boolean
     */
    public function beginTransaction();

    /**
     * Commit the current transaction. Any savepoints during this transaction will be removed as well
     *
     * @return boolean
     * @throws Connection
     */
    public function commit();

    /**
     * Revert all database changes done during the transaction. Any savepoints created will be removed as well
     *
     * @return boolean
     * @throws Connection
     */
    public function rollback();

    /**
     * Create a new savepoint
     *
     * @param string $name
     *
     * @return boolean
     */
    public function createSavepoint($name);

    /**
     * Release the given savepoint
     *
     * @param string $name
     *
     * @return boolean
     */
    public function releaseSavepoint($name);

    /**
     * Rolls back to the given savepoint
     *
     * @param string $name
     *
     * @return mixed
     */
    public function rollbackToSavepoint($name);

    /**
     * Prepares and executes a SQL query and returns associative array
     *
     * @param string $sql
     * @param array  $params
     * @param array  $types
     *
     * @return array
     */
    public function fetchAssoc($sql, array $params = array(), array $types = array());

    /**
     * Prepares and executes a SQL query and returns an associative array
     *
     * @param string  $sql
     * @param array   $params
     * @param array   $types
     * @param Closure $transform  Will call the closure every iteration and transform the result
     *
     * @return array
     */
    public function fetchAll($sql, array $params = array(), array $types = array(), Closure $transform = null);

    /**
     * Prepares and executes a SQL query and returns numeric array
     *
     * @param string $sql
     * @param array  $params
     * @param array  $types
     *
     * @return array
     */
    public function fetchArray($sql, array $params = array(), array $types = array());

    /**
     * Prepares and executes a SQL query and returns the value of a single column
     *
     * @param string $sql
     * @param array  $params
     * @param int    $index
     * @param array  $types
     *
     * @return mixed
     */
    public function fetchColumn($sql, array $params = array(), $index = 0, array $types = array());

    /**
     * Prepares and executes a SQL query and returns a transformed row
     *
     * @param string  $sql
     * @param Closure $transform
     * @param array   $params
     * @param array   $types
     *
     * @return mixed
     */
    public function fetchTransform($sql, Closure $transform, array $params = array(), array $types = array());

    /**
     * Prepares and executes a SQL query and returns all transformed rows
     *
     * @param string  $sql
     * @param Closure $transform
     * @param array   $params
     * @param array   $types
     *
     * @return mixed
     */
    public function fetchAllTransform($sql, Closure $transform, array $params = array(), array $types = array());

    /**
     * Executes a SQL Query
     *
     * @param string $sql
     * @param array  $params
     * @param array  $types
     *
     * @return StatementInterface
     */
    public function query($sql, array $params = array(), array $types = array());

    /**
     * @param  string $sql
     *
     * @return StatementInterface
     */
    public function prepare($sql);

    /**
     * Executes an prepared statement
     *
     * @param string $sql
     *
     * @return integer  The affected rows
     */
    public function exec($sql);
}
