<?php

namespace AppBundle\Controller;

use AppBundle\Entity\Game;
use AppBundle\Entity\ImportPgn;
use AppBundle\Form\GameType;
use AppBundle\Form\ImportPgnType;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Template;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\Request;

/**
 * @Route("/import")
 */
class ImportController extends Controller
{
    /**
     * @Route("/pgn", name="import")
     */
    public function pgnAction(Request $request)
    {
        $form = $this->createForm(ImportPgnType::class);
        $form->handleRequest($request);

        if ($form->isValid()) {
            /** @var ImportPgn $importedPgn */
            $importedPgn = $form->getData();
            $this->importedGameRepository()->save($importedPgn);

            return $this->redirectToRoute('app_import_game', ['uuid' => $importedPgn->getUuid()]);
        }

        return $this->render(
            'import/pgn.html.twig',
            ['form' => $form->createView()]
        );
    }

    /**
     * @Route("/game/{uuid}")
     */
    public function gameAction(Request $request, ImportPgn $importedPgn)
    {
        $game = $this
            ->get('app.import.pgn_string_importer')
            ->importPgnString($importedPgn->getPgnString());

        $form = $this->createForm(GameType::class, $game);
        $form->handleRequest($request);

        if ($form->isValid()) {
            $importedPgn->setImported(true);
            $this->importedGameRepository()->save($importedPgn, false);
            $this->gameRepository()->save($game, false);

            $this->getDoctrine()->getEntityManager()->flush();

            return $this->redirectToRoute('app_game_showgame', ['uuid' => $game->getUuid()]);
        }
        
        return $this->render(
            'import/game.html.twig',
            [
                'form' => $form->createView(),
                'importedPgn' => $importedPgn,
            ]
        );
    }

    /**
     * @return \AppBundle\Entity\Repository\ImportedPgnRepository|\Doctrine\Common\Persistence\ObjectRepository
     */
    private function importedGameRepository()
    {
        return $this->getDoctrine()->getRepository(ImportPgn::class);
    }

    private function gameRepository()
    {
        return $this->getDoctrine()->getRepository(Game::class);
    }
}
