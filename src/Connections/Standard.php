<?php
namespace Aviogram\DAL\Connections;

use Aviogram\DAL\Databases\Shared\ConstantInterface;
use Aviogram\DAL\Databases\Shared\DatabaseInterface;
use Aviogram\DAL\Databases\Shared\StatementInterface;
use Aviogram\DAL\Exception\Connection;
use Closure;

class Standard implements ConnectionInterface, ConstantInterface
{
    /**
     * @var DatabaseInterface
     */
    protected $database;

    /**
     * @var \Aviogram\DAL\Databases\Shared\ConnectionInterface
     */
    protected $connection;

    /**
     * @var integer
     */
    protected $transactions = 0;

    /**
     * Initialize a new connection
     *
     * @param DatabaseInterface $database
     */
    public function __construct(DatabaseInterface $database)
    {
        $this->database = $database;
    }

    /**
     * @return DatabaseInterface
     */
    public function getDatabase()
    {
        return $this->database;
    }

    /**
     * Returns a active connection. If no connection is established a connection will be made
     *
     * @return \Aviogram\DAL\Databases\Shared\ConnectionInterface
     */
    public function getActiveConnection()
    {
        $this->connect();

        return $this->connection;
    }

    /**
     * Establish the connection with the database
     *
     * @return boolean
     */
    public function connect()
    {
        if ($this->connection !== null) {
            return false;
        }

        $this->connection = $this->getDatabase()->getConnection();

        return true;
    }

    /**
     * Ping the server
     *
     * @return boolean FALSE on connection errors | TRUE connection established
     */
    public function ping()
    {
        return $this->getActiveConnection()->ping();
    }

    /**
     * Disconnect the connection with the database
     *
     * @return boolean
     */
    public function disconnect()
    {
        if ($this->connection === null) {
            return false;
        }

        $this->connection->disconnect();
        $this->connection = null;

        return true;
    }

    /**
     * Start a new transaction
     *
     * @return boolean
     */
    public function beginTransaction()
    {
        $this->connect();

        $this->transactions++;

        if ($this->transactions === 1) {
            $this->getActiveConnection()->beginTransaction();
        } else {
            $this->createSavepoint("DAL{$this->transactions}");
        }
    }

    /**
     * Commit the current transaction. Any savepoints during this transaction will be removed as well
     *
     * @return bool
     * @throws Connection
     */
    public function commit()
    {
        if ($this->transactions === 0) {
            throw Connection::noActiveTransactions();
        }

        if ($this->transactions === 1) {
            $this->getActiveConnection()->commit();
        } else {
            $this->releaseSavepoint("DAL{$this->transactions}");
        }

        $this->transactions--;
    }

    /**
     * Revert all database changes done during the transaction. Any savepoints created will be removed as well
     *
     * @return boolean
     * @throws Connection
     */
    public function rollBack()
    {
        if ($this->transactions === 0) {
            throw Connection::noActiveTransactions();
        }

        if ($this->transactions === 1) {
            $this->getActiveConnection()->rollBack();
        } else {
            $this->rollbackToSavepoint("DAL{$this->transactions}");
        }

        $this->transactions--;
    }

    /**
     * Create a new savepoint
     *
     * @param string $name
     *
     * @return boolean
     */
    public function createSavepoint($name)
    {
        throw Connection::savepointsNotSupported();
    }

    /**
     * Release the given savepoint
     *
     * @param string $name
     *
     * @return boolean
     */
    public function releaseSavepoint($name)
    {
        throw Connection::savepointsNotSupported();
    }

    /**
     * Rolls back to the given savepoint
     *
     * @param string $name
     *
     * @return mixed
     */
    public function rollbackToSavepoint($name)
    {
        throw Connection::savepointsNotSupported();
    }

    /**
     * Prepares and executes a SQL query and returns associative array
     *
     * @param string $sql
     * @param array  $params
     * @param array  $types
     *
     * @return array
     */
    public function fetchAssoc($sql, array $params = array(), array $types = array())
    {
        return $this->query($sql, $params, $types)->fetch(static::FETCH_ASSOC);
    }

    /**
     * Prepares and executes a SQL query and returns an associative array
     *
     * @param string  $sql
     * @param array   $params
     * @param array   $types
     * @param Closure $transform Will call the closure every iteration and transform the result
     *
     * @return array
     */
    public function fetchAll($sql, array $params = array(), array $types = array(), Closure $transform = null)
    {
        return $this->query($sql, $params, $types)->fetchAll();
    }

    /**
     * Prepares and executes a SQL query and returns numeric array
     *
     * @param string $sql
     * @param array  $params
     * @param array  $types
     *
     * @return array
     */
    public function fetchArray($sql, array $params = array(), array $types = array())
    {
        return $this->query($sql, $params, $types)->fetch(static::FETCH_NUM);
    }

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
    public function fetchColumn($sql, array $params = array(), $index = 0, array $types = array())
    {
        return $this->query($sql, $params, $types)->fetchColumn($index);
    }

    /**
     * Executes a SQL Query
     *
     * @param string $sql
     * @param array  $params
     * @param array  $types
     *
     * @return StatementInterface
     */
    public function query($sql, array $params = array(), array $types = array())
    {
        return $this->getActiveConnection()->query($sql);
    }

    /**
     * @param  string $sql
     *
     * @return StatementInterface
     */
    public function prepare($sql)
    {
        return $this->getActiveConnection()->prepare($sql);
    }

    /**
     * Executes an prepared statement
     *
     * @param string $sql
     *
     * @return integer  The affected rows
     */
    public function exec($sql)
    {
        return $this->getActiveConnection()->exec($sql);
    }

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
    public function fetchTransform(
                $sql,
        Closure $transform,
        array   $params = array(),
        array   $types  = array()
    ) {
        return $this->getActiveConnection()->query($sql, $params, $types)->fetchTransform($transform);
    }

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
    public function fetchAllTransform(
        $sql,
        Closure $transform,
        array   $params     = array(),
        array   $types      = array()
    ) {
        return $this->getActiveConnection()->query($sql)->fetchAllTransform($transform);
    }
}
