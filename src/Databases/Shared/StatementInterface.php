<?php
namespace Aviogram\DAL\Databases\Shared;

interface StatementInterface extends ResultStatementInterface
{
    /**
     * Binds a value to the identifier
     *
     * @param string    $identifier
     * @param mixed     $value
     * @param integer   $type         The data type you want to bind to, static::PARAM_* constants
     *
     * @return boolean
     */
    public function bindValue($identifier, $value, $type = self::PARAM_STR);

    /**
     * Binds a parameter by reference to the identifier
     *
     * @param string   $identifier
     * @param mixed    $variable
     * @param integer  $type         The data type you want to bind to, static::PARAM_* constants
     *
     * @return boolean
     */
    public function bindParam($identifier, &$variable, $type = null);

    /**
     * Execute the statement
     *
     * @param array $params A list of values that need to be bound to the parameters of the query
     *
     * @return boolean
     */
    public function execute(array $params = null);

    /**
     * Return the last error occurred
     *
     * @return Error | NULL when no error occurred
     */
    public function error();
}
