<?php
namespace Aviogram\DAL\Databases\MySQL;

use Aviogram\DAL\Schema\AbstractSchemaBuilder;
use Aviogram\DAL\Schema\Column;
use Aviogram\DAL\Schema\Columns;
use Aviogram\DAL\Schema\ForeignKey;
use Aviogram\DAL\Schema\Index;
use Aviogram\DAL\Schema\Indexes;
use Aviogram\DAL\Schema\Relation;
use Aviogram\DAL\Schema\Relations;

class SchemaBuilder extends AbstractSchemaBuilder
{
    /**
     * Retrieve a list of database names
     *
     * @return array
     */
    public function getDatabaseNameList()
    {
        $statement = $this->database->getConnectionSingleton()->query('SHOW DATABASES');

        return $statement->fetchAllTransform(
            function($row) {
                return $row[0];
            },
            $statement::FETCH_NUM
        );
    }

    /**
     * Retrieve a list of table names for the given database
     *
     * @param  string $database
     *
     * @return array
     */
    public function getTableNameList($database = null)
    {
        $query = "SHOW TABLES";
        if ($database !== null) {
            $query .= " FROM `{$database}`";
        }

        $statement = $this->database->getConnectionSingleton()->query($query);

        return $statement->fetchAllTransform(
            function($row) {
                return $row[0];
            },
            $statement::FETCH_NUM
        );
    }

    /**
     * Retrieve a list of columns for the given table
     *
     * @param string        $table
     * @param string|NULL   $database
     *
     * @return Columns
     */
    public function getTableColumns($table, $database = null)
    {
        $database = $database ? "'{$database}'" : "DATABASE()";
        $query    = <<<SQL
SELECT
  `COLUMN_NAME`                                         as `name`,
  `COLUMN_DEFAULT`                                      as `default`,
  `DATA_TYPE`                                           as `type`,
  IF(`IS_NULLABLE` = 'NO', FALSE, TRUE)                 as `nullable`,
  `NUMERIC_PRECISION`                                   as `precision`,
  `NUMERIC_SCALE`                                       as `scale`,
  `COLUMN_COMMENT`                                      as `comment`,
  IF(`EXTRA` = 'auto_increment', TRUE, FALSE)           as `autoIncrement`,
  IF(INSTR(`COLUMN_TYPE`, 'unsigned') = 0, FALSE, TRUE) as `unsigned`,
  `CHARACTER_MAXIMUM_LENGTH`                            as `length`,
  IF(
    `DATA_TYPE` = 'set' OR `DATA_TYPE` = 'enum',
    REPLACE(
      SUBSTRING(`COLUMN_TYPE`, LOCATE('(', COLUMN_TYPE) + 1, LOCATE(')', COLUMN_TYPE) - LOCATE('(', COLUMN_TYPE) - 1),
      "'",
      ''
    ),
    NULL
  ) as `list`
FROM
  `information_schema`.`COLUMNS`
WHERE
  `TABLE_SCHEMA` = {$database} AND `TABLE_NAME` = '{$table}'
SQL;

        $statement = $this->database->getConnectionSingleton()->query($query);
        $columns   = new Columns();

        $statement->fetchAllTransform(
            function($row) use ($columns) {
                $type        = $row['type'];
                $typeOptions = array();

                if ($row['list'] !== null) {
                    $typeOptions['list'] = explode(',', $row['list']);
                }

                // Treat Tinyint(1) as boolean
                if ($type === 'tinyint' && $row['precision'] === '1') {
                    $type = 'boolean';
                }

                $type = $this->getTypeFromComment($row['comment'], $type);

                $columns->append($column = new Column(
                    $row['name'],
                    $this->database->getMetaData()->getType()->getTypeForDatabaseType($type, $typeOptions)
                ));

                $column->setAutoIncrement((boolean) $row['autoIncrement']);
                $column->setComment($row['comment'] ?: null);
                $column->setDefault($row['default']);
                $column->setFixed($row['type'] === 'char' || $row['type'] === 'binary');
                $column->setLength($row['length'] ? (int) $row['length'] : null);
                $column->setPrecision($row['precision'] ? (int) $row['precision'] : null);
                $column->setScale($row['scale'] ? (int) $row['scale'] : null);
                $column->setUnsigned((boolean) $row['unsigned']);
                $column->setNullable((boolean) $row['nullable']);
            },
            $statement::FETCH_ASSOC
        );

        return $columns;
    }

