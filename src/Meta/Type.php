<?php
namespace Aviogram\DAL\Meta;

use Aviogram\DAL\Databases\Shared\DatabaseInterface;
use Aviogram\DAL\Meta\Type\AbstractType;

class Type
{
    const TYPE_OBJECT       = 'Object';
    const TYPE_PHP_ARRAY    = 'PHPArray';
    const TYPE_JSON         = 'Json';
    const TYPE_BIGINT       = 'BigInt';
    const TYPE_BOOLEAN      = 'Boolean';
    const TYPE_DATETIME     = 'Datetime';
    const TYPE_DATETIME_TZ  = 'DatetimeTz';
    const TYPE_DATE         = 'Date';
    const TYPE_TIME         = 'Time';
    const TYPE_DECIMAL      = 'Decimal';
    const TYPE_FLOAT        = 'Float';
    const TYPE_INTEGER      = 'Integer';
    const TYPE_SMALLINT     = 'SmallInt';
    const TYPE_STRING       = 'String';
    const TYPE_TEXT         = 'Text';
    const TYPE_BINARY       = 'Binary';
    const TYPE_BLOB         = 'Blob';
    const TYPE_DEFINED_LIST = 'DefinedList';

    /**
     * The constant mapping to classes
     *
     * @var array
     */
    protected $typeMapping = array(
        self::TYPE_OBJECT       => 'Aviogram\DAL\Meta\Type\Object',
        self::TYPE_PHP_ARRAY    => 'Aviogram\DAL\Meta\Type\PHPArray',
        self::TYPE_JSON         => 'Aviogram\DAL\Meta\Type\Json',
        self::TYPE_BIGINT       => 'Aviogram\DAL\Meta\Type\BigInt',
        self::TYPE_BOOLEAN      => 'Aviogram\DAL\Meta\Type\Boolean',
        self::TYPE_DATETIME     => 'Aviogram\DAL\Meta\Type\Datetime',
        self::TYPE_DATETIME_TZ  => 'Aviogram\DAL\Meta\Type\DatetimeTz',
        self::TYPE_DATE         => 'Aviogram\DAL\Meta\Type\Date',
        self::TYPE_TIME         => 'Aviogram\DAL\Meta\Type\Time',
        self::TYPE_DECIMAL      => 'Aviogram\DAL\Meta\Type\Decimal',
        self::TYPE_FLOAT        => 'Aviogram\DAL\Meta\Type\Float',
        self::TYPE_INTEGER      => 'Aviogram\DAL\Meta\Type\Integer',
        self::TYPE_SMALLINT     => 'Aviogram\DAL\Meta\Type\SmallInt',
        self::TYPE_STRING       => 'Aviogram\DAL\Meta\Type\String',
        self::TYPE_TEXT         => 'Aviogram\DAL\Meta\Type\Text',
        self::TYPE_BINARY       => 'Aviogram\DAL\Meta\Type\Binary',
        self::TYPE_BLOB         => 'Aviogram\DAL\Meta\Type\Blob',
        self::TYPE_DEFINED_LIST => 'Aviogram\DAL\Meta\Type\DefinedList'
    );

    /**
     * @var DatabaseInterface
     */
    protected $database;

    /**
     * @var array
     */
    protected $databaseMapping = array();

    /**
     * Holds single instance of the type
     *
     * @var array
     */
    protected $types   = array();

    /**
     * Create a new type mapping map
     *
     * @param DatabaseInterface $database
     * @param array             $databaseMapping The type mapping <db_type> => <dal_type>
     */
    public function __construct(DatabaseInterface $database, array $databaseMapping)
    {
        $this->database         = $database;
        $this->databaseMapping  = $databaseMapping;
    }

    /**
     * @param string $type
     *
     * @param array  $options   Extra options for the Type
     *
     * @return AbstractType
     * @throws Exception\Type
     */
    public function getTypeForDatabaseType($type, array $options = array())
    {
        if (array_key_exists($type, $this->databaseMapping) === true) {
            $type = $this->databaseMapping[$type];
        }

        if (array_key_exists($type, $this->typeMapping) === false) {
            throw Exception\Type::doesNotExists($type);
        }

        // Create an unique hash for the type. In this way we can have multiple instances for list columns (SET,ENUM)
        $hash = $type . md5(serialize($options));

        if (array_key_exists($hash, $this->types) === false) {
            $class              = $this->typeMapping[$type];
            $this->types[$hash] = new $class($this->database, $options);
        }

        return $this->types[$hash];
    }
}
