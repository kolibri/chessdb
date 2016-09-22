<?php


namespace AppBundle\Controller;


use AppBundle\Entity\Leaderboard;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;

/**
 * @Route("/leaderboard")
 */
class LeaderboardController extends Controller
{
    /**
     * @Route("/list")
     */
    public function listAction()
    {
        return $this->render(
            'leaderboard/list.html.twig',
            [
                'leaderboards' => $this
                    ->getDoctrine()
                    ->getRepository(Leaderboard::class)
                    ->findAll(),
            ]
        );
    }

    /**
     * @Route("/show/{uuid}")
     */
    public function showAction(Leaderboard $leaderboard)
    {
        return $this->render(
            'leaderboard/show.html.twig',
            [
                'leaderboard' => $leaderboard,
            ]
        );
    }

}