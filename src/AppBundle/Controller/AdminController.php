<?php declare(strict_types = 1);

namespace AppBundle\Controller;

use AppBundle\Entity\Repository\UserRepository;
use AppBundle\Form\Type\UserAdminProfileType;
use Doctrine\Common\Persistence\ObjectManager;
use Symfony\Component\Form\Extension\Core\Type\CollectionType;
use Symfony\Component\Form\Extension\Core\Type\FormType;
use Symfony\Component\Form\FormFactoryInterface;
use Symfony\Component\HttpFoundation\RedirectResponse;
use Symfony\Component\HttpFoundation\Request;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Security;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Generator\UrlGeneratorInterface;

/**
 * @Security("has_role('ROLE_ADMIN')")
 */
class AdminController
{
    private $userRepository;
    private $formFactory;
    private $router;
    private $objectManager;
    private $twig;

    public function __construct(
        UserRepository $userRepository,
        FormFactoryInterface $formFactory,
        UrlGeneratorInterface $router,
        ObjectManager $objectManager,
        \Twig_Environment $twig
    ) {
        $this->userRepository = $userRepository;
        $this->formFactory = $formFactory;
        $this->router = $router;
        $this->objectManager = $objectManager;
        $this->twig = $twig;
    }


    public function users(Request $request)
    {
        $users = [
            'inActive' => $this->userRepository->findByIsEnabled(false),
            'active' => $this->userRepository->findByIsEnabled(true),
        ];

        $form = $this
            ->formFactory->createBuilder(FormType::class, $users)
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
                    $this->userRepository->save($user, false);
                }
            }

            $this
                ->objectManager
                ->flush();

            return new RedirectResponse($this->router->generate('app_admin_users'));
        }

        return new Response(
            $this->twig->render(
                'admin/users.html.twig',
                [
                    'form' => $form->createView(),
                ]
            )
        );
    }
}
