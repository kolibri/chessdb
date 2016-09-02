<?php

namespace AppBundle\Controller;

use AppBundle\Form\UserRegistrationType;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\Request;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Template;

class SecurityController extends Controller
{
    /**
     * @Route("/register")
     * @Template("security/register.html.twig")
     */
    public function registerAction(Request $request)
    {
        $form = $this->createForm(UserRegistrationType::class);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $this->get('app.form.user_registration_handler')->handle($form->getData());

            return $this->redirect($this->generateUrl('homepage'));
        }

        return ['form' => $form->createView()];
    }
}
