<?php
namespace Aviogram\DAL\Schema\Exception;

class Index extends Manager
{
    /**
     * @param integer $offset
     * @param string  $indexName
     *
     * @return Manager
     */
    public static function columnAlreadyExists($offset, $indexName)
    {
        return new self("Column on offset '{$offset}' already exists for index '{$indexName}'");
    }

    /**
     * @param integer $offset
     * @param string  $indexName
     *
     * @return Table
     */
    public static function columnDoesNotExists($offset, $indexName)
    {
        return new self("Column offset '{$offset}' does not exists for index '{$indexName}'.");
    }
}
