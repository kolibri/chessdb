<?php

namespace AppBundle\Controller;

use AppBundle\Entity\Game;
use AppBundle\Entity\User;
use AppBundle\Form\Type\UserProfileType;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\Request;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;

class UserController extends Controller
{
    /**
     * @Route("/login")
     */
    public function loginAction(Request $request)
    {
        $authenticationUtils = $this->get('security.authentication_utils');

        return $this->render(
            'user/login.html.twig',
            [
                'last_username' => $authenticationUtils->getLastUsername(),
                'error' => $authenticationUtils->getLastAuthenticationError(),
            ]
        );
    }

    /**
     * @Route("/my-profile")
     */
    public function myProfileAction(Request $request)
    {
        $user = $this->getUser();

        if (!$user instanceof User) {
            return $this->redirectToRoute('app_user_login');
        }

        $form = $this->createForm(UserProfileType::class, $user);
        $form->handleRequest($request);

        if ($form->isValid()) {
            $manager = $this->getDoctrine()->getManager();
            $manager->persist($user);
            $manager->flush();

            return $this->redirectToRoute('app_user_myprofile');
        }

        $gamesByResult = $this
            ->getDoctrine()
            ->getRepository(Game::class)
            ->findByPlayerGroupByResult($user->getUsername());

        return $this->render(
            'user/my-profile.html.twig',
            [
                'form' => $form->createView(),
                'gamesByResult' => $gamesByResult,
            ]
        );
    }

    /**
     * @Route("/profiles")
     */
    public function listAction()
    {
        $userRepository = $this->getDoctrine()->getRepository(User::class);

        return $this->render(
            'user/list.html.twig',
            [
                'users' => $userRepository->findAll(),
            ]
        );
    }

    //* #Route("/profile/{player}", requirements={"player": "/^[a-zA-Z0-9_.-\s]*$/"})
    /**
     * @Route("/profile/{player}")
     *
     */
    public function showAction($player)
    {
        $games = $this
            ->getDoctrine()
            ->getRepository(Game::class)
            ->findByPlayerGroupByResult($player);

        return $this->render(
            'user/show.html.twig',
            [
                'player' => $player,
                'gamesByResult' => $games,
            ]
        );
    }

    /**
     * @Route("/profile/{player1}/vs/{player2}")
     */
    public function versusAction($player1, $player2)
    {
        $games = $this
            ->getDoctrine()
            ->getRepository(Game::class)
            ->findByPlayerVsPlayerGroupByResult($player1, $player2);

        return $this->render(
            'user/versus.html.twig',
            [
                'player1' => $player1,
                'player2' => $player2,
                'gamesByResult' => $games,
            ]
        );
    }
}
