<?php
namespace Aviogram\DAL\Databases\Shared;

class Error
{
    /**
     * @var string
     */
    protected $code;

    /**
     * @var array
     */
    protected $info;

    /**
     * Error constructor.
     *
     * @param string $code
     * @param array  $info
     */
    public function __construct($code, array $info)
    {
        $this->code = $code;
        $this->info = $info;
    }

    /**
     * The SQLSTATE error
     *
     * @return string
     */
    public function getCode()
    {
        return $this->code;
    }

    /**
     * Returns extended information about the error
     *
     * @return array
     */
    public function getInfo()
    {
        return $this->info;
    }
}
