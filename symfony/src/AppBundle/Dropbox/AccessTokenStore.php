<?php

namespace AppBundle\Dropbox;

use Symfony\Component\HttpFoundation\Session\Session;
use Symfony\Component\HttpFoundation\Session\SessionInterface;
use Symfony\Component\Security\Core\Authentication\Token\Storage\TokenStorageInterface;

class AccessTokenStore
{    const TOKEN_NAME = 'dropbox-access-token';

    /** @var Session */
    private $session;

    /**
     * DropboxValueStore constructor.
     * @param $session
     */
    public function __construct($session)
    {
        $this->session = $session;
    }

   function get()
    {
        return $this->session->get(self::TOKEN_NAME);
    }

    function set($value)
    {
        $this->session->set(self::TOKEN_NAME, $value);
    }

    function clear()
    {
        $this->session->remove(self::TOKEN_NAME);
    }
}