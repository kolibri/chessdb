<?php

namespace AppBundle\Entity;

use Doctrine\Common\Collections\ArrayCollection;
use Symfony\Component\Security\Core\User\UserInterface;
use Ramsey\Uuid\Uuid;
use Symfony\Component\Validator\Constraints\NotBlank;
use Symfony\Component\Validator\Constraints\Length;
use Symfony\Component\Validator\Constraints\Regex;
use Symfony\Component\Validator\Constraints\Email;
use Symfony\Bridge\Doctrine\Validator\Constraints\UniqueEntity;
use Doctrine\ORM\Mapping\Entity;
use Doctrine\ORM\Mapping\Id;
use Doctrine\ORM\Mapping\Column;
use Doctrine\ORM\Mapping\GeneratedValue;
use Doctrine\ORM\Mapping\CustomIdGenerator;
use Doctrine\ORM\Mapping\Table;
use Doctrine\ORM\Mapping\UniqueConstraint;

/**
 * @Entity(repositoryClass="AppBundle\Entity\Repository\UserRepository")
 * @Table(
 *     uniqueConstraints={
 *         @UniqueConstraint(name="user_username_unique", columns="username"),
 *         @UniqueConstraint(name="user_email_unique", columns="email_address"),
 *     }
 * )
 * @UniqueEntity("username")
 * @UniqueEntity("emailAddress")
 */
class User implements UserInterface
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
     * @NotBlank(groups={"registration"})
     * @Length(min=4, max=25, groups={"registration"})
     * @Regex(pattern="/^[a-z0-9]+$/i", groups={"registration"})
     */
    private $username;

    /**
     * @var string
     *
     * @Column
     */
    private $password;

    /**
     * @var array
     *
     * @Column(type="simple_array")
     * @NotBlank
     */
    private $roles = [];

    /**
     * @var string
     *
     * @Column
     * @NotBlank(groups={"registration"})
     * @Email(groups={"registration"})
     */
    private $emailAddress;

    /**
     * @var array
     *
     * @Column(type="simple_array", nullable=true)
     */
    private $playerAliases;

    /**
     * @var string
     *
     * @NotBlank(groups={"registration"})
     * @Length(min=6, groups={"registration"})
     */
    private $rawPassword;

    /**
     * User constructor.
     */
    public function __construct()
    {
    }

    public static function register($username, $emailAddress, $rawPassword, $playerAliases = null)
    {
        $user = new self();
        $user->setUsername($username);
        $user->setEmailAddress($emailAddress);
        $user->setRawPassword($rawPassword);
        $user->setRoles(['ROLE_PLAYER']);
        $user->setPlayerAliases(is_array($playerAliases) ? $playerAliases : [$username]);

        return $user;
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
    public function getUsername()
    {
        return $this->username;
    }

    /**
     * @param string $username
     */
    public function setUsername($username)
    {
        $this->username = $username;
    }

    /**
     * @return string
     */
    public function getPassword()
    {
        return $this->password;
    }

    /**
     * @param string $password
     */
    public function setPassword($password)
    {
        $this->password = $password;
    }

    /**
     * @return array
     */
    public function getRoles()
    {
        return $this->roles;
    }

    /**
     * @param array $roles
     */
    public function setRoles($roles)
    {
        foreach ($roles as $role) {
            $this->addRole($role);
        }
    }

    public function addRole($role)
    {
        $this->roles[] = $role;
    }

    /**
     * @return string
     */
    public function getEmailAddress()
    {
        return $this->emailAddress;
    }

    /**
     * @param string $emailAddress
     */
    public function setEmailAddress($emailAddress)
    {
        $this->emailAddress = $emailAddress;
    }

    /**
     * @return string
     */
    public function getRawPassword()
    {
        return $this->rawPassword;
    }

    /**
     * @param string $rawPassword
     */
    public function setRawPassword($rawPassword)
    {
        $this->rawPassword = $rawPassword;
    }

    /**
     * @return array
     */
    public function getPlayerAliases()
    {
        return $this->playerAliases;
    }

    /**
     * @param array $playerAliases
     */
    public function setPlayerAliases($playerAliases)
    {
        $this->playerAliases = $playerAliases;
    }

    public function getSalt()
    {
    }

    public function eraseCredentials()
    {
        $this->setRawPassword('');
    }
}
