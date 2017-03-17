<?php declare(strict_types = 1);

namespace AppBundle\Controller;

use AppBundle\Dropbox\AccessTokenStore;
use AppBundle\Dropbox\AuthHelper;
use AppBundle\Entity\DropboxPgn;
use AppBundle\Entity\ImportPgn;
use AppBundle\Entity\Repository\DropboxPgnRepository;
use AppBundle\Form\Type\DropboxPgnType;
use Dropbox\Client;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Security;
use Symfony\Component\Form\FormFactoryInterface;
use Symfony\Component\HttpFoundation\RedirectResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Generator\UrlGeneratorInterface;
use Symfony\Component\Security\Core\Authentication\Token\Storage\TokenStorageInterface;

/**
 * @Security("has_role('ROLE_PLAYER')")
 */
class DropboxController
{
    private $dropboxPgnRepository;
    private $dropBoxAccessTokenStorage;
    private $dropBoxAuthHelper;
    private $formFactory;
    private $router;
    private $tokenStorage;
    private $twig;

    public function __construct(
        DropboxPgnRepository $dropboxPgnRepository,
        AccessTokenStore $dropBoxAccessTokenStorage,
        AuthHelper $dropBoxAuthHelper,
        FormFactoryInterface $formFactory,
        UrlGeneratorInterface $router,
        TokenStorageInterface $tokenStorage,
        \Twig_Environment $twig
    ) {
        $this->dropboxPgnRepository = $dropboxPgnRepository;
        $this->dropBoxAccessTokenStorage = $dropBoxAccessTokenStorage;
        $this->dropBoxAuthHelper = $dropBoxAuthHelper;
        $this->formFactory = $formFactory;
        $this->router = $router;
        $this->tokenStorage = $tokenStorage;
        $this->twig = $twig;
    }

    public function list()
    {
        if (null === $this->dropBoxAccessTokenStorage->get()) {
            return new RedirectResponse($this->router->generate('app_dropbox_authstart'));
        }

        $client = $this->getClient();

        $files = $client->getFilePaths('/pgn', '/.*\.pgn/');
        $games = [];

        /** @var DropboxPgn[] $importedGames */
        $importedGames = [];
        foreach ($this->dropboxPgnRepository->findByUser($this->tokenStorage->getToken()->getUser()) as $importedGame) {
            $importedGames[$importedGame->getPath()] = $importedGame;
        }

        $pathsToImport = [];
        $imported = [];
        foreach ($files as $path) {
            if (array_key_exists($path, $importedGames)) {
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

        return new Response(
            $this->twig->render(
                'dropbox/list.html.twig',
                [
                    'pathsToImport' => $pathsToImport,
                    'imported' => $imported,
                ]
            )
        );
    }

    public function import(Request $request, $path)
    {
        $client = $this->getClient();

        $form = $this->formFactory->create(
            DropboxPgnType::class,
            new DropboxPgn(
                $path,
                new ImportPgn(
                    $client->getFileContent($path),
                    $this->tokenStorage->getToken()->getUser()
                )
            )
        );

        $form->handleRequest($request);

        if ($form->isValid()) {
            /** @var DropboxPgn $dropboxPgn */
            $dropboxPgn = $form->getData();
            $this
                ->dropboxPgnRepository
                ->save($dropboxPgn);

            return new RedirectResponse(
                $this->router->generate(
                    'app_import_game',
                    [
                        'uuid' => $dropboxPgn
                            ->getImportPgn()
                            ->getUuid(),
                    ]
                )
            );
        }

        return new Response(
            $this->twig->render(
                'dropbox/import.html.twig',
                ['form' => $form->createView()]
            )
        );
    }

    public function authStart()
    {
        return new RedirectResponse(
            $this
                ->dropBoxAuthHelper
                ->getStartUrl()
        );
    }

    public function authFinish(Request $request)
    {
        $this
            ->dropBoxAuthHelper
            ->finish($request);

        return new RedirectResponse(
            $this
                ->router
                ->generate('app_dropbox_list')
        );
    }

    private function getClient()
    {
        $dropboxClient = new Client(
            $this->dropBoxAccessTokenStorage->get(),
            'chessdb'
        );

        return new \AppBundle\Dropbox\Client($dropboxClient);
    }
}
