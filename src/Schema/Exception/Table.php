<?php
namespace Aviogram\DAL\Schema\Exception;

class Table extends Manager
{
    /**
     * @param string $columnName
     * @param string $tableName
     *
     * @return Manager
     */
    public static function columnAlreadyExists($columnName, $tableName)
    {
        return new self("Column with the name '{$columnName}' already exists for table '{$tableName}'");
    }

    /**
     * @param string $columnName
     * @param string $tableName
     *
     * @return Table
     */
    public static function columnDoesNotExists($columnName, $tableName)
    {
        return new self("Column '{$columnName}' does not exists on table '{$tableName}'.");
    }

    /**
     * @param string $indexName
     * @param string $tableName
     *
     * @return Manager
     */
    public static function indexAlreadyExists($indexName, $tableName)
    {
        return new self("Index with the name '{$indexName}' already exists for table '{$tableName}'");
    }

    /**
     * @param string $indexName
     * @param string $tableName
     *
     * @return Table
     */
    public static function indexDoesNotExists($indexName, $tableName)
    {
        return new self("Index '{$indexName}' does not exists on table '{$tableName}'.");
    }
}
