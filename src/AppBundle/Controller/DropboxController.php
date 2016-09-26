<?php


namespace AppBundle\Controller;

use AppBundle\Dropbox\AuthHelper;
use AppBundle\Entity\DropboxPgn;
use AppBundle\Entity\ImportPgn;
use AppBundle\Entity\Repository\DropboxPgnRepository;
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

        foreach ($files as $filePath) {
            $games[] = [
                'path' => $filePath,
                'pgn' => $client->getFileContent($filePath),
                'dropboxPgn' =>  $this->dropboxPgnRepository()->getByPathOrNull($filePath)
            ];
        }
        
        return $this->render(
            'dropbox/list.html.twig',
            [
                'games' => $games,
            ]
        );
    }

    /**
     * @Route("/game/{path}", requirements={"path"=".+"})
     * @Method({"GET"})
     */
    public function gameAction($path)
    {
        $tokenStorage = $this->get('app.dropbox.access_token_store');

        if (!$tokenStorage->get()) {
            return $this->redirectToRoute('app_dropbox_authstart');
        }

        $client = $this->getClient();
        $game = $client->getFileContent($path);

        $importPgn = new ImportPgn($game, $this->getUser());

        if (!$dropboxPgn = $this->dropboxPgnRepository()->getByPathOrNull($path)) {
            $dropboxPgn = new DropboxPgn($path, $importPgn);
        }

        $dropboxPgn->setImportPgn($importPgn);

        $enitityManager = $this->getDoctrine()->getManager();
        $enitityManager->persist($dropboxPgn);
        $enitityManager->persist($importPgn);
        $enitityManager->flush();

        return $this->redirectToRoute(
            'app_import_game',
            [
                'uuid' => $importPgn->getUuid(),
            ]
        );
    }

    /**
     * @Route("/auth-clear")
     * @Method({"GET"})
     */
    public function clearAction(Request $request)
    {
        $request->getSession()->clear();

        return $this->redirectToRoute('app_dropbox_list');
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
