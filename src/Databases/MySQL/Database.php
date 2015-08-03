<?php
namespace Aviogram\DAL\Databases\MySQL;

use Aviogram\DAL\Databases\Shared\AbstractDatabase;
use Aviogram\DAL\Meta\Type;
use Aviogram\DAL\Schema\SchemaBuilderInterface;

class Database extends AbstractDatabase
{
    /**
     * @return string
     */
    public function getName()
    {
        return 'MySQL';
    }

    /**
     * Return a list of drivers <alias> => <driverClass>
     *
     * @return array
     */
    protected function getDrivers()
    {
        return array(
            'pdo' => 'Aviogram\DAL\Databases\MySQL\Driver\PDO',
        );
    }

    /**
     * @return SchemaBuilderInterface
     */
    protected function getSchemaBuilderClass()
    {
        return 'Aviogram\DAL\Databases\MySQL\SchemaBuilder';
    }

    /**
     * Returns a type mapping for mapping database values to PHP values and visa versa
     *
     * @return array array('<db_type>' => Type::TYPE_*);
     */
    protected function getTypeMapping()
    {
        return array(
            'boolean'       => Type::TYPE_BOOLEAN,
            'bool'          => Type::TYPE_BOOLEAN,
            'tinyint'       => Type::TYPE_INTEGER,
            'smallint'      => Type::TYPE_INTEGER,
            'mediumint'     => Type::TYPE_INTEGER,
            'int'           => Type::TYPE_INTEGER,
            'integer'       => Type::TYPE_INTEGER,
            'bigint'        => Type::TYPE_BIGINT,
            'tinytext'      => Type::TYPE_TEXT,
            'mediumtext'    => Type::TYPE_TEXT,
            'longtext'      => Type::TYPE_TEXT,
            'text'          => Type::TYPE_TEXT,
            'varchar'       => Type::TYPE_STRING,
            'string'        => Type::TYPE_STRING,
            'char'          => Type::TYPE_STRING,
            'date'          => Type::TYPE_DATE,
            'datetime'      => Type::TYPE_DATETIME,
            'timestamp'     => Type::TYPE_DATETIME_TZ,
            'time'          => Type::TYPE_TIME,
            'float'         => Type::TYPE_FLOAT,
            'double'        => Type::TYPE_FLOAT,
            'real'          => Type::TYPE_FLOAT,
            'decimal'       => Type::TYPE_DECIMAL,
            'numeric'       => Type::TYPE_DECIMAL,
            'year'          => Type::TYPE_DATE,
            'longblob'      => Type::TYPE_BLOB,
            'blob'          => Type::TYPE_BLOB,
            'mediumblob'    => Type::TYPE_BLOB,
            'tinyblob'      => Type::TYPE_BLOB,
            'binary'        => Type::TYPE_BINARY,
            'varbinary'     => Type::TYPE_BINARY,
            'set'           => Type::TYPE_DEFINED_LIST,
            'enum'          => TYPE::TYPE_DEFINED_LIST
        );
    }
}
