<?php
namespace Aviogram\DAL\Databases\Shared\Exception;

abstract class AbstractDriver extends Driver
{
    /**
     * Specific error code of the PDO connection
     *
     * @var integer|String
     */
    protected $errorCode;

    /**
     * The SQLState of the PDO connection
     *
     * @var string
     */
    protected $sqlState;

    /**
     * @return int|String
     */
    public function getErrorCode()
    {
        return $this->errorCode;
    }

    /**
     * @return string
     */
    public function getSqlState()
    {
        return $this->sqlState;
    }
}
