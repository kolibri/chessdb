<?php declare(strict_types = 1);

namespace AppBundle\Dropbox;

use Dropbox\AppInfo;
use Dropbox\WebAuth;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Routing\Generator\UrlGeneratorInterface;
use Symfony\Component\Routing\RouterInterface;

class AuthHelper
{
    private $routing;
    private $appInfo;
    private $authTokenStore;
    private $clientIdentifier;
    private $redirectRouteName;
    private $accessTokenStore;

    public function __construct(
        RouterInterface $routing,
        AppInfo $appInfo,
        AuthTokenStore $authTokenStore,
        $clientIdentifier,
        $redirectRouteName,
        AccessTokenStore $accessTokenStore
    ) {
        $this->routing = $routing;
        $this->appInfo = $appInfo;
        $this->authTokenStore = $authTokenStore;
        $this->clientIdentifier = $clientIdentifier;
        $this->redirectRouteName = $redirectRouteName;
        $this->accessTokenStore = $accessTokenStore;
    }

    public function getStartUrl(): array
    {
        return $this->getWebAuth()->start();
    }

    public function finish(Request $request)
    {
        $params = [
            'state' => $request->query->get('state'),
            'error' => $request->query->get('error'),
            'error_description' => $request->query->get('error_description'),
            'code' => $request->query->get('code'),
        ];

        $result = $this->getWebAuth()->finish($params);

        $this->accessTokenStore->set($result[0]);
    }

    private function getWebAuth(): WebAuth
    {
        return new WebAuth(
            $this->appInfo,
            $this->clientIdentifier,
            $this->routing->generate($this->redirectRouteName, [], UrlGeneratorInterface::ABSOLUTE_URL),
            $this->authTokenStore
        );
    }
}
