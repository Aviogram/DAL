<?php
namespace Aviogram\DAL\Schema\Exception;

class Relation extends Manager
{
    /**
     * @param string $columnName
     * @param string $relationName
     *
     * @return Manager
     */
    public static function localColumnAlreadyExists($columnName, $relationName)
    {
        return new self("Local column with the name '{$columnName}' already exists for relation '{$relationName}'");
    }

    /**
     * @param string $columnName
     * @param string $relationName
     *
     * @return Table
     */
    public static function localColumnDoesNotExists($columnName, $relationName)
    {
        return new self("Local column '{$columnName}' does not exists for relation '{$relationName}'.");
    }

    /**
     * @param string $columnName
     * @param string $relationName
     *
     * @return Manager
     */
    public static function foreignColumnAlreadyExists($columnName, $relationName)
    {
        return new self("Foreign column with the name '{$columnName}' already exists for relation '{$relationName}'");
    }

    /**
     * @param string $columnName
     * @param string $relationName
     *
     * @return Table
     */
    public static function foreignColumnDoesNotExists($columnName, $relationName)
    {
        return new self("Foreign column '{$columnName}' does not exists for relation '{$relationName}'.");
    }

    /**
     * @param string $columnName
     * @param string $relationName
     *
     * @return Manager
     */
    public static function secondForeignColumnAlreadyExists($columnName, $relationName)
    {
        return new self("Second foreign column with the name '{$columnName}' already exists for relation '{$relationName}'");
    }

    /**
     * @param string $columnName
     * @param string $relationName
     *
     * @return Table
     */
    public static function secondForeignColumnDoesNotExists($columnName, $relationName)
    {
        return new self("Second foreign column '{$columnName}' does not exists for relation '{$relationName}'.");
    }

    /**
     * @param string $columnName
     * @param string $relationName
     *
     * @return Manager
     */
    public static function foreignForeignColumnAlreadyExists($columnName, $relationName)
    {
        return new self("Foreign foreign column with the name '{$columnName}' already exists for relation '{$relationName}'");
    }

    /**
     * @param string $columnName
     * @param string $relationName
     *
     * @return Table
     */
    public static function foreignForeignColumnDoesNotExists($columnName, $relationName)
    {
        return new self("Foreign foreign column '{$columnName}' does not exists for relation '{$relationName}'.");
    }

    /**
     * @param string $type
     * @param string $relationName
     *
     * @return Relation
     */
    public static function invalidType($type, $relationName)
    {
        return new self("Type '{$type}' is not a valid relation type for relation '{$relationName}'.");
    }
}
