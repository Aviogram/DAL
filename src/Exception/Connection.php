<?php
namespace Aviogram\DAL\Exception;

class Connection extends Exception
{
    /**
     * @return Connection
     */
    public static function noActiveTransactions()
    {
        return new self("There are no transactions started on this connection.");
    }
}
