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
     * @param Player $player
     * @return Player[]|null
     */
    public function findOtherPlayers(Player $player)
    {
        return $this
            ->createQueryBuilder('p')
            ->where('p.uuid != :player')
            ->setParameter('player', $player)
            ->getQuery()
            ->execute();
    }

    public function createCanAssignedToUserQueryBuilder(User $user = null)
    {
        $builder = $this->createQueryBuilder('p')
            ->where('p.user is NULL');

        if (null != $user) {
            $builder->orWhere('p.user = :user')
                ->setParameter('user', $user);

        }

        return $builder;
    }

    public function getStatistics($limit = null)
    {
        $dql =
            'SELECT p.name, p.uuid,
                SUM(CASE WHEN 
                    (g.white = p.uuid AND g.result = :whiteWins) OR  
                    (g.black = p.uuid AND g.result = :blackWins) THEN 1 ELSE 0 END
                ) as won,
                SUM(CASE WHEN 
                    (g.white = p.uuid AND g.result = :blackWins) OR  
                    (g.black = p.uuid AND g.result = :whiteWins) THEN 1 ELSE 0 END
                ) as lost,
                SUM(CASE WHEN 
                    (g.white = p.uuid OR g.black= p.uuid) AND g.result = :draw THEN 1 ELSE 0 END
                ) as draw,
                SUM(CASE WHEN 
                    (g.white = p.uuid OR g.black= p.uuid) AND g.result = :unfinished THEN 1 ELSE 0 END
                ) as unfinished
            FROM ' . Player::class . ' p, ' . Game::class . ' g
            GROUP BY p.name, p.uuid
            ORDER BY won DESC, lost ASC
        ';

        $query = $this
            ->getEntityManager()
            ->createQuery($dql)
            ->setParameter('whiteWins', Game::RESULT_WHITE_WINS)
            ->setParameter('blackWins', Game::RESULT_BLACK_WINS)
            ->setParameter('draw', Game::RESULT_DRAW)
            ->setParameter('unfinished', Game::RESULT_UNFINISHED)
            ->setMaxResults($limit)
            ;

        return $query->getArrayResult();
    }
}