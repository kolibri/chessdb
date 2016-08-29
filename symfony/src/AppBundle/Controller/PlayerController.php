<?php


namespace AppBundle\Controller;


use AppBundle\Entity\Game;
use AppBundle\Entity\Player;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Template;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;
use Symfony\Component\Form\FormInterface;
use Symfony\Component\HttpFoundation\Request;

class PlayerController extends Controller
{
    /**
     * @Route("/players")
     * @Template("player/list.html.twig")
     */
    public function listAction()
    {
        return ['players' => $this->playerRepository()->findAll()];
    }

    /**
     * @Route("/players/show/{id}")
     * @Template("player/show.html.twig")
     */
    public function showAction($id)
    {
        return [
            'player' => $this->playerRepository()->find($id),
            'otherPlayers' => $this->playerRepository()->findOtherPlayers($id),
            'wonGames' => $this->gameRepository()->findWonGamesByPlayerId($id),
            'lostGames' => $this->gameRepository()->findLostGamesByPlayerId($id),
            'drawGames' => $this->gameRepository()->findDrawGamesByPlayerId($id),
            'unfinishedGames' => $this->gameRepository()->findUnfinishedGamesByPlayerId($id),
        ];
    }

    /**
     * @Route("/players/vs/{player1Id}/{player2Id}")
     * @Template("player/versus.html.twig")
     */
    public function versusAction($player1Id, $player2Id)
    {
        return [
            'player1' => $this->playerRepository()->find($player1Id),
            'player2' => $this->playerRepository()->find($player2Id),
            'wonGames' => $this->gameRepository()->findWonGamesPlayerVsPlayer($player1Id, $player2Id),
            'lostGames' => $this->gameRepository()->findLostGamesPlayerVsPlayer($player1Id, $player2Id),
            'drawGames' => $this->gameRepository()->findDrawGamesPlayerVsPlayer($player1Id, $player2Id),
            'unfinishedGames' => $this->gameRepository()->findUnfinishedGamesPlayerVsPlayer($player1Id, $player2Id),
            'otherPlayers1' => $this->playerRepository()->findOtherPlayers($player1Id),
            'otherPlayers2' => $this->playerRepository()->findOtherPlayers($player2Id),
        ];
    }

    /**
     * @Route("/player/versus/form")
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
    public function versusFormFragmentAction(Request $request)
    {
        return ['form' => $this->versusForm()->createView()];
    }

    /**
     * @return \AppBundle\Entity\PlayerRepository
     */
    private function playerRepository()
    {
        return $this->get('doctrine')->getRepository(Player::class);
    }

    /**
     * @return \AppBundle\Entity\GameRepository
     */
    private function gameRepository()
    {
        return $this->get('doctrine')->getRepository(Game::class);
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
            ->add('submit', SubmitType::class)
            ->getForm();
    }
}
