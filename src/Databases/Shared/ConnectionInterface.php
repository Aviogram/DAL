<?php
namespace Aviogram\DAL\Databases\Shared;

interface ConnectionInterface
{
    /**
     * Get the server version
     *
     * @return string
     */
    public function getServerVersion();

    /**
     * Prepares a SQL Query for execution
     *
     * @param string $sql
     *
     * @return StatementInterface
     */
    public function prepare($sql);

    /**
     * Execute a SQL statement and return a DAL statement
     *
     * @param string $sql
     *
     * @return StatementInterface
     */
    public function query($sql);

    /**
     * Quote a string for a query
     *
     * @param string $input
     *
     * @return string
     */
    public function quote($input);

    /**
     * Executes an SQL Query and return the rows affected
     *
     * @param string $sql
     *
     * @return integer
     */
    public function exec($sql);

    /**
     * Return the last ID inserted of an auto increment column
     *
     * @return integer
     */
    public function lastInsertId();

    /**
     * Start a new transaction
     *
     * @return boolean
     */
    public function beginTransaction();

    /**
     * Commit a transaction that has been started with startTransaction
     *
     * @return boolean
     */
    public function commit();

    /**
     * Roll back a transaction that has been started with startTransaction
     *
     * @return boolean
     */
    public function rollBack();

    /**
     * Return the last error on the connection
     *
     * @return Error | NULL when no error occurred
     */
    public function error();

    /**
     * Pings the servers and returns none fatal boolean TRUE or FALSE
     *
     * @return boolean
     */
    public function ping();

    /**
     * Disconnect the connection
     *
     * @return boolean
     */
    public function disconnect();
}
