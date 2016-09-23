<?php


namespace AppBundle\Entity;

use Ramsey\Uuid\Uuid;

class ImportPgn
{
    /** @var Uuid */
    private $uuid;

    /** @var string */
    private $pgnString;

    /** @var boolean */
    private $imported;

    public function __construct($pgnString)
    {
        $this->pgnString = $pgnString;
        $this->imported = false;
    }

    /**
     * @return Uuid
     */
    public function getUuid()
    {
        return $this->uuid;
    }

    /**
     * @return string
     */
    public function getPgnString()
    {
        return $this->pgnString;
    }

    /**
     * @param string $pgnString
     */
    public function setPgnString($pgnString)
    {
        $this->pgnString = $pgnString;
    }

    /**
     * @return boolean
     */
    public function isImported()
    {
        return $this->imported;
    }

    /**
     * @param boolean $imported
     */
    public function setImported($imported)
    {
        $this->imported = $imported;
    }
}
