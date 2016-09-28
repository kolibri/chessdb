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

    /**
     * @param bool $isEnabled
     * @return User[]|null
     */
    public function findByIsEnabled($isEnabled = true)
    {
        return $this
            ->createQueryBuilder('u')
            ->where('u.isEnabled = :isEnabled')
            ->setParameter('isEnabled', $isEnabled)
            ->getQuery()
            ->execute();
    }
}
