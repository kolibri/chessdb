<?php


namespace AppBundle\Dropbox;

use Dropbox\Client as DropboxClient;

class ClientCreator
{
    /** @var AccessTokenStore */
    private $tokenStorage;
    
    /** @var string */
    private $clientIdentifier;

    /**
     * ClientCreator constructor.
     * @param AccessTokenStore $tokenStorage
     * @param string $clientIdentifier
     */
    public function __construct(AccessTokenStore $tokenStorage, $clientIdentifier)
    {
        $this->tokenStorage = $tokenStorage;
        $this->clientIdentifier = $clientIdentifier;
    }

    /**
     * @return Client
     */
    public function createClient()
    {
        $dropboxClient = new DropboxClient(
            $this->tokenStorage->get(),
            $this->clientIdentifier
        );

        return new Client($dropboxClient);
    }
}