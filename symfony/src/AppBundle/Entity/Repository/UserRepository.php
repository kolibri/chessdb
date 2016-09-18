<?php


namespace AppBundle\Entity\Repository;


use AppBundle\Entity\User;
use Doctrine\ORM\EntityRepository;

class UserRepository extends EntityRepository
{
    public function save(User $user, $flush = true)
    {
        $entityManager = $this->getEntityManager();
        $entityManager->persist($user);

        if ($flush) {
            $entityManager->flush();
        }
    }
}