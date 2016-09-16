<?php


namespace AppBundle\Controller;


use AppBundle\Entity\Game;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;

/**
 * @Route("/game")
 */
class GameController extends Controller
{
    /**
     * @Route("/show/{uuid}")
     */
    public function showGameAction(Game $game)
    {
        return $this->render(
            'game/show.html.twig',
            ['game' => $game]
        );
    }
}