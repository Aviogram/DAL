<?php
namespace Aviogram\DAL\Schema;

use Aviogram\DAL\Databases\Shared\DatabaseInterface;

abstract class AbstractSchemaBuilder implements SchemaBuilderInterface
{
    /**
     * @var array
     */
    private $queuedRelationUpdates = array();

    /**
     * @var DatabaseInterface
     */
    protected $database;

    /**
     * Initialize the schema query
     *
     * @param DatabaseInterface $database
     */
    public function __construct(DatabaseInterface $database)
    {
        $this->database = $database;
    }

    /**
     * When the table instance is created this method will be called so you can set the recursive relations
     * that could not be set during the getTableRelations calls
     *
     * @return void
     */
    public function setDelayedRelationInformation()
    {
        // Loop through the queue
        foreach ($this->queuedRelationUpdates as $update) {
            call_user_func_array(array($this, 'setRelationInformation'), $update);
        }

        // Reset the queue
        $this->queuedRelationUpdates = array();
    }

    /**
     * If a type has been specified via the comment we use that one
     *
     * @param  string $comment
     * @param  string $currentType
     *
     * @return string
     */
    protected function getTypeFromComment($comment, $currentType)
    {
        if (preg_match('/Type:([a-zA-Z0-9_]+)/', $comment, $match)) {
            return $match[1];
        }

        return $currentType;
    }

    /**
     * If a fixed timezone has been specified we'll use the timezone before inserting into the database
     *
     * @param string $comment
     *
     * @return string|null
     */
    protected function getTimezoneFromComment($comment)
    {
        if (preg_match('/TZ:([a-zA-Z0-9\/]+)/', $comment, $match)) {
            return $match[1];
        }

        return null;
    }

    /**
     * Add or Update the relation in the relation list based on the information given
     *
     * @param Relations  $relations             The list of relations
     * @param string     $sourceTableName       The table name where the relation belongs to
     * @param Columns    $sourceTableColumns    The table columns of the source table
     * @param string     $name                  The name of the relation
     * @param string     $localTableName        The local table name
     * @param string     $localColumnName       The local column name
     * @param string     $foreignTableName      The foreign table name
     * @param string     $foreignColumnName     The foreign column name
     *
     * @return Relation[]
     */
    protected function addRelationInformationToList(
        Relations $relations,
                  $sourceTableName,
        Columns   $sourceTableColumns,
                  $name,
                  $localTableName,
                  $localColumnName,
                  $foreignTableName,
                  $foreignColumnName
    ) {
        /**
         * Get the column instance for the given name
         *
         * @param  string $name
         *
         * @return Column|null
         */
        $getColumn = function($name) use ($sourceTableColumns) {
            foreach ($sourceTableColumns as $column) {
                if ($column->getName() === $name) {
                    return $column;
                }
            }

            return null;
        };

        // Get relation
        $relation = null;
        foreach ($relations as $possibleRelation) {
            if ($possibleRelation->getName() === $name) {
                $relation = $possibleRelation;
            }
        }

        // If relation is not available create a new one
        if ($relation === null) {
            $relations[] = $relation = new Relation($name);
        }

        // Foreign key to same table, means a self reference
        if ($localTableName === $sourceTableName && $foreignTableName === $sourceTableName) {
            $relation->addLocalColumn($getColumn($localColumnName));
            $relation->addForeignColumn($getColumn($foreignColumnName));
            $relation->setType(Relation::TYPE_SELF_REFERENCE);

            return $relations;
        }

        // Foreign key from the table
        if ($localTableName === $sourceTableName) {
            // By default the type is Many-to-One
            $relation->setType($relation::TYPE_MANY_TO_ONE);

            $foreignTable  = $this->database->getSchemaManager()->getTable($foreignTableName);
            $localColumn   = $getColumn($localColumnName);

            // Check if we're dealing with an recursion flow
            if ($foreignTable === static::RECURSIVE_TABLE_DETECTED) {
                $this->addDelayedInformationOnRelation(
                    $relation,
                    $localTableName,
                    $localColumn,
                    $foreignTableName,
                    $foreignColumnName
                );

                return $relations;
            }

            // Get the column instance
            $foreignColumn = $foreignTable->getColumn($foreignColumnName);

            // Set the relation information
            $relation->addLocalColumn($localColumn)->addForeignColumn($foreignColumn);

            return $relations;
        }

        // By default the type is One-to-Many
        $relation->setType($relation::TYPE_ONE_TO_MANY);

        /** @var Column $foreignColumn */
        $foreignColumn = $getColumn($foreignColumnName);
        $localTable    = $this->database->getSchemaManager()->getTable($localTableName);

        // Check if we're dealing with an recursion flow
        if ($localTable === static::RECURSIVE_TABLE_DETECTED) {
            $this->addDelayedInformationOnRelation(
                $relation,
                $localTableName,
                $localColumnName,
                $foreignTableName,
                $foreignColumn
            );

            return $relations;
        }

        // Get the the column instance
        $localColumn   = $localTable->getColumn($localColumnName);

        // Update relation information
        $relation->addLocalColumn($localColumn)->addForeignColumn($foreignColumn);

        return $relations;
    }

    /**
     * Call this method for delaying the retrieval of the column because of recursion detection
     *
     * @param Relation      $relation           The relation where the updates should take place
     * @param string        $localTableName     The name of the local table
     * @param Column|string $localColumn        When a string is given the column will be fetched
     * @param string        $foreignTableName   The name of the foreign table
     * @param Column|string $foreignColumn      When a string is given the column will be fetched
     * @param null          $database           Optional: Database name
     *
     * @return void
     */
    private function addDelayedInformationOnRelation(
        Relation $relation,
                 $localTableName,
                 $localColumn,
                 $foreignTableName,
                 $foreignColumn,
                 $database = null
    ) {
        array_unshift($this->queuedRelationUpdates, func_get_args());
    }

    /**
     * Set the relation information that has been set via addDelayedInformationOnRelation
     *
     * @param Relation      $relation           The relation where the updates should take place
     * @param string        $localTableName     The name of the local table
     * @param Column|string $localColumn        When a string is given the column will be fetched
     * @param string        $foreignTableName   The name of the foreign table
     * @param Column|string $foreignColumn      When a string is given the column will be fetched
     * @param null          $database           Optional: Database name
     *
     * @return null | array Returns an array when it can still not be processed
     */
    private function setRelationInformation(
        Relation $relation,
                 $localTableName,
                 $localColumn,
                 $foreignTableName,
                 $foreignColumn,
                 $database = null
    ) {
        // Fetch the column if a string was given
        if (($localColumn instanceof Column) === false) {
            $localTable  = $this->database->getSchemaManager()->getTable($localTableName, $database);
            $localColumn = $localTable->getColumn($localColumn);
        }

        // Fetch the column if a string was given
        if (($foreignColumn instanceof Column) === false) {
            $foreignTable  = $this->database->getSchemaManager()->getTable($foreignTableName, $database);
            $foreignColumn = $foreignTable->getColumn($foreignColumn);
        }

        if ($relation->hasLocalColumn($localColumn->getName()) === false) {
            $relation->addLocalColumn($localColumn);
        }

        if ($relation->hasForeignColumn($foreignColumn->getName()) === false) {
            $relation->addForeignColumn($foreignColumn);
        }
    }
}
