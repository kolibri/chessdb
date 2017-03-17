<?php declare(strict_types = 1);

namespace AppBundle\Controller;

use AppBundle\Entity\Game;
use AppBundle\Entity\Repository\GameRepository;
use Symfony\Component\HttpFoundation\Response;

class GameController
{
    private $gameRepository;
    private $twig;

    public function __construct(
        GameRepository $gameRepository,
        \Twig_Environment $twig
    ) {
        $this->gameRepository = $gameRepository;
        $this->twig = $twig;
    }

    public function list()
    {
        return new Response(
            $this
                ->twig
                ->render(
                    'game/list.html.twig',
                    [
                        'games' => $this
                            ->gameRepository
                            ->findAll(),
                    ]
                )
        );
    }

    public function show(Game $game)
    {
        return new Response(
            $this
                ->twig
                ->render(
                    'game/show.html.twig',
                    ['game' => $game]
                )
        );
    }
}
