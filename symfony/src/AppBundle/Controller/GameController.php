<?php


namespace AppBundle\Controller;

use AppBundle\Entity\Game;
use AppBundle\Entity\GameRepository;
use AppBundle\Form\GameType;
use AppBundle\Form\GameTypeHandler;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Template;
use Symfony\Component\HttpFoundation\Request;

/**
 * @Route("/game")
 */
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

    /**
     * @Route("/edit/{id}")
     * @Template("game/edit.html.twig")
     */
    public function editAction(Request $request, $id)
    {
        /** @var Game $game */
        $game = $this->gameRepository()->find($id);
        $form = $this->createForm(GameType::class, $game);

        $form->handleRequest($request);
        if ($form->isSubmitted() && $form->isValid()) {
            $this->formHandler()->handle($form);

            return $this->redirectToRoute('app_game_show', ['id' => $game->getUuid()]);
        }

        return [
            'game' => $game,
            'form' => $form->createView(),
        ];
    }

    /**
     * @return GameRepository
     */
    private function gameRepository()
    {
        return $this->getDoctrine()->getRepository(Game::class);
    }

    /**
     * @return GameTypeHandler
     */
    private function formHandler()
    {
        return $this->get('app.form.game_type_handler');
    }
}