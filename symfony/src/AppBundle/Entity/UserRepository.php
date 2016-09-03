<?php

namespace AppBundle\Entity;

use Doctrine\ORM\EntityRepository;

class UserRepository extends EntityRepository
{
    public function save(User $user, $flush = true)
    {
        $entityManager = $this->getEntityManager();
        $entityManager->persist($user);

        foreach ($user->getPlayers() as $player) {
            $entityManager->persist($player);
        }

        if ($flush) {
            $entityManager->flush();
        }
    }
}
