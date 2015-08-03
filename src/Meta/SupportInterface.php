<?php
namespace Aviogram\DAL\Meta;

interface SupportInterface
{
    /**
     * If the database supports affected rows after insert/update/delete
     *
     * @return boolean
     */
    public function affectedRows();

    /**
     * If the database supports transactions
     *
     * @return boolean
     */
    public function transactions();

    /**
     * If the database supports save points
     *
     * @return boolean
     */
    public function savePoints();

    /**
     * If the database supports primary keys. An unique column
     *
     * @return boolean
     */
    public function primaryConstraint();

    /**
     * If the database supports foreign keys
     *
     * @return boolean
     */
    public function foreignKeyConstraints();
}
