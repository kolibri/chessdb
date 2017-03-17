<?php declare(strict_types = 1);

namespace AppBundle\Controller;

use AppBundle\Entity\ImportPgn;
use AppBundle\Entity\Repository\GameRepository;
use AppBundle\Entity\Repository\ImportPgnRepository;
use AppBundle\Factory\GameFactory;
use AppBundle\Form\Type\GameType;
use AppBundle\Form\Type\ImportPgnType;
use Doctrine\Common\Persistence\ObjectManager;
use Symfony\Component\HttpFoundation\Response;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Security;
use Symfony\Component\Finder\Exception\AccessDeniedException;
use Symfony\Component\Form\FormFactoryInterface;
use Symfony\Component\HttpFoundation\RedirectResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Routing\Generator\UrlGeneratorInterface;
use Symfony\Component\Security\Core\Authentication\Token\Storage\TokenStorageInterface;
use Symfony\Component\Security\Core\Authorization\AuthorizationCheckerInterface;

/**
 * @Security("has_role('ROLE_PLAYER')")
 */
class ImportController
{

    private $gameRepository;
    private $importPgnRepository;
    private $gameFactory;
    private $tokenStorage;
    private $objectMananger;
    private $formFactory;
    private $authorionChecker;
    private $router;
    private $twig;

    public function __construct(
        GameRepository $gameRepository,
        ImportPgnRepository $importPgnRepository,
        GameFactory $gameFactory,
        TokenStorageInterface $tokenStorage,
        ObjectManager $objectMananger,
        FormFactoryInterface $formFactory,
        AuthorizationCheckerInterface $authorionChecker,
        UrlGeneratorInterface $router,
        \Twig_Environment $twig
    ) {
        $this->gameRepository = $gameRepository;
        $this->importPgnRepository = $importPgnRepository;
        $this->gameFactory = $gameFactory;
        $this->tokenStorage = $tokenStorage;
        $this->objectMananger = $objectMananger;
        $this->formFactory = $formFactory;
        $this->authorionChecker = $authorionChecker;
        $this->router = $router;
        $this->twig = $twig;
    }

    public function pgn(Request $request): Response
    {
        $form = $this->formFactory->create(ImportPgnType::class);
        $form->handleRequest($request);

        if ($form->isValid()) {
            /** @var ImportPgn $importPgn */
            $importPgn = $form->getData();
            $this->importPgnRepository->save($importPgn);

            return new RedirectResponse(
                $this->router->generate(
                    'app_import_game',
                    ['uuid' => $importPgn->getUuid()]
                )
            );
        }

        return new Response(
            $this->twig->render(
                'import/pgn.html.twig',
                ['form' => $form->createView()]
            )
        );
    }


    public function game(Request $request, ImportPgn $importPgn): Response
    {
        if (!$this->authorionChecker->isGranted('import', $importPgn)) {
            throw new AccessDeniedException();
        }

        if ($importPgn->isImported()) {
            return new Response(
                $this->twig->render(
                    'import/game_already_imported.html.twig',
                    [
                        'game' => $this
                            ->gameRepository
                            ->findOneByImportPgn($importPgn),
                    ]
                )
            );
        }

        $game = $this
            ->gameFactory
            ->createFromImportPgn($importPgn);
        $form = $this->formFactory->create(GameType::class, $game);
        $form->handleRequest($request);

        if ($form->isValid()) {
            $importPgn->setImported(true);
            $this
                ->importPgnRepository
                ->save($importPgn, false);
            $this
                ->gameRepository
                ->save($game, false);

            $this
                ->objectMananger
                ->flush();

            return new RedirectResponse(
                $this->router->generate(
                    'app_game_show',
                    ['uuid' => $game->getUuid()]
                )
            );
        }

        return new Response(
            $this->twig->render(
                'import/game.html.twig',
                [
                    'form' => $form->createView(),
                    'importedPgn' => $importPgn,
                ]
            )
        );
    }

    public function deletePgn(ImportPgn $importPgn): Response
    {
        if (!$this->authorionChecker->isGranted('delete', $importPgn)) {
            throw new AccessDeniedException();
        }
        $this->importPgnRepository->remove($importPgn);

        return new RedirectResponse(
            $this->router->generate('app_import_list')
        );
    }

    public function list(): Response
    {
        return new Response(
            $this->twig->render(
                'import/list.html.twig',
                [
                    'games' => $this
                        ->importPgnRepository
                        ->findUnimportedByUser(
                            $this
                                ->tokenStorage
                                ->getToken()
                                ->getUser()
                        ),
                ]
            )
        );
    }
}
