<?php declare(strict_types = 1);

namespace AppBundle\Controller;

use AppBundle\Entity\Repository\GameRepository;
use AppBundle\Entity\Repository\UserRepository;
use AppBundle\Entity\User;
use AppBundle\Form\Type\UserProfileType;
use AppBundle\Helper\RegistrationHelper;
use Symfony\Component\Form\FormFactoryInterface;
use Symfony\Component\HttpFoundation\RedirectResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Generator\UrlGeneratorInterface;
use Symfony\Component\Security\Core\Authentication\Token\Storage\TokenStorageInterface;
use Symfony\Component\Security\Http\Authentication\AuthenticationUtils;

class UserController
{
    private $gameRepository;
    private $userRepository;
    private $registrationHelper;
    private $authUtils;
    private $router;
    private $formFactory;
    private $tokenStorage;
    private $twig;

    public function __construct(
        GameRepository $gameRepository,
        UserRepository $userRepository,
        RegistrationHelper $registrationHelper,
        AuthenticationUtils $authUtils,
        UrlGeneratorInterface $router,
        FormFactoryInterface $formFactory,
        TokenStorageInterface $tokenStorage,
        \Twig_Environment $twig
    ) {
        $this->gameRepository = $gameRepository;
        $this->userRepository = $userRepository;
        $this->registrationHelper = $registrationHelper;
        $this->authUtils = $authUtils;
        $this->router = $router;
        $this->formFactory = $formFactory;
        $this->tokenStorage = $tokenStorage;
        $this->twig = $twig;
    }

    public function login()
    {
        return new Response(
            $this->twig->render(
                'user/login.html.twig',
                [
                    'last_username' => $this
                        ->authUtils
                        ->getLastUsername(),
                    'error' => $this
                        ->authUtils
                        ->getLastAuthenticationError(),
                ]
            )
        );
    }

    public function myProfile(Request $request)
    {
        $user = $this->tokenStorage->getToken()->getUser();


        if (!$user instanceof User) {
            return new RedirectResponse($this->router->generate('app_user_login'));
        }

        $form = $this->formFactory->create(
            UserProfileType::class,
            $user,
            ['validation_groups' => ["profile"]]
        );
        $form->handleRequest($request);

        if ($form->isValid()) {
            if (null !== $user->getRawPassword()) {
                $user = $this
                    ->registrationHelper
                    ->encodePassword($user);
            }

            $this
                ->userRepository
                ->save($user);

            return new RedirectResponse(
                $this
                    ->router
                    ->generate('app_user_myprofile')
            );
        }

        return new Response(
            $this
                ->twig
                ->render(
                    'user/my-profile.html.twig',
                    [
                        'form' => $form->createView(),
                        'gamesByResult' => $this
                            ->gameRepository
                            ->findByPlayerGroupByResult($user),
                    ]
                )
        );
    }

    public function register(Request $request)
    {
        $form = $this->formFactory->create(UserProfileType::class);
        $form->handleRequest($request);

        if ($form->isValid()) {
            /** @var User $user */
            $user = $form->getData();
            $this
                ->registrationHelper
                ->encodePasswordAndSave($user);

            return new RedirectResponse(
                $this
                    ->router
                    ->generate('app_user_login')
            );
        }

        return new Response(
            $this
                ->twig
                ->render(
                    'user/register.html.twig',
                    ['form' => $form->createView()]
                )
        );
    }
}
