<?php
namespace Aviogram\DAL\Databases\Shared\PDO;

use Aviogram\DAL\Databases\Shared\ConnectionInterface;
use Aviogram\DAL\Databases\Shared\Error;
use Aviogram\DAL\Databases\Shared\StatementInterface;
use PDO;
use PDOException;

class Connection implements ConnectionInterface
{
    /**
     * @var PDO
     */
    protected $pdo;

    /**
     * @param string $dsn
     * @param null   $username
     * @param null   $password
     * @param array  $options
     *
     * @throws Exception\PDO
     */
    public function __construct($dsn, $username = null, $password = null, array $options = array())
    {
        try {
            $this->pdo = new PDO($dsn, $username, $password, $options);
            $this->pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
        } catch (PDOException $e) {
            throw new Exception\PDO($e->getMessage(), $e->getCode(), $e);
        }
    }

    /**
     * Prepares a statement for execution
     *
     * @param string $sql
     *
     * @return \Aviogram\DAL\Databases\Shared\StatementInterface
     * @throws Exception\PDO
     */
    public function prepare($sql)
    {
        try {
            return new Statement($this->pdo->prepare($sql));
        } catch (PDOException $e) {
            throw new Exception\PDO($e->getMessage(), $e->getCode(), $e);
        }
    }

    /**
     * Execute a SQL statement and return a DAL statement
     *
     * @param string $sql
     *
     * @return StatementInterface
     * @throws Exception\PDO
     */
    public function query($sql)
    {
        try {
            return new Statement($this->pdo->query($sql));
        } catch (PDOException $e) {
            throw new Exception\PDO($e->getMessage(), $e->getCode(), $e);
        }
    }

    /**
     * Quote a string for a query
     *
     * @param string $input
     *
     * @return string
     */
    public function quote($input)
    {
        return $this->pdo->quote($input);
    }

    /**
     * Execute a DAL statement and return the rows affected
     *
     * @param $statement
     *
     * @return int
     * @throws Exception\PDO
     */
    public function exec($statement)
    {
        try {
            return $this->pdo->exec($statement);
        } catch (PDOException $e) {
            throw new Exception\PDO($e->getMessage(), $e->getCode(), $e);
        }
    }

    /**
     * Return the last error on the connection
     *
     * @return Error | NULL when no error occured
     */
    public function error()
    {
        if ($this->pdo->errorCode() === null) {
            return null;
        }

        return new Error($this->pdo->errorCode(), $this->pdo->errorInfo());
    }

    /**
     * Pings the servers and returns none fatal boolean TRUE or FALSE
     *
     * @return boolean
     */
    public function ping()
    {
        try {
            $this->pdo->query('SELECT 1');
        } catch (PDOException $e) {
            return false;
        }

        return true;
    }

    /**
     * Disconnect the connection
     *
     * @return boolean
     */
    public function disconnect()
    {
        $this->pdo = null;

        return true;
    }

    /**
     * Return the last ID inserted of an auto increment column
     *
     * @return integer
     */
    public function lastInsertId()
    {
        return $this->pdo->lastInsertId();
    }

    /**
     * Start a new transaction
     *
     * @return boolean
     */
    public function beginTransaction()
    {
        return $this->pdo->beginTransaction();
    }

    /**
     * Commit a transaction that has been started with startTransaction
     *
     * @return boolean
     */
    public function commit()
    {
        return $this->pdo->commit();
    }

    /**
     * Roll back a transaction that has been started with startTransaction
     *
     * @return boolean
     */
    public function rollBack()
    {
        return $this->pdo->rollBack();
    }

    /**
     * Get the server version
     *
     * @return string
     */
    public function getServerVersion()
    {
        return $this->pdo->getAttribute(PDO::ATTR_SERVER_VERSION);
    }
}
