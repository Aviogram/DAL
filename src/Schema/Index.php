<?php
namespace Aviogram\DAL\Schema;

class Index extends AbstractElement
{
    /**
     * @var Columns
     */
    protected $columns;

    /**
     * @var boolean
     */
    protected $unique;

    /**
     * @var string
     */
    protected $type;

    /**
     * @var boolean
     */
    protected $primary;

    /**
     * @param string  $name
     * @param boolean $unique
     * @param boolean $primary
     * @param string  $type
     */
    public function __construct(
              $name,
              $unique    = false,
              $primary   = false,
              $type      = null
    ) {
        $this->setName($name);

        $this->columns = new Columns();

        $this->unique  = $unique;
        $this->primary = $primary;
        $this->type    = $type;
    }

    /**
     * @return Columns
     */
    public function getColumns()
    {
        return $this->columns;
    }

    /**
     * Get a single column from the index
     *
     * @param  integer $index   The column position in the index. (0 indexed)
     *
     * @return Column
     * @throws Exception\Table
     */
    public function getColumn($index)
    {
        if ($this->columns->offsetExists($index) === false) {
            throw Exception\Index::columnDoesNotExists($this->getName(), $index);
        }

        return $this->columns->offsetGet($index);
    }

    /**
     * @param int    $index     The position of the column in the index (0 indexed)
     * @param Column $column
     *
     * @return Index
     * @throws Exception\Manager
     */
    public function addColumn($index, Column $column)
    {
        if ($this->columns->offsetExists($index) === true) {
            throw Exception\Index::columnAlreadyExists($index, $this->getName());
        }

        $this->columns->offsetSet($index, $column);

        return $this;
    }

    /**
     * @return boolean
     */
    public function isUnique()
    {
        return $this->unique;
    }

    /**
     * @param boolean $unique
     *
     * @return Index
     */
    public function setUnique($unique)
    {
        $this->unique = $unique;

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
     * @param string $type
     *
     * @return Index
     */
    public function setType($type)
    {
        $this->type = $type;

        return $this;
    }

    /**
     * @return boolean
     */
    public function isPrimary()
    {
        return $this->primary;
    }

    /**
     * @param boolean $primary
     *
     * @return Index
     */
    public function setPrimary($primary)
    {
        $this->primary = $primary;

        return $this;
    }
}
