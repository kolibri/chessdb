<?php

namespace AppBundle\Controller;

use AppBundle\Entity\User;
use AppBundle\Form\Type\RegistrationsType;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\Request;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Method;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Security;

/**
 * @Route("/admin")
 * @Security("has_role('ROLE_ADMIN')")
 */
class AdminController extends Controller
{
    /**
     * @Route("/registrations")
     * @Method({"GET","POST"})
     */
    public function registrationsAction(Request $request)
    {
        $userRepository = $this->getDoctrine()
            ->getRepository(User::class);

        $users = [
            'inActive' => $userRepository->findByIsEnabled(false),
            'active' => $userRepository->findByIsEnabled(true),
        ];

        $form = $this->createFormBuilder($users)
            ->add('inActive', RegistrationsType::class)
            ->add('active', RegistrationsType::class)
            ->getForm();
        $form->handleRequest($request);

        if ($form->isValid()) {
            foreach ($users as $userList) {
                foreach ($userList as $user) {
                    $userRepository->save($user, false);
                }
            }

            $this
                ->getDoctrine()
                ->getManager()
                ->flush();

            return $this->redirectToRoute('app_admin_registrations');
        }

        return $this->render(
            'admin/registrations.html.twig',
            [
                'form' => $form->createView(),
            ]
        );
    }
}
