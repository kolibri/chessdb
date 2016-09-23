<?php

namespace AppBundle\Controller;

use AppBundle\Entity\Game;
use AppBundle\Entity\User;
use AppBundle\Form\Type\UserProfileType;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\Request;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Method;

class UserController extends Controller
{
    /**
     * @Route("/login")
     * @Method({"GET"})
     */
    public function loginAction()
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
     * @Method({"GET", "POST"})
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

        return $this->render(
            'user/my-profile.html.twig',
            [
                'form' => $form->createView(),
                'gamesByResult' => $this
                    ->getDoctrine()
                    ->getRepository(Game::class)
                    ->findByPlayerGroupByResult($user->getUsername()),
            ]
        );
    }
}
