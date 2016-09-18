<?php


namespace AppBundle\Entity\Repository;


use AppBundle\Entity\Game;
use AppBundle\Entity\ImportPgn;
use AppBundle\Entity\User;
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

    /**
     * @param ImportPgn $importPgn
     * @return Game|null
     * @throws \Doctrine\ORM\NonUniqueResultException
     */
    public function findOneByImportPgn(ImportPgn $importPgn)
    {
        return $this
            ->createQueryBuilder('g')
            ->where('g.originalPgn = :pgn')
            ->setParameter('pgn', $importPgn)
            ->getQuery()
            ->getOneOrNullResult();
    }

    /**
     * @param User $user
     * @return Game[]|null
     */
    public function findByUser(User $user)
    {
        return $this
            ->createQueryBuilder('g')
            ->where('g.white = :user')
            ->orWhere('g.black = :user')
            ->setParameter('user', $user->getUsername())
            ->getQuery()
            ->execute();
    }

}