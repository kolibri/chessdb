<?php

namespace AppBundle\Controller;

use AppBundle\Entity\User;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\Request;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Template;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Security;

/**
 * Class UserController
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
     * @Route("/edit_player/{id}")
     * @Template("user/edit_players.html.twig")
     * @Security("has_role('ROLE_ADMIN')")
     */
    public function editPlayersAction(Request $request, $id)
    {
        $user = $this->userRepository()->find($id);

        return ['user' => $user];
    }

    private function userRepository()
    {
        return $this->getDoctrine()->getRepository(User::class);
    }
}
