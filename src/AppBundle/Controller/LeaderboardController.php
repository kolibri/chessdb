<?php declare(strict_types = 1);

namespace AppBundle\Controller;

use AppBundle\Entity\Repository\GameRepository;
use AppBundle\Entity\Repository\UserRepository;
use AppBundle\Entity\User;
use Symfony\Component\HttpFoundation\Response;

class LeaderboardController
{
    private $userRepository;
    private $gameRepository;
    private $twig;

    public function __construct(
        UserRepository $userRepository,
        GameRepository $gameRepository,
        \Twig_Environment $twig
    ) {
        $this->userRepository = $userRepository;
        $this->gameRepository = $gameRepository;
        $this->twig = $twig;
    }

    public function show($sortBy = 'won', $sortAsc = false)
    {
        /** @var User[] $users */
        $users = $this
            ->userRepository
            ->findAll();

        $leaderboard = [];
        foreach ($users as $user) {
            $leaderboard[] = [
                'player' => $user->getUsername(),
                'games' => count($this->gameRepository->findByPlayer($user)),
                'won' => count($this->gameRepository->findWonByPlayer($user)),
                'lost' => count($this->gameRepository->findLostByPlayer($user)),
                'draw' => count($this->gameRepository->findDrawByPlayer($user)),
            ];
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

        return new Response(
            $this->twig->render(
                'leaderboard/show.html.twig',
                [
                    'leaderboard' => $leaderboard,
                    'sortBy' => $sortBy,
                    'sortAsc' => $sortAsc,
                ]
            )
        );
    }
}
