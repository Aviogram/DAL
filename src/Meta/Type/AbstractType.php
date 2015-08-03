<?php
namespace Aviogram\DAL\Meta\Type;

use Aviogram\Common\ArrayUtils;
use Aviogram\DAL\Databases\Shared\DatabaseInterface;

abstract class AbstractType
{
    /**
     * @var DatabaseInterface
     */
    private $database;

    /**
     * @var array
     */
    private $options;

    /**
     * @param DatabaseInterface $database
     * @param array             $options
     */
    public function __construct(DatabaseInterface $database, array $options = array())
    {
        $this->database = $database;
        $this->options  = $options;
    }

    /**
     * @return DatabaseInterface
     */
    protected function getDatabase()
    {
        return $this->database;
    }

    /**
     * Get an option from the configuration
     *
     * @param string $key       This can be a dotted separated string
     * @param null   $default   This value will be returned when the key cannot be found
     *
     * @return mixed
     */
    protected function getOption($key, $default = null)
    {
        return ArrayUtils::targetGet($key, $this->options, $default);
    }

    /**
     * @param mixed $value
     *
     * @return mixed
     */
    public function castToDatabaseValue($value)
    {
        return $value;
    }

    /**
     * @param  mixed $value
     *
     * @return mixed
     */
    public function castToPHPValue($value)
    {
        return $value;
    }
}
