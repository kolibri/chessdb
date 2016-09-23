<?php

namespace AppBundle\Dropbox;

use Symfony\Component\HttpFoundation\Session\SessionInterface;

class AccessTokenStore
{
    const TOKEN_NAME = 'dropbox-access-token';

    /** @var SessionInterface */
    private $session;

    /**
     * DropboxValueStore constructor.
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
