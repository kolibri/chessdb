<?php


namespace AppBundle\Entity\Repository;


use AppBundle\Entity\Game;
use Doctrine\ORM\EntityRepository;

class GameRepository extends EntityRepository
{
    public function save(Game $game, $flush = true)
    {
        $entityManager = $this->getEntityManager();
        $entityManager->persist($game);

        if ($flush) {
            $entityManager->flush();
        }
    }

}