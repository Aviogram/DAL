<?php
namespace Aviogram\DAL\Databases\Shared;

interface ConstantInterface
{
    /**
     * Represents a boolean data type.
     */
    const PARAM_BOOL = 5;

    /**
     * Represents the SQL NULL data type.
     */
    const PARAM_NULL = 0;

    /**
     * Represents the SQL INTEGER data type.
     */
    const PARAM_INT = 1;

    /**
     * Represents the SQL CHAR, VARCHAR, or other string data type.
     */
    const PARAM_STR = 2;

    /**
     * Represents the SQL large object data type.
     */
    const PARAM_LOB = 3;

    /**
     * Represents a recordset type. Not currently supported by any drivers.
     */
    const PARAM_STMT = 4;

    /**
     * Specifies that the parameter is an INOUT parameter for a stored
     * procedure. You must bitwise-OR this value with an explicit
     * PDO::PARAM_* data type.
     */
    const PARAM_INPUT_OUTPUT = 2147483648;

    /**
     * Allocation event
     */
    const PARAM_EVT_ALLOC = 0;

    /**
     * Deallocation event
     */
    const PARAM_EVT_FREE = 1;

    /**
     * Event triggered prior to execution of a prepared statement.
     */
    const PARAM_EVT_EXEC_PRE = 2;

    /**
     * Event triggered subsequent to execution of a prepared statement.
     */
    const PARAM_EVT_EXEC_POST = 3;

    /**
     * Event triggered prior to fetching a result from a resultset.
     */
    const PARAM_EVT_FETCH_PRE = 4;

    /**
     * Event triggered subsequent to fetching a result from a resultset.
     */
    const PARAM_EVT_FETCH_POST = 5;

    /**
     * Event triggered during bound parameter registration
     * allowing the driver to normalize the parameter name.
     */
    const PARAM_EVT_NORMALIZE = 6;

    /**
     * Specifies that the fetch method shall return each row as an object with
     * variable names that correspond to the column names returned in the result
     * set. <b>Constant::FETCH_LAZY</b> creates the object variable names as they are accessed.
     * Not valid inside <b>StatementInterface::fetchAll</b>.
     */
    const FETCH_LAZY = 1;

    /**
     * Specifies that the fetch method shall return each row as an array indexed
     * by column name as returned in the corresponding result set. If the result
     * set contains multiple columns with the same name,
     * <b>PDO::FETCH_ASSOC</b> returns
     * only a single value per column name.
     */
    const FETCH_ASSOC = 2;

    /**
     * Specifies that the fetch method shall return each row as an array indexed
     * by column number as returned in the corresponding result set, starting at
     * column 0.
     */
    const FETCH_NUM = 3;

    /**
     * Specifies that the fetch method shall return each row as an array indexed
     * by both column name and number as returned in the corresponding result set,
     * starting at column 0.
     */
    const FETCH_BOTH = 4;

    /**
     * Specifies that the fetch method shall return each row as an object with
     * property names that correspond to the column names returned in the result
     * set.
     */
    const FETCH_OBJ = 5;

    /**
     * Specifies that the fetch method shall return TRUE and assign the values of
     * the columns in the result set to the PHP variables to which they were
     * bound with the <b>StatementInterface::bindParam</b> or
     * <b>StatementInterface::bindColumn</b> methods.
     */
    const FETCH_BOUND = 6;

    /**
     * Specifies that the fetch method shall return only a single requested
     * column from the next row in the result set.
     */
    const FETCH_COLUMN = 7;

    /**
     * Specifies that the fetch method shall return a new instance of the
     * requested class, mapping the columns to named properties in the class.
     * The magic
     * <b>__set</b>
     * method is called if the property doesn't exist in the requested class
     */
    const FETCH_CLASS = 8;

    /**
     * Specifies that the fetch method shall update an existing instance of the
     * requested class, mapping the columns to named properties in the class.
     */
    const FETCH_INTO = 9;

    /**
     * Allows completely customize the way data is treated on the fly (only
     * valid inside <b>StatementInterface::fetchAll</b>).
     */
    const FETCH_FUNC = 10;

    /**
     * Group return by values. Usually combined with
     * <b>Constant::FETCH_COLUMN</b> or
     * <b>Constant::FETCH_KEY_PAIR</b>.
     */
    const FETCH_GROUP = 65536;

    /**
     * Fetch only the unique values.
     */
    const FETCH_UNIQUE = 196608;

    /**
     * Fetch a two-column result into an array where the first column is a key and the second column
     * is the value. Available since PHP 5.2.3.
     */
    const FETCH_KEY_PAIR = 12;

    /**
     * Determine the class name from the value of first column.
     */
    const FETCH_CLASSTYPE = 262144;

    /**
     * As <b>PDO::FETCH_INTO</b> but object is provided as a serialized string.
     * Available since PHP 5.1.0. Since PHP 5.3.0 the class constructor is never called if this
     * flag is set.
     */
    const FETCH_SERIALIZE = 524288;

    /**
     * Call the constructor before setting properties
     */
    const FETCH_PROPS_LATE = 1048576;

    /**
     * Specifies that the fetch method shall return each row as an array indexed
     * by column name as returned in the corresponding result set. If the result
     * set contains multiple columns with the same name,
     * <b>PDO::FETCH_NAMED</b> returns
     * an array of values per column name.
     */
    const FETCH_NAMED = 11;
}
