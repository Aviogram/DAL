<?php
namespace Aviogram\DAL\Schema;

use Aviogram\DAL\Databases\Shared\DatabaseInterface;

class SchemaManager
{
    const RECURSIVE_TABLE_DETECTED = SchemaBuilderInterface::RECURSIVE_TABLE_DETECTED;

    /**
     * @var SchemaBuilderInterface
     */
    protected $schemaBuilder;

    /**
     * @var DatabaseInterface
     */
    protected $database;

    /**
     * Initialize the schema manager
     *
     * @param DatabaseInterface      $database
     * @param SchemaBuilderInterface $schemaBuilder
     */
    public function __construct(DatabaseInterface $database, SchemaBuilderInterface $schemaBuilder)
    {
        $this->schemaBuilder = $schemaBuilder;
        $this->database      = $database;
    }

    /**
     * Get a list of database names
     *
     * @return array
     */
    public function getDatabaseNameList()
    {
        return $this->schemaBuilder->getDatabaseNameList();
    }

    /**
     * Get a list of table names for the given database name
     *
     * @param string|null $database
     *
     * @return array
     */
    public function getTableNameList($database = null)
    {
        return $this->schemaBuilder->getTableNameList($database);
    }

    /**
     * Get the table definition for the given table name
     *
     * @param string      $table
     * @param string|null $database
     *
     * @return Table
     */
    public function getTable($table, $database = null)
    {
        static $tableCache = array();
        static $inProgress = array();
        static $mainCall   = true;

        $cacheKey      = "{$database}.{$table}";
        $updateDelayed = $mainCall;

        if ($mainCall === true) {
            $mainCall = false;
        }

        // Return table from instance if we have already parsed it
        if (array_key_exists($cacheKey, $tableCache) === true) {
            return $tableCache[$cacheKey];
        }

        if (in_array($table, $inProgress) === true) {
            return static::RECURSIVE_TABLE_DETECTED;
        }

        $inProgress[] = $table;

        $columns   = $this->schemaBuilder->getTableColumns($table, $database);
        $indexes   = $this->schemaBuilder->getTableIndexes($columns, $table, $database);
        $relations = $this->schemaBuilder->getTableRelations($columns, $table, $database);

        // Remove table from the inProgress queue
        array_splice($inProgress, array_search($table, $inProgress), 1);

        // Create table in cache
        $tableCache[$cacheKey] = new Table($table, $columns, $indexes, $relations);

        // All the tables are processed. Now Call the update delayed relations
        if (count($inProgress) === 0 && $updateDelayed === true) {
            // Give the builder the chance to update the relations for any recursion errors that have been occurred
            $this->schemaBuilder->setDelayedRelationInformation();

            // Reset main call
            $mainCall = true;

            $tableStack = array();

            // Correct type to oneToOne and/or add manyToMany relations
            $checkRelationTypes = function(Table $table, $self, Table $parent = null) use (&$tableStack) {
                $tableName      = $table->getName();
                $debug          = ($tableName === 'order_item' || $tableName === 'order');

                // Prevent recursion
                if (in_array($tableName, $tableStack) === true) {
                    return;
                }

                $tableStack[] = $tableName;
                $manyToMany   = array(
                    /** @var Columns */
                    'parent'  => array(),
                    'foreign' => array()
                );

                foreach ($table->getRelations() as $tableRelation) {
                    if ($tableRelation->isSelfReference() === true) {
                        continue;
                    }

                    $indexes            = $tableRelation->getLocalTable()->getIndexes();
                    $relationColumnHash = $tableRelation->getLocalColumns()->getIdentifier();

                    foreach ($indexes as $index) {
                        if ($index->isUnique() === false || $index->isPrimary() === true) {
                            continue;
                        }

                        if ($relationColumnHash === $index->getColumns()->getIdentifier()) {
                            $tableRelation->setType($tableRelation::TYPE_ONE_TO_ONE);
                        }
                    }

                    if ($tableRelation->isManyToOne()) {
                        if ($tableRelation->getLocalTable() === $parent || $tableRelation->getForeignTable() === $parent) {
                            $type = 'parent';
                        } else {
                            $type = 'foreign';
                        }

                        $manyToMany[$type][] = $tableRelation;
                    }

                    $self($tableRelation->getLocalTable(), $self, $table);
                    $self($tableRelation->getForeignTable(), $self, $table);
                }

                if (empty($manyToMany['parent']) === false && empty($manyToMany['foreign']) === false) {
                    /** @var Relation $parentRelation*/
                    foreach ($manyToMany['parent'] as $parentRelation) {
                        /** @var Relation $foreignRelation */
                        foreach ($manyToMany['foreign'] as $foreignRelation) {
                            $relation = new RelationManyToMany($parentRelation->getName() . '_' . $foreignRelation->getName());

                            foreach ($parentRelation->getLocalColumns() as $column) {
                                $relation->addLocalColumn($column);
                            }

                            foreach ($parentRelation->getForeignColumns() as $column) {
                                $relation->addForeignColumn($column);
                            }

                            foreach ($foreignRelation->getLocalColumns() as $column) {
                                $relation->addSecondForeignColumn($column);
                            }

                            foreach ($foreignRelation->getForeignColumns() as $column) {
                                $relation->addForeignForeignColumn($column);
                            }

                            echo "Add relation to table: {$parent->getName()}\n";
                            if ($parent->hasRelation($relation->getName()) === false) {
                                $parent->addRelation($relation);
                            }

                            echo "Add relation to table: {$foreignRelation->getForeignTable()->getName()}\n";
                            if ($foreignRelation->getForeignTable()->hasRelation($relation->getName()) === false) {
                                $foreignRelation->getForeignTable()->addRelation($relation);
                            }
                        }
                    }
                }

                array_splice($tableStack, array_search($tableName, $tableStack), 1);
            };

            $checkRelationTypes($tableCache[$cacheKey], $checkRelationTypes);
        }

        // Return the new created table
        return $tableCache[$cacheKey];
    }
}
