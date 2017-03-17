<?php declare(strict_types = 1);

namespace AppBundle\Entity;

use Ramsey\Uuid\Uuid;
use Symfony\Component\Security\Core\User\AdvancedUserInterface;

class User implements AdvancedUserInterface
{
    private $uuid;
    private $username;
    private $password;
    private $roles = [];
    private $emailAddress;
    private $playerAliases;
    private $isEnabled;
    private $rawPassword;

    private function __construct(
        string $username,
        string $emailAddress,
        string $rawPassword,
        array $playerAliases = []
    ) {
        $this->username = $username;
        $this->emailAddress = $emailAddress;
        $this->rawPassword = $rawPassword;
        $this->roles = ['ROLE_PLAYER'];
        $this->playerAliases = array_merge($playerAliases, [$username]);
        $this->isEnabled = false;
    }

    public static function register(
        string $username,
        string $emailAddress,
        string $rawPassword,
        array $playerAliases = []
    ): self {
        return new self($username, $emailAddress, $rawPassword, $playerAliases);
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

    public function enable()
    {
        $this->isEnabled = true;
    }

    public function disable()
    {
        $this->isEnabled = false;
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
