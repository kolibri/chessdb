<?php

namespace AppBundle\Entity;

use Symfony\Component\Security\Core\User\AdvancedUserInterface;
use Ramsey\Uuid\Uuid;

class User implements AdvancedUserInterface
{
    /** @var Uuid */
    private $uuid;

    /** @var string */
    private $username;

    /** @var string */
    private $password;

    /** @var array */
    private $roles = [];

    /** @var string */
    private $emailAddress;

    /** @var array */
    private $playerAliases;

    /** @var boolean */
    private $isEnabled;

    /** @var string */
    private $rawPassword;

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
        $user->setIsEnabled(false);

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

    /**
     * @return bool
     */
    public function isEnabled()
    {
        return $this->isEnabled;
    }

    /**
     * @param boolean $isEnabled
     */
    public function setIsEnabled($isEnabled)
    {
        $this->isEnabled = $isEnabled;
    }

    public function getSalt()
    {
    }

    public function eraseCredentials()
    {
        $this->setRawPassword('');
    }

    public function isAccountNonExpired()
    {
        return true;
    }

    public function isAccountNonLocked()
    {
        return true;
    }

    public function isCredentialsNonExpired()
    {
        return true;
    }
}
