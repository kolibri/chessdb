<?php

namespace AppBundle\Controller;

use AppBundle\Entity\Game;
use AppBundle\Entity\User;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;

/**
 * @Route("/leaderboard")
 */
class LeaderboardController extends Controller
{
    /**
     * @Route("/")
     */
    public function showAction()
    {
        $users = $this->getDoctrine()->getRepository(User::class)->findAll();
        $gameRepository = $this
            ->getDoctrine()
            ->getRepository(Game::class);

        $leaderboard = [];
        foreach ($users as $user) {
            $leaderboard[] = [
                'player' => $user->getUsername(),
                'games' => $gameRepository->findByPlayer($user->getUsername()),
                'won' => $gameRepository->findWonByPlayer($user->getUsername()),
                'lost' => $gameRepository->findLostByPlayer($user->getUsername()),
                'draw' => $gameRepository->findDrawByPlayer($user->getUsername())
            ];
        }

        return $this->render(
            'leaderboard/show.html.twig',
            [
                'leaderboard' => $leaderboard,
            ]
        );
    }
}
