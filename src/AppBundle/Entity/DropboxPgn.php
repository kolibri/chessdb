<?php declare(strict_types = 1);

namespace AppBundle\Entity;

use Ramsey\Uuid\Uuid;

class DropboxPgn
{
    /** @var Uuid */
    private $uuid;
    private $path;
    private $importPgn;

    public function __construct(string $path, ImportPgn $importPgn)
    {
        $this->path = $path;
        $this->importPgn = $importPgn;
    }

    public function getUuid(): Uuid
    {
        return $this->uuid;
    }

    public function getPath(): string
    {
        return $this->path;
    }

    public function setPath(string $path)
    {
        $this->path = $path;
    }

    public function getImportPgn(): ImportPgn
    {
        return $this->importPgn;
    }

    public function setImportPgn(ImportPgn $importPgn)
    {
        $this->importPgn = $importPgn;
    }
}
