<?php declare(strict_types = 1);


namespace AppBundle\Entity\Repository;

use AppBundle\Entity\DropboxPgn;
use AppBundle\Entity\ImportPgn;
use AppBundle\Entity\User;
use Doctrine\ORM\EntityRepository;

class ImportPgnRepository extends EntityRepository
{
    public function save(ImportPgn $importedPgn, bool $flush = true)
    {
        $entityManager = $this->getEntityManager();
        $entityManager->persist($importedPgn);

        if ($flush) {
            $entityManager->flush();
        }
    }

    /**
     * @return ImportPgn[]|null
     */
    public function findUnimportedByUser(User $user)
    {
        return $this
            ->createQueryBuilder('p')
            ->where('p.imported = :imported')
            ->andWhere('p.user = :user')
            ->setParameter('imported', false)
            ->setParameter('user', $user)
            ->getQuery()
            ->execute();
    }

    public function remove(ImportPgn $importPgn, bool $flush = true)
    {
        $mananger = $this->getEntityManager();

        $dropboxPgns = $this
            ->getEntityManager()
            ->getRepository(DropboxPgn::class)
            ->getByImportPgn($importPgn);

        foreach ($dropboxPgns as $dropboxPgn) {
            $mananger->remove($dropboxPgn);
        }

        $mananger->remove($importPgn);
        if ($flush) {
            $mananger->flush();
        }
    }
}
