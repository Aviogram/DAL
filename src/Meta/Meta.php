<?php
namespace Aviogram\DAL\Meta;

use Aviogram\DAL\Databases\Shared\DatabaseInterface;

class Meta
{
    /**
     * @var DatabaseInterface
     */
    protected $database;

    /**
     * @var SupportInterface
     */
    protected $support;

    /**
     * @var Type
     */
    protected $type;

    /**
     * @param DatabaseInterface $database
     * @param SupportInterface  $support
     * @param Type              $type
     */
    public function __construct(
        DatabaseInterface $database,
        SupportInterface  $support,
        Type              $type
    ) {
        $this->database = $database;
        $this->support  = $support;
        $this->type     = $type;
    }

    /**
     * @return SupportInterface
     */
    public function getSupportSettings()
    {
        return $this->support;
    }

    /**
     * @return Type
     */
    public function getType()
    {
        return $this->type;
    }
}
