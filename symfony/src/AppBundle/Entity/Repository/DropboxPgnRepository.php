<?php


namespace AppBundle\Entity\Repository;

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
}