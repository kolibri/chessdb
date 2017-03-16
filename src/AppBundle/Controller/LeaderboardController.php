<?php declare(strict_types = 1);

namespace AppBundle\Controller;

use AppBundle\Entity\Game;
use AppBundle\Entity\User;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Method;

/**
 * @Route("/leaderboard")
 */
class LeaderboardController extends Controller
{
    /**
     * @Route("/{sortBy}/{sortAsc}",
     *      defaults={
     *          "sortBy": "won",
     *          "sortAsc": false
     * }
     * )
     * @Method({"GET"})
     */
    public function showAction($sortBy = 'won', $sortAsc = false)
    {
        $users = $this
            ->getDoctrine()
            ->getRepository(User::class)
            ->findAll();

        $gameRepository = $this
            ->getDoctrine()
            ->getRepository(Game::class);

        $leaderboard = [];
        foreach ($users as $user) {
            $leaderboard[] = [
                'player' => $user->getUsername(),
                'games' => count($gameRepository->findByPlayer($user)),
                'won' => count($gameRepository->findWonByPlayer($user)),
                'lost' => count($gameRepository->findLostByPlayer($user)),
                'draw' => count($gameRepository->findDrawByPlayer($user)),
            ];
        }

        if (!array_key_exists($sortBy, $leaderboard)) {
            throw new \InvalidArgumentException(
                sprintf('Cannot sort by "%s" as no such key exists', $sortBy)
            );
        }

        uasort(
            $leaderboard,
            function ($a, $b) use ($sortBy, $sortAsc) {
                if ($a[$sortBy] == $b[$sortBy]) {
                    return 0;
                }

                return $a[$sortBy] < $b[$sortBy] ?
                    ($sortAsc ? -1 : 1) :
                    ($sortAsc ? 1 : -1);
            }
        );

        return $this->render(
            'leaderboard/show.html.twig',
            [
                'leaderboard' => $leaderboard,
                'sortBy' => $sortBy,
                'sortAsc' => $sortAsc,
            ]
        );
    }
}
