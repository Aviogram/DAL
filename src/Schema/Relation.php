<?php
namespace Aviogram\DAL\Schema;

class Relation extends AbstractElement
{
    const TYPE_ONE_TO_ONE     = 'oneToOne';
    const TYPE_ONE_TO_MANY    = 'oneToMany';
    const TYPE_MANY_TO_ONE    = 'manyToOne';
    const TYPE_MANY_TO_MANY   = 'manyToMany';
    const TYPE_SELF_REFERENCE = 'selfReference';

    /**
     * @var string
     */
    protected $type;

    /**
     * Available types
     *
     * @var array
     */
    protected $types = array(
        self::TYPE_MANY_TO_MANY,
        self::TYPE_MANY_TO_ONE,
        self::TYPE_ONE_TO_MANY,
        self::TYPE_SELF_REFERENCE,
        self::TYPE_ONE_TO_ONE
    );

    /**
     * @var Columns
     */
    protected $localColumns;

    /**
     * @var Columns
     */
    protected $foreignColumns;

    /**
     * @var Table
     */
    protected $table;

    /**
     * @param string $name
     */
    public function __construct($name)
    {
        $this->setName($name);

        // Initialize collections
        $this->localColumns   = new Columns();
        $this->foreignColumns = new Columns();
    }

    /**
     * @return Columns
     */
    public function getLocalColumns()
    {
        return $this->localColumns;
    }

    /**
     * @return Table
     */
    public function getLocalTable()
    {
        $this->localColumns->rewind();
        return $this->localColumns->current()->getTable();
    }

    /**
     * Get a local column
     *
     * @param  string $name
     *
     * @return Column
     * @throws Exception\Table
     */
    public function getLocalColumn($name)
    {
        $alias = $this->normalizeName($name);

        if ($this->localColumns->offsetExists($alias) === false) {
            throw Exception\Relation::localColumnDoesNotExists($alias, $this->localColumns);
        }

        return $this->localColumns->offsetGet($alias);
    }

    /**
     * @param string $name
     *
     * @return bool
     */
    public function hasLocalColumn($name)
    {
        return $this->localColumns->offsetExists($this->normalizeName($name));
    }

    /**
     * Add a local column for the relation
     *
     * @param Column $column
     *
     * @return $this
     * @throws Exception\Manager
     */
    public function addLocalColumn(Column $column)
    {
        $alias = $this->normalizeName($column->getName());

        if ($this->localColumns->offsetExists($alias) === true) {
            throw Exception\Relation::localColumnAlreadyExists($alias, $this->getName());
        }

        $this->localColumns->offsetSet($alias, $column);

        return $this;
    }

    /**
     * @return Columns
     */
    public function getForeignColumns()
    {
        return $this->foreignColumns;
    }

    /**
     * @return Table
     */
    public function getForeignTable()
    {
        $this->foreignColumns->rewind();
        return $this->foreignColumns->current()->getTable();
    }

    /**
     * Get a local column
     *
     * @param  string $name
     *
     * @return Column
     * @throws Exception\Table
     */
    public function getForeignColumn($name)
    {
        $alias = $this->normalizeName($name);

        if ($this->foreignColumns->offsetExists($alias) === false) {
            throw Exception\Relation::foreignColumnDoesNotExists($alias, $this->foreignColumns);
        }

        return $this->foreignColumns->offsetGet($alias);
    }

    /**
     * @param string $name
     *
     * @return bool
     */
    public function hasForeignColumn($name)
    {
        return $this->foreignColumns->offsetExists($this->normalizeName($name));
    }

    /**
     * Add a foreign column for the relation
     *
     * @param Column $column
     *
     * @return $this
     * @throws Exception\Manager
     */
    public function addForeignColumn(Column $column)
    {
        $alias = $this->normalizeName($column->getName());

        if ($this->foreignColumns->offsetExists($alias) === true) {
            throw Exception\Relation::foreignColumnAlreadyExists($alias, $this->getName());
        }

        $this->foreignColumns->offsetSet($alias, $column);

        return $this;
    }

    /**
     * @return string
     */
    public function getType()
    {
        return $this->type;
    }

    /**
     * @param string $type See (static::TYPE_*)
     *
     * @return Relation
     * @throws Exception\Relation
     */
    public function setType($type)
    {
        if (in_array($type, $this->types) === false) {
            throw Exception\Relation::invalidType($type, $this->getName());
        }

        $this->type = $type;

        return $this;
    }

    /**
     * @return array
     */
    public function getTypes()
    {
        return $this->types;
    }

    /**
     * @param array $types
     *
     * @return Relation
     */
    public function setTypes($types)
    {
        $this->types = $types;

        return $this;
    }

    /**
     * @return Table
     */
    public function getTable()
    {
        return $this->table;
    }

    /**
     * @param Table $table
     *
     * @return Relation
     */
    public function setTable(Table $table)
    {
        $this->table = $table;

        return $this;
    }

    /**
     * @return bool
     */
    public function isOneToOne()
    {
        return $this->getType() === static::TYPE_ONE_TO_ONE;
    }

    /**
     * @return bool
     */
    public function isOneToMany()
    {
        return $this->getType() === static::TYPE_ONE_TO_MANY;
    }

    /**
     * @return bool
     */
    public function isManyToOne()
    {
        return $this->getType() === static::TYPE_MANY_TO_ONE;
    }

    /**
     * @return bool
     */
    public function isSelfReference()
    {
        return $this->getType() === static::TYPE_SELF_REFERENCE;
    }
}
