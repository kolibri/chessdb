<?php declare(strict_types = 1);

namespace AppBundle\Dropbox;

use Dropbox\ValueStore;
use Symfony\Component\HttpFoundation\Session\SessionInterface;

class AuthTokenStore implements ValueStore
{
    const TOKEN_NAME = 'dropbox-auth-token';

    private $session;

    public function __construct(SessionInterface $session)
    {
        $this->session = $session;
    }

    public function get()
    {
        return $this->session->get(self::TOKEN_NAME);
    }

    public function set($value)
    {
        $this->session->set(self::TOKEN_NAME, $value);
    }

    public function clear()
    {
        $this->session->remove(self::TOKEN_NAME);
    }
}
