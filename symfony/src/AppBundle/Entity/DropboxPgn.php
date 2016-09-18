<?php

namespace AppBundle\Entity;
use Ramsey\Uuid\Uuid;
use Symfony\Component\Validator\Constraints\NotBlank;
use AppBundle\Validator\Constraints\Pgn;
use Doctrine\ORM\Mapping\Entity;
use Doctrine\ORM\Mapping\Id;
use Doctrine\ORM\Mapping\Column;
use Doctrine\ORM\Mapping\GeneratedValue;
use Doctrine\ORM\Mapping\CustomIdGenerator;
use Doctrine\ORM\Mapping\OneToOne;
use Doctrine\ORM\Mapping\JoinColumn;

/**
 * @Entity(repositoryClass="AppBundle\Entity\Repository\DropboxPgnRepository")
 */
class DropboxPgn
{
    /**
     * @var Uuid
     *
     * @Id
     * @Column(type="uuid")
     * @GeneratedValue(strategy="CUSTOM")
     * @CustomIdGenerator(class="Ramsey\Uuid\Doctrine\UuidGenerator")
     */
    private $uuid;

    /**
     * @var string
     *
     * @Column
     * @NotBlank
     */
    private $path;

    /**
     * @var ImportPgn
     *
     * @OneToOne(targetEntity="ImportPgn")
     * @JoinColumn(name="original_pgn", referencedColumnName="uuid")
     */
    private $importPgn;

    /**
     * DropboxPgn constructor.
     * @param string $path
     * @param ImportPgn $importPgn
     */
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