<?php declare(strict_types = 1);

namespace AppBundle\Controller;

use AppBundle\Entity\User;
use AppBundle\Form\Type\UserAdminProfileType;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\Form\Extension\Core\Type\CollectionType;
use Symfony\Component\HttpFoundation\Request;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Method;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Security;
use Symfony\Component\HttpFoundation\Response;

/**
 * @Route("/admin")
 * @Security("has_role('ROLE_ADMIN')")
 */
class AdminController extends Controller
{
    /**
     * @Route("/users")
     * @Method({"GET","POST"})
     */
    public function usersAction(Request $request): Response
    {
        $userRepository = $this->getDoctrine()
            ->getRepository(User::class);

        $users = [
            'inActive' => $userRepository->findByIsEnabled(false),
            'active' => $userRepository->findByIsEnabled(true),
        ];

        $form = $this
            ->createFormBuilder($users)
            ->add(
                'inActive',
                CollectionType::class,
                ['entry_type' => UserAdminProfileType::class, 'entry_options' => ['validation_groups' => 'profile']]
            )
            ->add(
                'active',
                CollectionType::class,
                ['entry_type' => UserAdminProfileType::class, 'entry_options' => ['validation_groups' => 'profile']]
            )
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
            'admin/users.html.twig',
            [
                'form' => $form->createView(),
            ]
        );
    }
}
