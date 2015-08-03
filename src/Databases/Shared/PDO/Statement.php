<?php
namespace Aviogram\DAL\Databases\Shared\PDO;

use Aviogram\DAL\Databases\Shared\Error;
use Aviogram\DAL\Databases\Shared\StatementInterface;
use Closure;
use PDOException;
use PDOStatement;

class Statement implements StatementInterface
{
    /**
     * @var PDOStatement
     */
    protected $statement;

    /**
     * Required constructor for PDO
     *
     * @param PDOStatement $statement
     */
    public function __construct(PDOStatement $statement)
    {
        $this->statement = $statement;
    }

    /**
     * Set the fetch mode for this statement
     *
     * @param  integer $fetchMode Use static::FETCH_*
     *
     * @return bool
     * @throws Exception\PDO
     */
    public function setFetchMode($fetchMode)
    {
        try {
            return $this->statement->setFetchMode($fetchMode);
        } catch (PDOException $e) {
            throw new Exception\PDO($e->getMessage(), $e->getCode(), $e);
        }
    }

    /**
     * Binds a value to the identifier
     *
     * @param string  $identifier
     * @param mixed   $value
     * @param integer $type The data type you want to bind to, static::PARAM_* constants
     *
     * @return bool
     * @throws Exception\PDO
     */
    public function bindValue($identifier, $value, $type = self::PARAM_STR)
    {
        try {
            return $this->statement->bindValue($identifier, $value, $type);
        } catch (PDOException $e) {
            throw new Exception\PDO($e->getMessage(), $e->getCode(), $e);
        }
    }

    /**
     * Binds a parameter by reference to the identifier
     *
     * @param string   $identifier
     * @param mixed    $variable
     * @param int|null $type The data type you want to bind to, static::PARAM_* constants
     *
     * @return bool
     * @throws Exception\PDO
     */
    public function bindParam($identifier, &$variable, $type = self::PARAM_STR)
    {
        try {
            return $this->statement->bindParam($identifier, $variable, $type, null, null);
        } catch (PDOException $e) {
            throw new Exception\PDO($e->getMessage(), $e->getCode(), $e);
        }
    }

    /**
     * Execute the statement
     *
     * @param array $params A list of values that need to be bound to the parameters of the query
     *
     * @return bool
     * @throws Exception\PDO
     */
    public function execute(array $params = null)
    {
        try {
            return $this->statement->execute($params);
        } catch (PDOException $e) {
            throw new Exception\PDO($e->getMessage(), $e->getCode(), $e);
        }
    }

    /**
     * Get the next row of the resultset
     *
     * @param integer|null $fetchMode Override the default fetch mode for this iteration
     *
     * @return mixed Returns FALSE when the iteration fails
     * @throws Exception\PDO
     */
    public function fetch($fetchMode = null)
    {
        try {
            return $this->statement->fetch($fetchMode);
        } catch (PDOException $e) {
            throw new Exception\PDO($e->getMessage(), $e->getCode(), $e);
        }
    }

    /**
     * Return a list of all the rows in the resultset
     *
     * @param  integer|null $fetchMode Override the default fetch mode for this iteration
     *
     * @return array
     * @throws Exception\PDO
     */
    public function fetchAll($fetchMode = null)
    {
        try {
            return $this->statement->fetchAll($fetchMode);
        } catch (PDOException $e) {
            throw new Exception\PDO($e->getMessage(), $e->getCode(), $e);
        }
    }

    /**
     * Return a single column value from the next row of the resultset
     *
     * @param int $index The column order is 0 indexed
     *
     * @return mixed Returns FALSE when the iteration fails
     * @throws Exception\PDO
     */
    public function fetchColumn($index = 0)
    {
        try {
            return $this->statement->fetchColumn($index);
        } catch (PDOException $e) {
            throw new Exception\PDO($e->getMessage(), $e->getCode(), $e);
        }
    }

    /**
     * Return the last error occurred
     *
     * @return Error | NULL when no error occurred
     */
    public function error()
    {
        if ($this->statement->errorCode() === null) {
            return null;
        }

        return new Error($this->statement->errorCode(), $this->statement->errorInfo());
    }

    /**
     * Return the next row of the resultset and apply the transform closure on the row before returning it
     *
     * @param Closure      $transform
     * @param integer|null $fetchMode
     *
     * @return mixed
     */
    public function fetchTransform(Closure $transform, $fetchMode = null)
    {
        return $transform($this->fetch($fetchMode));
    }

    /**
     * Return a list of all the rows in the resultset and apply the transform closure on every row before returning it
     * You can return static::STOP_FETCH_ALL_TRANSFORM when you need to terminate the iteration.
     *
     * @param Closure      $transform
     * @param integer|null $fetchMode
     *
     * @return array
     */
    public function fetchAllTransform(Closure $transform, $fetchMode = null)
    {
        $rows = array();

        while (($result = $this->fetch($fetchMode))) {
            $rows[] = $row = $transform($result);

            if ($row === static::STOP_FETCH_ALL_TRANSFORM) {
                break;
            }
        }

        return $rows;
    }
}
