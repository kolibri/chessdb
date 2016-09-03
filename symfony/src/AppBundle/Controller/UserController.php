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
use Sensio\Bundle\FrameworkExtraBundle\Configuration\ParamConverter;

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
     * @Security("has_role('ROLE_PLAYER')")
     */
    public function showAction(User $user)
    {
        $gameRepository = $this->gameRepository();

        return [
            'user' => $user,
            'wonGames' => $gameRepository->findWonByUser($user),
            'lostGames' => $gameRepository->findLostByUser($user),
            'drawGames' => $gameRepository->findDrawByUser($user),
            'unfinishedGames' => $gameRepository->findUnfinishedByUser($user),
            'otherUsers' => $this->userRepository()->findOtherUsers($user)
        ];
    }

    /**
     * @Route("/vs/{user1Uuid}/{user2Uuid}")
     * @Template("/user/versus.html.twig")
     * @Security("has_role('ROLE_PLAYER')")
     * @ParamConverter("user1", class="AppBundle:User", options={"id" = "user1Uuid"})
     * @ParamConverter("user2", class="AppBundle:User", options={"id" = "user2Uuid"})
     */
    public function versusAction(User $user1, User $user2)
    {
        $gameRepository = $this->gameRepository();
        $userRepository = $this->userRepository();

        return [
            'user1' => $user1,
            'user2' => $user2,
            'wonGames' => $gameRepository->findWonByUserVsUser($user1, $user2),
            'lostGames' => $gameRepository->findLostByUserVsUser($user1, $user2),
            'drawGames' => $gameRepository->findDrawByUserVsUser($user1, $user2),
            'unfinishedGames' => $gameRepository->findUnfinishedByUserVsUser($user1, $user2),
            'otherUsers1' => $userRepository->findOtherUsers($user1),
            'otherUsers2' => $userRepository->findOtherUsers($user2),
        ];
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
            
            return $this->redirectToRoute('app_user_editplayer', ['uuid' => $user->getUuid()]);
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