    /**
     * Retrieve a list of indexed for the given table
     *
     * @param Columns       $columns
     * @param string        $table
     * @param string|NULL   $database
     *
     * @return Indexes
     */
    public function getTableIndexes(Columns $columns, $table, $database = null)
    {
        $indexes   = new Indexes();
        $indexHash = array();
        $database = $database ? "'{$database}'" : "DATABASE()";
        $sql      = <<<SQL
SELECT
  `INDEX_NAME` as `name`,
  `SEQ_IN_INDEX` as `index`,
  IF(`INDEX_NAME` = 'PRIMARY', TRUE, FALSE) as `primary`,
  IF(`NON_UNIQUE` = 0, TRUE, FALSE) as `unique`,
  `INDEX_TYPE` as `type`,
  `COLUMN_NAME` as `columnName`
FROM
  `information_schema`.`STATISTICS`
WHERE
  `TABLE_SCHEMA` = {$database} AND `TABLE_NAME` = '{$table}'
SQL;
        $transform = function($row) use (&$indexHash, $indexes, $columns) {
            if (array_key_exists($row['name'], $indexHash) === false) {
                $indexes->append($index  = new Index($row['name']));
                $indexHash[$row['name']] = $index;

                $index->setPrimary((bool) $row['primary']);
                $index->setType($row['type']);
                $index->setUnique((bool) $row['unique']);
            } else {
                $index = $indexHash[$row['name']];
            }

            foreach ($columns as $column) {
                if ($column->getName() === $row['columnName']) {
                    $index->addColumn((int) $row['index'], $column);
                    break;
                }
            }
        };

        $statement = $this->database->getConnectionSingleton()->query($sql);
        $statement->fetchAllTransform($transform, $statement::FETCH_ASSOC);

        return $indexes;
    }

    /**
     * Retrieve a list of relations for the given table
     *
     * @param Columns       $columns
     * @param string        $table
     * @param string|NULL   $database
     *
     * @return Relations
     */
    public function getTableRelations(Columns $columns, $table, $database = null)
    {
        $relations = new Relations();
        $database  = $database ? "'{$database}'" : "DATABASE()";
        $sql       = <<<SQL
SELECT
  kcu.CONSTRAINT_NAME as `name`,
  kcu.TABLE_NAME as `localTable`,
  kcu.COLUMN_NAME as `localColumn`,
  kcu.REFERENCED_TABLE_NAME AS `foreignTable`,
  kcu.REFERENCED_COLUMN_NAME as `foreignColumn`,
  rc.UPDATE_RULE AS `onUpdate`,
  rc.DELETE_RULE AS `onDelete`
FROM information_schema.KEY_COLUMN_USAGE AS kcu
INNER JOIN information_schema.REFERENTIAL_CONSTRAINTS AS rc ON kcu.CONSTRAINT_NAME = rc.CONSTRAINT_NAME
WHERE
  ( kcu.TABLE_SCHEMA = {$database} AND kcu.TABLE_NAME = '{$table}' ) OR
  ( kcu.REFERENCED_TABLE_SCHEMA = {$database} AND kcu.REFERENCED_TABLE_NAME = '{$table}' )
SQL;
        $transform = function($row) use ($relations, $columns, $table) {
            $this->addRelationInformationToList(
                $relations,
                $table,
                $columns,
                $row['name'],
                $row['localTable'],
                $row['localColumn'],
                $row['foreignTable'],
                $row['foreignColumn']
            );
        };

        $statement = $this->database->getConnectionSingleton()->query($sql);
        $statement->fetchAllTransform($transform, $statement::FETCH_ASSOC);

        return $relations;
    }
}
