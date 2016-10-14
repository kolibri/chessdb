<?php


namespace AppBundle\Controller;

use AppBundle\Dropbox\AuthHelper;
use AppBundle\Entity\DropboxPgn;
use AppBundle\Entity\ImportPgn;
use AppBundle\Entity\Repository\DropboxPgnRepository;
use AppBundle\Form\Type\DropboxPgnType;
use AppBundle\Form\Type\ImportPgnType;
use Dropbox\Client;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Method;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Security;
use Symfony\Component\HttpFoundation\Request;

/**
 * @Route("/dropbox")
 * @Security("has_role('ROLE_PLAYER')")
 */
class DropboxController extends Controller
{
    /**
     * @Route("/")
     * @Method({"GET"})
     */
    public function listAction()
    {
        $tokenStorage = $this->get('app.dropbox.access_token_store');

        if (null === $tokenStorage->get()) {
            return $this->redirectToRoute('app_dropbox_authstart');
        }

        $client = $this->getClient();

        $files = $client->getFilePaths('/pgn', '/.*\.pgn/');
        $games = [];

        /** @var DropboxPgn[] $importedGames */
        $importedGames = [];
        foreach ($this->dropboxPgnRepository()->findByUser($this->getUser()) as $importedGame) {
            $importedGames[$importedGame->getPath()] = $importedGame;
        }

        $pathsToImport = [];
        $imported = [];
        foreach ($files as $path) {
            if (key_exists($path, $importedGames)) {
                $imported[] = [
                    'path' => $path,
                    'dropboxPgn' => $importedGames[$path],
                ];
            } else {
                $pathsToImport[] = [
                    'path' => $path,
                    'content' => $client->getFileContent($path),
                ];
            }
        }

        return $this->render(
            'dropbox/list.html.twig',
            [
                'pathsToImport' => $pathsToImport,
                'imported' => $imported,
            ]
        );
    }

    /**
     * @Route("/import/{path}", requirements={"path"=".+"})
     * @Method({"GET", "POST"})
     */
    public function importAction(Request $request, $path)
    {
        $client = $this->getClient();

        $form = $this->createForm(
            DropboxPgnType::class,
            new DropboxPgn(
                $path,
                new ImportPgn(
                    $client->getFileContent($path),
                    $this->getUser()
                )
            )
        );

        $form->handleRequest($request);

        if ($form->isValid()) {
            /** @var DropboxPgn $dropboxPgn */
            $dropboxPgn = $form->getData();
            $this
                ->dropboxPgnRepository()
                ->save($dropboxPgn);

            return $this->redirectToRoute(
                'app_import_game',
                [
                    'uuid' => $dropboxPgn
                        ->getImportPgn()
                        ->getUuid(),
                ]
            );
        }

        return $this->render(
            'dropbox/import.html.twig',
            ['form' => $form->createView()]
        );
    }

    /**
     * @Route("/auth-start")
     * @Method({"GET"})
     */
    public function authStartAction(Request $request)
    {
        $authUrl = $this->getAuthHelper()->getStartUrl();

        return $this->redirect($authUrl);
    }

    /**
     * @Route("/auth-finish")
     * @Method({"GET"})
     */
    public function authFinishAction(Request $request)
    {
        $this->getAuthHelper()->finish($request);

        return $this->redirectToRoute('app_dropbox_list');
    }

    /**
     * @return AuthHelper
     */
    private function getAuthHelper()
    {
        return $this->get('app.dropbox.auth_helper');
    }

    private function getClient()
    {
        $dropboxClient = new Client(
            $this->get('app.dropbox.access_token_store')->get(),
            'chessdb'
        );

        return new \AppBundle\Dropbox\Client($dropboxClient);
    }

    /**
     * @return DropboxPgnRepository
     */
    private function dropboxPgnRepository()
    {
        return $this->getDoctrine()->getRepository(DropboxPgn::class);
    }
}
