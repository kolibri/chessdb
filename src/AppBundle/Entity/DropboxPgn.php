<?php

namespace AppBundle\Entity;

use Ramsey\Uuid\Uuid;

class DropboxPgn
{
    /** @var Uuid */
    private $uuid;

    /** @var string */
    private $path;

    /** @var ImportPgn */
    private $importPgn;

    public function __construct($path, ImportPgn $importPgn)
    {
        $this->path = $path;
        $this->importPgn = $importPgn;
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
    public function getPath()
    {
        return $this->path;
    }

    /**
     * @param string $path
     */
    public function setPath($path)
    {
        $this->path = $path;
    }

    /**
     * @return ImportPgn
     */
    public function getImportPgn()
    {
        return $this->importPgn;
    }

    /**
     * @param ImportPgn $importPgn
     */
    public function setImportPgn($importPgn)
    {
        $this->importPgn = $importPgn;
    }
}
