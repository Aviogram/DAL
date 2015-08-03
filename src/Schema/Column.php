<?php
namespace Aviogram\DAL\Schema;

use Aviogram\DAL\Meta\Type\AbstractType;

class Column extends AbstractElement
{
    /**
     * @var Table
     */
    protected $table;

    /**
     * @var AbstractType
     */
    protected $type;

    /**
     * @var boolean
     */
    protected $nullable;

    /**
     * @var integer|NULL
     */
    protected $length;

    /**
     * @var integer|NULL
     */
    protected $precision;

    /**
     * @var integer|NULL
     */
    protected $scale;

    /**
     * @var boolean
     */
    protected $unsigned = false;

    /**
     * @var boolean
     */
    protected $fixed = false;

    /**
     * @var mixed
     */
    protected $default;

    /**
     * @var boolean
     */
    protected $autoIncrement = false;

    /**
     * @var string|NULL
     */
    protected $comment;

    /**
     * @var array
     */
    protected $extraColumnOptions = array();

    /**
     * Column constructor.
     *
     * @param string       $name
     * @param AbstractType $type
     */
    public function __construct($name, AbstractType $type = null)
    {
        $this->setName($name);
        $this->type = $type;
    }

    /**
     * @return string
     */
    public function getName()
    {
        return $this->name;
    }

    /**
     * @return AbstractType
     */
    public function getType()
    {
        return $this->type;
    }

    /**
     * @return boolean
     */
    public function isNullable()
    {
        return $this->nullable;
    }

    /**
     * @param boolean $nullable
     *
     * @return Column
     */
    public function setNullable($nullable)
    {
        $this->nullable = $nullable;

        return $this;
    }

    /**
     * @return int|NULL
     */
    public function getLength()
    {
        return $this->length;
    }

    /**
     * @param int|NULL $length
     *
     * @return Column
     */
    public function setLength($length)
    {
        $this->length = $length;

        return $this;
    }

    /**
     * @return int|NULL
     */
    public function getPrecision()
    {
        return $this->precision;
    }

    /**
     * @param int|NULL $precision
     *
     * @return Column
     */
    public function setPrecision($precision)
    {
        $this->precision = $precision;

        return $this;
    }

    /**
     * @return int|NULL
     */
    public function getScale()
    {
        return $this->scale;
    }

    /**
     * @param int|NULL $scale
     *
     * @return Column
     */
    public function setScale($scale)
    {
        $this->scale = $scale;

        return $this;
    }

    /**
     * @return boolean
     */
    public function isUnsigned()
    {
        return $this->unsigned;
    }

    /**
     * @param boolean $unsigned
     *
     * @return Column
     */
    public function setUnsigned($unsigned)
    {
        $this->unsigned = $unsigned;

        return $this;
    }

    /**
     * @return boolean
     */
    public function isFixed()
    {
        return $this->fixed;
    }

    /**
     * @param boolean $fixed
     *
     * @return Column
     */
    public function setFixed($fixed)
    {
        $this->fixed = $fixed;

        return $this;
    }

    /**
     * @return mixed
     */
    public function getDefault()
    {
        return $this->default;
    }

    /**
     * @param mixed $default
     *
     * @return Column
     */
    public function setDefault($default)
    {
        $this->default = $default;

        return $this;
    }

    /**
     * @return boolean
     */
    public function isAutoIncrement()
    {
        return $this->autoIncrement;
    }

    /**
     * @param boolean $autoIncrement
     *
     * @return Column
     */
    public function setAutoIncrement($autoIncrement)
    {
        $this->autoIncrement = $autoIncrement;

        return $this;
    }

    /**
     * @return NULL|string
     */
    public function getComment()
    {
        return $this->comment;
    }

    /**
     * @param NULL|string $comment
     *
     * @return Column
     */
    public function setComment($comment)
    {
        $this->comment = $comment;

        return $this;
    }

    /**
     * @return array
     */
    public function getExtraColumnOptions()
    {
        return $this->extraColumnOptions;
    }

    /**
     * @param  string $key
     * @param  mixed  $value
     *
     * @return Column
     */
    public function addExtraColumnOptions($key, $value)
    {
        $this->extraColumnOptions[$key] = $value;

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
     * @return Column
     */
    public function setTable($table)
    {
        $this->table = $table;

        return $this;
    }
}
