<?php

namespace AppBundle\Controller;

use AppBundle\User\UserRegistrationType;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\Request;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Template;

class SecurityController extends Controller
{
    /**
     * @Route("/register")
     * @Template("security/register.html.twig")
     *
     */
    public function registerAction(Request $request)
    {
        $form = $this->createForm(UserRegistrationType::class, null, ['validation_groups' => array('registration')]);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $this->get('app.user.registration_handler')->handleRegistration($form->getData());

            return $this->redirect($this->generateUrl('homepage'));
        }

        return ['form' => $form->createView()];
    }

    /**
     * @Route("/login")
     * @Template("security/login.html.twig")
     */
    public function loginAction(Request $request)
    {
        $authenticationUtils = $this->get('security.authentication_utils');

        // get the login error if there is one
        $error = $authenticationUtils->getLastAuthenticationError();

        // last username entered by the user
        $lastUsername = $authenticationUtils->getLastUsername();

        return [
                'last_username' => $lastUsername,
                'error'         => $error,
        ];
    }
}
