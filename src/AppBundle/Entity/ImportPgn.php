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

    /** @var User */
    private $user;

    public function __construct($pgnString, User $user)
    {
        $this->pgnString = $pgnString;
        $this->imported = false;
        $this->user = $user;
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

    /**
     * @return User
     */
    public function getUser()
    {
        return $this->user;
    }
}
