<?php
namespace Aviogram\DAL\Databases\Shared\PDO\Exception;

use Aviogram\DAL\Databases\Shared\Exception\AbstractDriver;
use PDOException;

class PDO extends AbstractDriver
{
    /**
     * @param string     $message
     * @param null       $code
     * @param \Exception $previous
     */
    public function __construct($message, $code = null, \PDOException $previous = null)
    {
        parent::__construct($message, 0, $previous);

        $this->sqlState  = array_key_exists(0, $previous->errorInfo) ? $previous->errorInfo[0] : $previous->getCode();
        $this->errorCode = array_key_exists(1, $previous->errorInfo) ? $previous->errorInfo[1] : $previous->getCode();
    }
}
