<?php
namespace Aviogram\DAL\Schema;

class RelationManyToMany extends Relation
{
    /**
     * @var Columns
     */
    protected $secondForeignColumns;

    /**
     * @var Columns
     */
    protected $foreignForeignColumns;

    /**
     * @param string $name
     */
    public function __construct($name)
    {
        parent::__construct($name);

        $this->secondForeignColumns  = new Columns();
        $this->foreignForeignColumns = new Columns();
    }

    /**
     * @return Columns
     */
    public function getSecondForeignColumns()
    {
        return $this->secondForeignColumns;
    }

    /**
     * @return Table
     */
    public function getSecondForeignTable()
    {
        $this->secondForeignColumns->rewind();
        return $this->secondForeignColumns->current()->getTable();
    }

    /**
     * Get a local column
     *
     * @param  string $name
     *
     * @return Column
     * @throws Exception\Table
     */
    public function getSecondForeignColumn($name)
    {
        $alias = $this->normalizeName($name);

        if ($this->secondForeignColumns->offsetExists($alias) === false) {
            throw Exception\Relation::secondForeignColumnDoesNotExists($alias, $this->secondForeignColumns);
        }

        return $this->secondForeignColumns->offsetGet($alias);
    }

    /**
     * @param string $name
     *
     * @return bool
     */
    public function hasSecondForeignColumn($name)
    {
        return $this->secondForeignColumns->offsetExists($this->normalizeName($name));
    }

    /**
     * Add a foreign column for the relation
     *
     * @param Column $column
     *
     * @return $this
     * @throws Exception\Manager
     */
    public function addSecondForeignColumn(Column $column)
    {
        $alias = $this->normalizeName($column->getName());

        if ($this->secondForeignColumns->offsetExists($alias) === true) {
            throw Exception\Relation::secondForeignColumnAlreadyExists($alias, $this->getName());
        }

        $this->secondForeignColumns->offsetSet($alias, $column);

        return $this;
    }

    /**
     * @return Columns
     */
    public function getForeignForeignColumns()
    {
        return $this->foreignForeignColumns;
    }

    /**
     * @return Table
     */
    public function getForeignForeignTable()
    {
        $this->foreignForeignColumns->rewind();
        return $this->foreignForeignColumns->current()->getTable();
    }

    /**
     * Get a local column
     *
     * @param  string $name
     *
     * @return Column
     * @throws Exception\Table
     */
    public function getForeignForeignColumn($name)
    {
        $alias = $this->normalizeName($name);

        if ($this->foreignForeignColumns->offsetExists($alias) === false) {
            throw Exception\Relation::foreignForeignColumnDoesNotExists($alias, $this->foreignForeignColumns);
        }

        return $this->foreignForeignColumns->offsetGet($alias);
    }

    /**
     * @param string $name
     *
     * @return bool
     */
    public function hasForeignForeignColumn($name)
    {
        return $this->foreignForeignColumns->offsetExists($this->normalizeName($name));
    }

    /**
     * Add a foreign column for the relation
     *
     * @param Column $column
     *
     * @return $this
     * @throws Exception\Manager
     */
    public function addForeignForeignColumn(Column $column)
    {
        $alias = $this->normalizeName($column->getName());

        if ($this->foreignForeignColumns->offsetExists($alias) === true) {
            throw Exception\Relation::foreignForeignColumnAlreadyExists($alias, $this->getName());
        }

        $this->foreignForeignColumns->offsetSet($alias, $column);

        return $this;
    }

    /**
     * @return string
     */
    public function getType()
    {
        return static::TYPE_MANY_TO_MANY;
    }
}
