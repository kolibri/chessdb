<?php


namespace AppBundle\Controller;

use AppBundle\Entity\Game;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Template;

class GameController extends Controller
{
    /**
     * @Route("/games")
     * @Template("game/list.html.twig")
     */
    public function listAction()
    {
        return [
            'games' => $this->gameRepository()->findAll()
        ];
    }

    /**
     * @Route("/games/show/{id}")
     * @Template("game/show.html.twig")
     */
    public function showAction($id)
    {
        return ['game' => $this->gameRepository()->find($id)];
    }
    
    private function gameRepository()
    {
        return $this->getDoctrine()->getRepository(Game::class);
    }
}