<?php


namespace AppBundle\Dropbox;

use Dropbox\ValueStore;
use Symfony\Component\HttpFoundation\Session\SessionInterface;

class AuthTokenStore implements ValueStore
{
    const TOKEN_NAME = 'dropbox-auth-token';
    
    /** @var SessionInterface */
    private $session;

    /**
     * AuthTokenStore constructor.
     * @param $session
     */
    public function __construct($session)
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
