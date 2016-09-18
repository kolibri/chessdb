<?php

namespace AppBundle\Controller;

use AppBundle\Entity\Game;
use AppBundle\Entity\ImportPgn;
use AppBundle\Entity\Repository\GameRepository;
use AppBundle\Entity\Repository\ImportPgnRepository;
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
            /** @var ImportPgn $importPgn */
            $importPgn = $form->getData();
            $this->importedPgnRepository()->save($importPgn);

            return $this->redirectToRoute('app_import_game', ['uuid' => $importPgn->getUuid()]);
        }

        return $this->render(
            'import/pgn.html.twig',
            ['form' => $form->createView()]
        );
    }

    /**
     * @Route("/game/{uuid}")
     */
    public function gameAction(Request $request, ImportPgn $importPgn)
    {
        if ($importPgn->isImported()) {
            return $this->render(
                'import/game_already_imported.html.twig',
                ['game' => $this->gameRepository()->findOneByImportPgn($importPgn)]
            );
        }

        $game = $this
            ->get('app.import.pgn_string_importer')
            ->importPgn($importPgn);
        $game->setOriginalPgn($importPgn);
        $form = $this->createForm(GameType::class, $game);
        $form->handleRequest($request);

        if ($form->isValid()) {
            $importPgn->setImported(true);
            $this->importedPgnRepository()->save($importPgn, false);
            $this->gameRepository()->save($game, false);

            $this->getDoctrine()->getEntityManager()->flush();

            return $this->redirectToRoute('app_game_show', ['uuid' => $game->getUuid()]);
        }
        
        return $this->render(
            'import/game.html.twig',
            [
                'form' => $form->createView(),
                'importedPgn' => $importPgn,
            ]
        );
    }

    /**
     * @Route("/delete/pgn/{uuid}")
     */
    public function deletePgn(ImportPgn $importPgn)
    {
        $importPgnRepository = $this->getDoctrine()->getRepository(ImportPgn::class);
        $importPgnRepository->remove($importPgn);

        return $this->redirectToRoute('app_import_listpgns');
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
     * @return ImportPgnRepository
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
