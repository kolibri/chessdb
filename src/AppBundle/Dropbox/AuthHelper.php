<?php


namespace AppBundle\Dropbox;

use Dropbox\AppInfo;
use Dropbox\WebAuth;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Routing\Generator\UrlGeneratorInterface;
use Symfony\Component\Routing\RouterInterface;

class AuthHelper
{
    /** @var  RouterInterface */
    private $routing;

    /** @var  AppInfo */
    private $appInfo;

    /** @var AuthTokenStore */
    private $authTokenStore;

    /** @var string */
    private $clientIdentifier;

    /** @var string */
    private $redirectRouteName;

    /** @var AccessTokenStore */
    private $accessTokenStore;

    /**
     * AuthHelper constructor.
     * @param RouterInterface $routing
     * @param AppInfo $appInfo
     * @param AuthTokenStore $authTokenStore
     * @param $clientIdentifier
     * @param $redirectRouteName
     * @param AccessTokenStore $accessTokenStore
     */
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

    public function getStartUrl()
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

    private function getWebAuth()
    {
        return new WebAuth(
            $this->appInfo,
            $this->clientIdentifier,
            $this->routing->generate($this->redirectRouteName, [], UrlGeneratorInterface::ABSOLUTE_URL),
            $this->authTokenStore
        );
    }
}
