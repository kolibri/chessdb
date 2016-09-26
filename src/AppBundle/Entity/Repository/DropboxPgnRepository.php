<?php


namespace AppBundle\Entity\Repository;

use AppBundle\Entity\DropboxPgn;
use AppBundle\Entity\ImportPgn;
use AppBundle\Entity\User;
use Doctrine\ORM\EntityRepository;

class DropboxPgnRepository extends EntityRepository
{
    public function save(DropboxPgn $dropboxPgn, $flush = true)
    {
        $entityManager = $this->getEntityManager();
        $entityManager->persist($dropboxPgn);

        if ($flush) {
            $entityManager->flush();
        }
    }

    /**
     * @param User $user
     * @return DropboxPgn[]
     */
    public function findByUser(User $user)
    {
        return $this
            ->createQueryBuilder('d')
            ->join('d.importPgn', 'i', 'WITH', 'i.user = :user')
            ->setParameter('user', $user)
            ->getQuery()
            ->execute();
    }

    public function getByPathOrNull($path)
    {
        return $this
            ->createQueryBuilder('d')
            ->where('d.path = :path')
            ->setParameter('path', $path)
            ->getQuery()
            ->getOneOrNullResult();
    }

    public function getByImportPgn(ImportPgn $importPgn)
    {
        return $this
            ->createQueryBuilder('d')
            ->where('d.importPgn = :importPgn')
            ->setParameter('importPgn', $importPgn)
            ->getQuery()
            ->getResult();
    }
}
