<?php
namespace Aviogram\DAL\Databases\Shared;

use Closure;

interface ResultStatementInterface extends ConstantInterface
{
    const STOP_FETCH_ALL_TRANSFORM = 'stopFetchAllTransform';

    /**
     * Set the fetch mode for this statement
     *
     * @param  integer $fetchMode   Use static::FETCH_*
     *
     * @return boolean
     */
    public function setFetchMode($fetchMode);

    /**
     * Get the next row of the resultset
     *
     * @param integer|null $fetchMode   Override the default fetch mode for this iteration
     *
     * @return mixed Returns FALSE when the iteration fails
     */
    public function fetch($fetchMode = null);

    /**
     * Return a list of all the rows in the resultset
     *
     * @param  integer|null $fetchMode Override the default fetch mode for this iteration

     * @return array
     */
    public function fetchAll($fetchMode = null);

    /**
     * Return a single column value from the next row of the resultset
     *
     * @param int $index    The column order is 0 indexed
     *
     * @return mixed Returns FALSE when the iteration fails
     */
    public function fetchColumn($index = 0);

    /**
     * Return the next row of the resultset and apply the transform closure on the row before returning it
     *
     * @param Closure       $transform
     * @param integer|null  $fetchMode
     *
     * @return mixed
     */
    public function fetchTransform(Closure $transform, $fetchMode = null);

    /**
     * Return a list of all the rows in the resultset and apply the transform closure on every row before returning it
     * You can return static::STOP_FETCH_ALL_TRANSFORM when you need to terminate the iteration.
     *
     * @param Closure       $transform
     * @param integer|null  $fetchMode
     *
     * @return array
     */
    public function fetchAllTransform(Closure $transform, $fetchMode = null);
}
