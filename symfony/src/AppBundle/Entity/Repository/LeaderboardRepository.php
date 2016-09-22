<?php


namespace AppBundle\Entity\Repository;


use AppBundle\Entity\Game;
use AppBundle\Entity\Leaderboard;
use AppBundle\Entity\LeaderboardItem;
use AppBundle\Entity\User;
use Doctrine\ORM\EntityRepository;

class LeaderboardRepository extends EntityRepository
{
    public function save(Leaderboard $leaderboard, $flush = true)
    {
        $entityManager = $this->getEntityManager();

        foreach ($leaderboard->getItems() as $item) {
            $entityManager->persist($item);
        }

        $entityManager->persist($leaderboard);

        if ($flush) {
            $entityManager->flush();
        }
    }
    
    public function createLeaderboard()
    {
        $users = $this->getEntityManager()->getRepository(User::class)->findAll();
        $gameRepository = $this
            ->getEntityManager()
            ->getRepository(Game::class);
        
        $leaderboard = new Leaderboard();
        foreach ($users as $user) {
            $leaderboard->addItem(new LeaderboardItem(
                $user->getUsername(),
                count($gameRepository->findByPlayer($user->getUsername())),
                count($gameRepository->findWonByPlayer($user->getUsername())),
                count($gameRepository->findLostByPlayer($user->getUsername())),
                count($gameRepository->findDrawByPlayer($user->getUsername()))
            ));
        }

        return $leaderboard;
    }
}