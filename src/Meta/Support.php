<?php
namespace Aviogram\DAL\Meta;

class Support implements SupportInterface
{
    /**
     * If the database supports affected rows after insert/update/delete
     *
     * @return boolean
     */
    public function affectedRows()
    {
        return true;
    }

    /**
     * If the database supports transactions
     *
     * @return boolean
     */
    public function transactions()
    {
        return true;
    }

    /**
     * If the database supports save points
     *
     * @return boolean
     */
    public function savePoints()
    {
        return true;
    }

    /**
     * If the database supports primary keys. An unique column
     *
     * @return boolean
     */
    public function primaryConstraint()
    {
        return true;
    }

    /**
     * If the database supports foreign keys
     *
     * @return boolean
     */
    public function foreignKeyConstraints()
    {
        return true;
    }
}
