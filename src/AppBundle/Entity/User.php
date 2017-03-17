<?php declare(strict_types = 1);

namespace AppBundle\Entity;

use Symfony\Component\Security\Core\User\AdvancedUserInterface;
use Ramsey\Uuid\Uuid;

class User implements AdvancedUserInterface
{
    /** @var Uuid */
    private $uuid;

    private $username;
    private $password;
    private $roles = [];
    private $emailAddress;
    private $playerAliases;
    private $isEnabled;
    private $rawPassword;

    public static function register(
        string $username,
        string $emailAddress,
        string $rawPassword,
        array $playerAliases = []
    ) {
        $user = new self();
        $user->setUsername($username);
        $user->setEmailAddress($emailAddress);
        $user->setRawPassword($rawPassword);
        $user->setRoles(['ROLE_PLAYER']);
        $user->setPlayerAliases(array_merge($playerAliases, [$username]));
        $user->setIsEnabled(false);

        return $user;
    }

    public function getUuid(): Uuid
    {
        return $this->uuid;
    }

    public function getUsername(): string
    {
        return $this->username;
    }

    public function setUsername(string $username)
    {
        $this->username = $username;
    }

    public function getPassword(): string
    {
        return $this->password;
    }

    public function setPassword(string $password)
    {
        $this->password = $password;
    }

    public function getRoles(): array
    {
        return $this->roles;
    }

    public function setRoles(array $roles)
    {
        foreach ($roles as $role) {
            $this->addRole($role);
        }
    }

    public function addRole(string $role)
    {
        $this->roles[] = $role;
    }

    public function getEmailAddress(): string
    {
        return $this->emailAddress;
    }

    public function setEmailAddress(string $emailAddress)
    {
        $this->emailAddress = $emailAddress;
    }

    public function getRawPassword()
    {
        return $this->rawPassword;
    }

    public function setRawPassword(string $rawPassword)
    {
        $this->rawPassword = $rawPassword;
    }

    public function getPlayerAliases(): array
    {
        return $this->playerAliases;
    }

    public function setPlayerAliases(array $playerAliases)
    {
        $this->playerAliases = $playerAliases;
    }

    public function isEnabled(): bool
    {
        return $this->isEnabled;
    }

    public function setIsEnabled(bool $isEnabled)
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

    public function isAccountNonExpired(): bool
    {
        return true;
    }

    public function isAccountNonLocked(): bool
    {
        return true;
    }

    public function isCredentialsNonExpired(): bool
    {
        return true;
    }
}
