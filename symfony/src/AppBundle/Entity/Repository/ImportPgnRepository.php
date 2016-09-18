<?php


namespace AppBundle\Entity\Repository;


use AppBundle\Entity\DropboxPgn;
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

    public function remove(ImportPgn $importPgn, $flush = true)
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
        $mananger->flush();

    }
}