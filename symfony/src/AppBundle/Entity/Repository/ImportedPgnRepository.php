<?php


namespace AppBundle\Entity\Repository;


use AppBundle\Entity\ImportPgn;
use Doctrine\ORM\EntityRepository;

class ImportedPgnRepository extends EntityRepository
{
    public function save(ImportPgn $importedPgn, $flush = true)
    {
        $entityManager = $this->getEntityManager();
        $entityManager->persist($importedPgn);

        if ($flush) {
            $entityManager->flush();
        }
    }
}