<?php


namespace AppBundle\Controller;

use AppBundle\Entity\Game;
use AppBundle\Entity\User;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Method;

/**
 * @Route("/player")
 */
class PlayerController extends Controller
{
    /**
     * @Route("/")
     * @Method({"GET"})
     */
    public function listAction()
    {
        return $this->render(
            'player/list.html.twig',
            [
                'users' => $this
                    ->getDoctrine()
                    ->getRepository(User::class)
                    ->findAll(),
            ]
        );
    }

    /**
     * @Route("/{player}")
     * @Method({"GET"})
     */
    public function showAction($player)
    {
        return $this->render(
            'player/show.html.twig',
            [
                'player' => $player,
                'gamesByResult' => $this
                    ->getDoctrine()
                    ->getRepository(Game::class)
                    ->findByPlayerGroupByResult($player),
            ]
        );
    }

    /**
     * @Route("/{player1}/vs/{player2}")
     * @Method({"GET"})
     */
    public function versusAction($player1, $player2)
    {
        return $this->render(
            'player/versus.html.twig',
            [
                'player1' => $player1,
                'player2' => $player2,
                'gamesByResult' => $this
                    ->getDoctrine()
                    ->getRepository(Game::class)
                    ->findByPlayerVsPlayerGroupByResult($player1, $player2),
            ]
        );
    }
}
