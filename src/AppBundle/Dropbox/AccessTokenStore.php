<?php declare(strict_types = 1);

namespace AppBundle\Dropbox;

use Symfony\Component\HttpFoundation\Session\SessionInterface;

class AccessTokenStore
{
    const TOKEN_NAME = 'dropbox-access-token';

    private $session;

    public function __construct(SessionInterface $session)
    {
        $this->session = $session;
    }

    public function get(): string
    {
        return $this->session->get(self::TOKEN_NAME);
    }

    public function set(string $value)
    {
        $this->session->set(self::TOKEN_NAME, $value);
    }

    public function clear()
    {
        $this->session->remove(self::TOKEN_NAME);
    }
}
