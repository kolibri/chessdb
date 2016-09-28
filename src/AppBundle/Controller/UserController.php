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

        $form = $this->createForm(UserProfileType::class, $user, ['validation_groups' => ["profile"]]);
        $form->handleRequest($request);

        if ($form->isValid()) {
            if (null !== $user->getRawPassword()) {
                $user = $this
                    ->get('app.helper.registration_helper')
                    ->encodePassword($user);
            }

            $this
                ->getDoctrine()
                ->getRepository(User::class)
                ->save($user)
            ;

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

    /**
     * @Route("/register")
     * @Method({"GET", "POST"})
     */
    public function registerAction(Request $request)
    {
        $form = $this->createForm(UserProfileType::class);
        $form->handleRequest($request);

        if ($form->isValid()) {
            /** @var User $user */
            $user = $form->getData();
            $this
                ->get('app.helper.registration_helper')
                ->encodePasswordAndSave($user);

            $message = new \Swift_Message();
            $message->setTo($this->getParameter('admin_mail'));
            $message->setFrom($this->getParameter('admin_mail'));
            $message->setSubject('New user in chessdb');
            $message->setBody($this->renderView('user/register_mail.txt.twig', ['user' => $user]));

            $this
                ->get('mailer')
                ->send($message);

            return $this->redirectToRoute('app_user_login');
        }

        return $this->render(
            'user/register.html.twig',
            ['form' => $form->createView()]
        );
    }
}
