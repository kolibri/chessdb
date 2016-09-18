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

/**
 * @Entity(repositoryClass="AppBundle\Entity\Repository\ImportPgnRepository")
 */
class ImportPgn
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
     * @Column(type="text")
     * @NotBlank
     * @Pgn
     */
    private $pgnString;

    /**
     * @var boolean
     *
     * @Column(type="boolean", nullable=true)
     */
    private $imported;

    /**
     * ImportedPgn constructor.
     * @param string $pgnString
     */
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