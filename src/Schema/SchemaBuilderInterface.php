<?php
namespace Aviogram\DAL\Schema;

use Aviogram\DAL\Databases\Shared\DatabaseInterface;

interface SchemaBuilderInterface
{
    const RECURSIVE_TABLE_DETECTED = 1;

    /**
     * Initialize the schema query
     *
     * @param DatabaseInterface $database
     */
    public function __construct(DatabaseInterface $database);

    /**
     * Retrieve a list of database names
     *
     * @return array
     */
    public function getDatabaseNameList();

    /**
     * Retrieve a list of table names for the given database
     *
     * @param  string|NULL $database
     *
     * @return array
     */
    public function getTableNameList($database = null);

    /**
     * Retrieve a list of columns for the given table
     *
     * @param string        $table
     * @param string|NULL   $database
     *
     * @return Columns
     */
    public function getTableColumns($table, $database = null);

    /**
     * Retrieve a list of indexed for the given table
     *
     * @param Columns     $columns
     * @param string      $table
     * @param string|NULL $database
     *
     * @return Indexes
     */
    public function getTableIndexes(Columns $columns, $table, $database = null);

    /**
     * Retrieve a list of relations for the given table
     *
     * @param Columns       $columns
     * @param string        $table
     * @param string|NULL   $database
     *
     * @return Relations
     */
    public function getTableRelations(Columns $columns, $table, $database = null);

    /**
     * When the table instance is created this method will be called so you can set the recursive relations
     * that could not be set during the getTableRelations calls
     *
     * @return void
     */
    public function setDelayedRelationInformation();
}
