<?php

namespace AppBundle\Controller;

use AppBundle\Entity\Game;
use AppBundle\Entity\ImportPgn;
use AppBundle\Entity\Repository\GameRepository;
use AppBundle\Entity\Repository\ImportedPgnRepository;
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
     * @Route("/pgn")
     */
    public function pgnAction(Request $request)
    {
        $form = $this->createForm(ImportPgnType::class);
        $form->handleRequest($request);

        if ($form->isValid()) {
            /** @var ImportPgn $importedPgn */
            $importedPgn = $form->getData();
            $this->importedPgnRepository()->save($importedPgn);

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
        if ($importedPgn->isImported()) {
            return $this->render(
                'import/game_already_imported.html.twig',
                ['game' => $this->gameRepository()->findOneByImportPgn($importedPgn)]
            );
        }

        $game = $this
            ->get('app.import.pgn_string_importer')
            ->importPgnString($importedPgn->getPgnString());
        $game->setOriginalPgn($importedPgn);
        $form = $this->createForm(GameType::class, $game);
        $form->handleRequest($request);

        if ($form->isValid()) {
            $importedPgn->setImported(true);
            $this->importedPgnRepository()->save($importedPgn, false);
            $this->gameRepository()->save($game, false);

            $this->getDoctrine()->getEntityManager()->flush();

            return $this->redirectToRoute('app_game_show', ['uuid' => $game->getUuid()]);
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
     * @Route("/list")
     */
    public function listPgnsAction()
    {
        return $this->render(
            'import/list_pgns.html.twig',
            ['pgns' => $this->importedPgnRepository()->findUnimported()]
        );
    }

    /**
     * @return ImportedPgnRepository
     */
    private function importedPgnRepository()
    {
        return $this->getDoctrine()->getRepository(ImportPgn::class);
    }

    /**
     * @return GameRepository
     */
    private function gameRepository()
    {
        return $this->getDoctrine()->getRepository(Game::class);
    }
}
