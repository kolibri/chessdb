<?php

namespace AppBundle\Dropbox;

use Symfony\Component\HttpFoundation\RedirectResponse;
use Symfony\Component\HttpKernel\Event\GetResponseEvent;
use Symfony\Component\Routing\RouterInterface;

class ClientListener
{
    /** @var  AccessTokenStore */
    private $accessTokenStore;


    /** @var RouterInterface */
    private $routing;

    private $authRouteName;

    public function onKernelRequest(GetResponseEvent $event)
    {
//        $event->getRequest();
//        AccessTokenStore

        if (null === $this->accessTokenStore->get()) {
            $event->setResponse(
                new RedirectResponse(
                    $this
                        ->routing
                        ->generate($this->authRouteName)
                )
            );

//            return $this->redirectToRoute('app_dropbox_authstart');
        }
    }
}
