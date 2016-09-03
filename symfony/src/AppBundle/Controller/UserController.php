<?php

namespace AppBundle\Controller;

use AppBundle\Entity\Game;
use AppBundle\Entity\User;
use AppBundle\Form\UserToPlayerType;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\Request;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Template;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Security;

/**
 * @Route("/user")
 */
class UserController extends Controller
{
    /**
     * @Route("/list")
     * @Template("user/list.html.twig")
     * @Security("has_role('ROLE_ADMIN')")
     */
    public function listAction()
    {
        return ['users' => $this->userRepository()->findAll()];
    }

    /**
     * @Route("/show/{uuid}")
     * @Template("user/show.html.twig")
     */
    public function showAction(User $user)
    {
        return ['user' => $user];
    }

    /**
     * @Route("/edit_player/{uuid}")
     * @Template("user/edit_players.html.twig")
     * @Security("has_role('ROLE_ADMIN')")
     */
    public function editPlayerAction(Request $request, User $user)
    {
        #$user = $this->userRepository()->find($id);
        $form = $this->createForm(UserToPlayerType::class, $user);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $this->userRepository()->save($user);
            
            return $this->redirectToRoute('app_user_editplayer', ['id' => $user->getUuid()]);
        }

        return [
            'user' => $user,
            'form' => $form->createView(),
        ];
    }

    /**
     * @return \AppBundle\Entity\UserRepository
     */
    private function userRepository()
    {
        return $this->getDoctrine()->getRepository(User::class);
    }

    /**
     * @return \AppBundle\Entity\GameRepository
     */
    private function gameRepository()
    {
        return $this->getDoctrine()->getRepository(Game::class);
    }
}
