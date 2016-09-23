<?php


namespace AppBundle\Entity\Repository;

use AppBundle\Entity\ImportPgn;
use Doctrine\ORM\EntityRepository;

class DropboxPgnRepository extends EntityRepository
{
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
            ->getResult()
            ;
    }
}
