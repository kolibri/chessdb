<?php

namespace AppBundle\Controller;

use AppBundle\Entity\Game;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Method;

/**
 * @Route("/game")
 */
class GameController extends Controller
{
    /**
     * @Route("/list")
     * @Method({"GET"})
     */
    public function listAction()
    {
        return $this->render(
            'game/list.html.twig',
            ['games' => $this->getDoctrine()->getRepository(Game::class)->findAll()]
        );
    }

    /**
     * @Route("/show/{uuid}")
     * @Method({"GET"})
     */
    public function showAction(Game $game)
    {
        return $this->render(
            'game/show.html.twig',
            ['game' => $game]
        );
    }
}
