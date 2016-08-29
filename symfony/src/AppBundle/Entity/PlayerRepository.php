<?php


namespace AppBundle\Entity;


use Doctrine\ORM\EntityRepository;

class PlayerRepository extends EntityRepository
{
    /**
     * @param string $name
     *
     * @return Player
     */
    public function findOrCreateNewPlayerByName($name)
    {
        if ($player = $this->findOneBy(['name' => $name])) {
            return $player;
        }

        $player = new Player($name);

        $entityManager = $this->getEntityManager();
        $entityManager->persist($player);
        $entityManager->flush(); // flush, so we get the user on the next call

        return $player;
    }

    /**
     * @param string $uuid
     * @return Player[]|null
     */
    public function findOtherPlayers($uuid)
    {
        return $this
            ->createQueryBuilder('p')
            ->where('p.uuid != :player')
            ->setParameter('player', $uuid)
            ->getQuery()
            ->execute();
    }
}