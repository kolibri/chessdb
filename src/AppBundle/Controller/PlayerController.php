<?php declare(strict_types = 1);

namespace AppBundle\Controller;

use AppBundle\Entity\Repository\GameRepository;
use AppBundle\Entity\Repository\UserRepository;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\ParamConverter;
use Symfony\Component\HttpFoundation\Response;

class PlayerController
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

    public function list()
    {
        return new Response(
            $this->twig->render(
                'player/list.html.twig',
                [
                    'users' => $this
                        ->userRepository
                        ->findAll(),
                ]
            )
        );
    }

    /**
     * @ParamConverter(
     *     "player",
     *     class="AppBundle:User",
     *     options={"mapping": {"player": "username"}}
     * )
     */
    public function show($player)
    {
        return new Response(
            $this->twig->render(
                'player/show.html.twig',
                [
                    'player' => $player,
                    'players' => $this
                        ->userRepository
                        ->findAll(),
                    'gamesByResult' => $this
                        ->gameRepository
                        ->findByPlayerGroupByResult($player),
                ]
            )
        );
    }

    /**
     * @ParamConverter(
     *     "player1",
     *     class="AppBundle:User",
     *     options={"mapping": {"player1": "username"}}
     * )
     * @ParamConverter(
     *     "player2",
     *     class="AppBundle:User",
     *     options={"mapping": {"player2": "username"}}
     * )
     */
    public function versus($player1, $player2)
    {
        return new Response(
            $this->twig->render(
                'player/versus.html.twig',
                [
                    'player1' => $player1,
                    'player2' => $player2,
                    'gamesByResult' => $this
                        ->gameRepository
                        ->findByPlayerVsPlayerGroupByResult($player1, $player2),
                ]
            )
        );
    }
}
