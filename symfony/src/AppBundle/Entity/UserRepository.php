<?php

namespace AppBundle\Entity;

use Doctrine\ORM\EntityRepository;

class UserRepository extends EntityRepository
{
    /**
     * @param User $user
     * @param bool $flush
     */
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

    /**
     * @param User $user
     * @return User[]|null
     */
    public function findOtherUsers(User $user)
    {
        return $this
            ->createQueryBuilder('u')
            ->where('u.uuid != :user')
            ->setParameter('user', $user)
            ->getQuery()
            ->execute();

    }
}
