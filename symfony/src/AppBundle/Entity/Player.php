<?php


namespace AppBundle\Entity;

use Ramsey\Uuid\Uuid;
use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Validator\Constraints as Assert;


/**
 * @ORM\Entity(repositoryClass="PlayerRepository")
 */
class Player
{
    /**
     * @var Uuid
     *
     * @ORM\Id
     * @ORM\Column(type="uuid")
     * @ORM\GeneratedValue(strategy="CUSTOM")
     * @ORM\CustomIdGenerator(class="Ramsey\Uuid\Doctrine\UuidGenerator")
     */
    private $uuid;

    /**
     * @var string
     *
     * @ORM\Column
     * @Assert\NotBlank
     */
    private $name;

    /**
     * @var User
     *
     * @ORM\ManyToOne(targetEntity="User", inversedBy="players")
     * @ORM\JoinColumn(name="user_uuid", referencedColumnName="uuid")
     */
    private $user;

    /**
     * Player constructor.
     * @param string $name
     */
    public function __construct($name)
    {
        $this->name = $name;
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
    public function getName()
    {
        return $this->name;
    }

    /**
     * @return User
     */
    public function getUser()
    {
        return $this->user;
    }

    /**
     * @param User $user
     */
    public function setUser(User $user)
    {
        $this->user = $user;
    }

    public function removeUser()
    {
        $this->user = null;
    }
}