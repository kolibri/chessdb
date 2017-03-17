<?php declare(strict_types = 1);


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
     * @return User
     */
    public function findByName(string $username)
    {
        return $this
            ->createQueryBuilder('u')
            ->where('u.username = :username')
            ->setParameter('username', $username)
            ->getQuery()
            ->execute();
    }

    /**
     * @return User
     */
    public function findByIsEnabled(bool $isEnabled = true)
    {
        return $this
            ->createQueryBuilder('u')
            ->where('u.isEnabled = :isEnabled')
            ->setParameter('isEnabled', $isEnabled)
            ->getQuery()
            ->execute();
    }
}
