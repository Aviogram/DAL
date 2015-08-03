<?php
namespace Aviogram\DAL\Schema;

class Table extends AbstractElement
{
    /**
     * @var Columns
     */
    protected $columns;

    /**
     * @var Indexes
     */
    protected $indexes;

    /**
     * @var Relations
     */
    protected $relations;

    /**
     * @var Index|NULL
     */
    protected $primaryIndex;

    /**
     * Creates a new table
     *
     * @param string    $name
     * @param Columns   $columns
     * @param Indexes   $indexes
     * @param Relations $relations
     */
    public function __construct($name, Columns $columns = null, Indexes $indexes = null, Relations $relations = null)
    {
        // Set the table name
        $this->setName($name);

        // Construct collections
        $this->columns   = new Columns();
        $this->indexes   = new Indexes();
        $this->relations = new Relations();

        if ($columns !== null) {
            foreach ($columns as $column) {
                $this->addColumn($column);
            }
        }

        if ($indexes !== null) {
            foreach ($indexes as $index) {
                $this->addIndex($index);
            }
        }

        if ($relations !== null) {
            foreach ($relations as $relation) {
                $this->addRelation($relation);
            }
        }
    }

    /**
     * @param Column $column
     *
     * @return $this
     * @throws Exception\Manager  When the column already exists
     */
    public function addColumn(Column $column)
    {
        $alias = $this->normalizeName($column->getName());

        if ($this->columns->offsetExists($alias) === true) {
            throw Exception\Table::columnAlreadyExists($alias, $this->getName());
        }

        // Set reference to the the current table
        $column->setTable($this);

        // Add the column
        $this->columns->offsetSet($alias, $column);

        return $this;
    }

    /**
     * Get a single column from the table
     *
     * @param  string $name
     *
     * @return Column
     * @throws Exception\Manager  When the column does not exists
     */
    public function getColumn($name)
    {
        $alias = $this->normalizeName($name);

        if ($this->columns->offsetExists($alias) === false) {
            throw Exception\Table::columnDoesNotExists($name, $this->getName());
        }

        return $this->columns->offsetGet($alias);
    }

    /**
     * @return Columns
     */
    public function getColumns()
    {
        return $this->columns;
    }

    /**
     * Add a new index to the table
     *
     * @param Index $index
     *
     * @return $this
     * @throws Exception\Manager    When the index is already added
     */
    public function addIndex(Index $index)
    {
        $alias = $this->normalizeName($index->getName());

        if ($this->indexes->offsetExists($alias) === true) {
            throw Exception\Table::indexAlreadyExists($index->getName(), $this->getName());
        }

        $this->indexes->offsetSet($alias, $index);

        if ($index->isPrimary() === true) {
            $this->primaryIndex = $index;
        }

        return $this;
    }

    /**
     * Get a single index from the table
     *
     * @param  string $name     The name of the index
     *
     * @return Index
     * @throws Exception\Table  When the index does not exists
     */
    public function getIndex($name)
    {
        $alias = $this->normalizeName($name);

        if ($this->indexes->offsetGet($alias) === false) {
            throw Exception\Table::indexDoesNotExists($alias, $this->getName());
        }

        return $this->indexes->offsetGet($alias);
    }

    /**
     * @return Indexes
     */
    public function getIndexes()
    {
        return $this->indexes;
    }

    /**
     * @return Index|NULL
     */
    public function getPrimaryIndex()
    {
        return $this->primaryIndex;
    }

    /**
     * If already relation exists with the name
     *
     * @param string $name
     *
     * @return bool
     */
    public function hasRelation($name)
    {
        return $this->relations->offsetExists($this->normalizeName($name));
    }

    /**
     * Add a new index to the table
     *
     * @param Relation $relation
     *
     * @return $this
     * @throws Exception\Manager    When the relation is already added
     */
    public function addRelation(Relation $relation)
    {
        $alias = $this->normalizeName($relation->getName());

        if ($this->relations->offsetExists($alias) === true) {
            throw Exception\Table::relationAlreadyExists($relation->getName(), $this->getName());
        }

        // Set reference to this table
        $relation->setTable($this);

        // Add the relation to the list
        $this->relations->offsetSet($alias, $relation);

        return $this;
    }

    /**
     * Get a single relation from the table
     *
     * @param  string $name     The name of the relation
     *
     * @return Relation
     * @throws Exception\Table  When the relation does not exists
     */
    public function getRelation($name)
    {
        $alias = $this->normalizeName($name);

        if ($this->relations->offsetExists($alias) === false) {
            throw Exception\Table::relationDoesNotExists($alias, $this->getName());
        }

        return $this->relations->offsetGet($alias);
    }

    /**
     * @return Relations
     */
    public function getRelations()
    {
        return $this->relations;
    }
}
