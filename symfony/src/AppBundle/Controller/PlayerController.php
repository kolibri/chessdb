<?php

namespace AppBundle\Controller;

use AppBundle\Entity\Game;
use AppBundle\Entity\Player;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Template;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\ParamConverter;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;
use Symfony\Component\Form\FormInterface;
use Symfony\Component\HttpFoundation\Request;

/**
 * @Route("/player")
 */
class PlayerController extends Controller
{
    /**
     * @Route("/list")
     * @Template("player/list.html.twig")
     */
    public function listAction()
    {
        return ['players' => $this->playerRepository()->findAll()];
    }

    /**
     * @Route("/show/{uuid}")
     * @Template("player/show.html.twig")
     */
    public function showAction(Player $player)
    {
        $gameRepository = $this->gameRepository();
        $playerRepository = $this->playerRepository();

        return [
            'player' => $player,
            'otherPlayers' => $playerRepository->findOtherPlayers($player),
            'wonGames' => $gameRepository->findWonByPlayer($player),
            'lostGames' => $gameRepository->findLostByPlayer($player),
            'drawGames' => $gameRepository->findDrawByPlayer($player),
            'unfinishedGames' => $gameRepository->findUnfinishedByPlayer($player),
        ];
    }

    /**
     * @Route("/vs/{player1Uuid}/{player2Uuid}")
     * @Template("player/versus.html.twig")
     * @ParamConverter("player1", class="AppBundle:Player", options={"id" = "player1Uuid"})
     * @ParamConverter("player2", class="AppBundle:Player", options={"id" = "player2Uuid"})
     */
    public function versusAction(Player $player1, Player $player2)
    {
        $gameRepository = $this->gameRepository();
        $playerRepository = $this->playerRepository();

        return [
            'player1' => $player1,
            'player2' => $player2,
            'wonGames' => $gameRepository->findWonPlayerVsPlayer($player1, $player2),
            'lostGames' => $gameRepository->findLostPlayerVsPlayer($player1, $player2),
            'drawGames' => $gameRepository->findDrawByPlayerVsPlayer($player1, $player2),
            'unfinishedGames' => $gameRepository->findUnfinishedByPlayerVsPlayer($player1, $player2),
            'otherPlayers1' => $playerRepository->findOtherPlayers($player1),
            'otherPlayers2' => $playerRepository->findOtherPlayers($player2),
        ];
    }

    /**
     * @Route("/versus/form")
     * @Template("player/versus_form.html.twig")
     */
    public function versusFormAction(Request $request)
    {
        $form = $this->versusForm();
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            /** @var Player[] $data */
            $data = $form->getData();
            return $this->redirectToRoute('app_player_versus', [
                'player1Id' => $data['player1']->getUuid(),
                'player2Id' => $data['player2']->getUuid(),
            ]);
        }

        return ['form' => $form->createView()];
    }

    /**
     * @Template("player/versus_form_fragment.html.twig")
     */
    public function versusFormFragmentAction()
    {
        return ['form' => $this->versusForm()->createView()];
    }

    /**
     * @return \AppBundle\Entity\PlayerRepository
     */
    private function playerRepository()
    {
        return $this->getDoctrine()->getRepository(Player::class);
    }

    /**
     * @return \AppBundle\Entity\GameRepository
     */
    private function gameRepository()
    {
        return $this->getDoctrine()->getRepository(Game::class);
    }

    /**
     * @return FormInterface
     */
    private function versusForm()
    {
        $options = [
            'class' => Player::class,
            'choice_label' => 'name',
        ];

        return $this->createFormBuilder()
            ->setAction($this->generateUrl('app_player_versusform'))
            ->add('player1', EntityType::class, $options)
            ->add('player2', EntityType::class, $options)
            ->add('submit', SubmitType::class, [
                'label' => 'vs'
            ])
            ->getForm();
    }
}
