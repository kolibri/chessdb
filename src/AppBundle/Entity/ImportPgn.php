<?php declare(strict_types = 1);

namespace AppBundle\Entity;

use Ramsey\Uuid\Uuid;

class ImportPgn
{
    /** @var Uuid */
    private $uuid;

    private $pgnString;
    private $imported;
    private $user;

    public function __construct(string $pgnString, User $user)
    {
        $this->pgnString = $pgnString;
        $this->imported = false;
        $this->user = $user;
    }

    public function getUuid(): Uuid
    {
        return $this->uuid;
    }

    public function setPgnString(string $pgnString)
    {
        $this->pgnString = $pgnString;
    }

    public function getPgnString(): string
    {
        return $this->pgnString;
    }

    public function isImported(): bool
    {
        return $this->imported;
    }

    public function markAsImported()
    {
        $this->imported = true;
    }

    public function getUser(): User
    {
        return $this->user;
    }
}
