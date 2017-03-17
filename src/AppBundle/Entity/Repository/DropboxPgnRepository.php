<?php declare(strict_types = 1);

namespace AppBundle\Entity\Repository;

use AppBundle\Entity\DropboxPgn;
use AppBundle\Entity\ImportPgn;
use AppBundle\Entity\User;
use Doctrine\ORM\EntityRepository;

class DropboxPgnRepository extends EntityRepository
{
    public function save(DropboxPgn $dropboxPgn, bool $flush = true)
    {
        $entityManager = $this->getEntityManager();
        $entityManager->persist($dropboxPgn);

        if ($flush) {
            $entityManager->flush();
        }
    }

    /**
     * @return DropboxPgn[]|null
     */
    public function findByUser(User $user): array
    {
        return $this
            ->createQueryBuilder('d')
            ->join('d.importPgn', 'i', 'WITH', 'i.user = :user')
            ->setParameter('user', $user)
            ->getQuery()
            ->execute();
    }

    /**
     * @return DropboxPgn[]|null
     */
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
