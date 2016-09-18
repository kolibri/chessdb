<?php


namespace AppBundle\Entity\Repository;


use AppBundle\Entity\ImportPgn;
use Doctrine\ORM\EntityRepository;

class ImportPgnRepository extends EntityRepository
{
    public function save(ImportPgn $importedPgn, $flush = true)
    {
        $entityManager = $this->getEntityManager();
        $entityManager->persist($importedPgn);

        if ($flush) {
            $entityManager->flush();
        }
    }

    public function findUnimported()
    {
        return $this
            ->createQueryBuilder('p')
            ->where('p.imported = :imported')
            ->setParameter('imported', false)
            ->getQuery()
            ->execute();
    }
}