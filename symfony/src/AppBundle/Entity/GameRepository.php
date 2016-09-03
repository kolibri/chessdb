<?php


namespace AppBundle\Entity;


use Doctrine\ORM\EntityRepository;

class GameRepository extends EntityRepository
{
    /**
     * @param Player $player
     * @return Game[]|null
     */
    public function findWonByPlayer(Player $player)
    {
        return $this->findWonOrLostByPlayer($player, true);
    }

    /**
     * @param Player $player
     * @return Game[]|null
     */
    public function findLostByPlayer(Player $player)
    {
        return $this->findWonOrLostByPlayer($player, false);
    }

    /**
     * @param Player $player
     * @return Game[]|null
     */
    public function findDrawByPlayer(Player $player)
    {
        return $this->findByPlayerAndResult($player, Game::RESULT_DRAW);
    }

    /**
     * @param Player $player
     * @return Game[]|null
     */
    public function findUnfinishedByPlayer(Player $player)
    {
        return $this->findByPlayerAndResult($player, Game::RESULT_UNFINISHED);
    }

    /**
     * @param Player $player1
     * @param Player $player2
     * @return Game[]|null
     */
    public function findWonPlayerVsPlayer(Player $player1, Player $player2)
    {
        return $this->findWonOrLostByPlayerVsPlayer($player1, $player2, true);
    }

    /**
     * @param Player $player1
     * @param Player $player2
     * @return Game[]|null
     */
    public function findLostPlayerVsPlayer(Player $player1, Player $player2)
    {
        return $this->findWonOrLostByPlayerVsPlayer($player1, $player2, false);
    }

    /**
     * @param Player $player1
     * @param Player $player2
     * @return Game[]|null
     */
    public function findDrawByPlayerVsPlayer(Player $player1, Player $player2)
    {
        return $this->findPlayerVsPlayerByResult($player1, $player2, Game::RESULT_DRAW);
    }

    /**
     * @param Player $player1
     * @param Player $player2
     * @return Game[]|null
     */
    public function findUnfinishedByPlayerVsPlayer(Player $player1, Player $player2)
    {
        return $this->findPlayerVsPlayerByResult($player1, $player2, Game::RESULT_UNFINISHED);
    }

    /**
     * @param User $user
     * @return Game[]|null
     */
    public function findWonByUser(User $user)
    {
        return $this->findWonOrLostByUser($user, true);
    }

    /**
     * @param User $user
     * @return Game[]|null
     */
    public function findLostByUser(User $user)
    {
        return $this->findWonOrLostByUser($user, false);
    }

    /**
     * @param User $user
     * @return Game[]|null
     */
    public function findDrawByUser(User $user)
    {
        return $this->findByUserAndResult($user, Game::RESULT_DRAW);
    }

    /**
     * @param User $user
     * @return Game[]|null
     */
    public function findUnfinishedByUser(User $user)
    {
        return $this->findByUserAndResult($user, Game::RESULT_UNFINISHED);
    }

    /**
     * @param User $user1
     * @param User $user2
     * @return Game[]|null
     */
    public function findWonByUserVsUser(User $user1, User $user2)
    {
        return $this->findWonOrLostByUserVsUser($user1, $user2, true);
    }

    /**
     * @param User $user1
     * @param User $user2
     * @return Game[]|null
     */
    public function findLostByUserVsUser(User $user1, User $user2)
    {
        return $this->findWonOrLostByUserVsUser($user1, $user2, false);
    }

    /**
     * @param User $user1
     * @param User $user2
     * @return Game[]|null
     */
    public function findDrawByUserVsUser(User $user1, User $user2)
    {
        return $this->findUserVsUserByResult($user1, $user2, Game::RESULT_DRAW);
    }

    /**
     * @param User $user1
     * @param User $user2
     * @return Game[]|null
     */
    public function findUnfinishedByUserVsUser(User $user1, User $user2)
    {
        return $this->findUserVsUserByResult($user1, $user2, Game::RESULT_UNFINISHED);
    }

    /**
     * @param Player $player1
     * @param Player $player2
     * @param $result
     * @return Game[]|null
     */
    public function findPlayerVsPlayerByResult(Player $player1, Player $player2, $result)
    {
        return $this
            ->createQueryBuilder('g')
            ->where('g.white = :player1 AND g.black = :player2 AND g.result = :result')
            ->orWhere('g.white = :player2 AND g.black = :player1 AND g.result = :result')
            ->setParameter('player1', $player1)
            ->setParameter('player2', $player2)
            ->setParameter('result', $result)
            ->getQuery()
            ->execute();
    }

    /**
     * @param Player $player1
     * @param Player $player2
     * @param bool $won
     * @return Game[]|null
     */
    public function findWonOrLostByPlayerVsPlayer(Player $player1, Player $player2, $won = true)
    {
        return $this
            ->createQueryBuilder('g')
            ->where('g.white = :player1 AND g.black = :player2 AND g.result = :whiteWins')
            ->orWhere('g.white = :player2 AND g.black = :player1 AND g.result = :blackWins')
            ->setParameter('player1', $player1)
            ->setParameter('player2', $player2)
            ->setParameter('whiteWins', ($won ? Game::RESULT_WHITE_WINS : Game::RESULT_BLACK_WINS))
            ->setParameter('blackWins', (!$won ? Game::RESULT_WHITE_WINS : Game::RESULT_BLACK_WINS))
            ->getQuery()
            ->execute();
    }

    /**
     * @param Player $player
     * @param string $result
     * @return Game[]|null
     */
    public function findByPlayerAndResult(Player $player, $result)
    {
        return $this
            ->createQueryBuilder('g')
            ->where('g.white = :player OR g.black = :player')
            ->andWhere('g.result = :result')
            ->setParameter('result', $result)
            ->setParameter('player', $player)
            ->getQuery()
            ->execute();
    }

    /**
     * @param Player $player
     * @param bool $won
     * @return Game[]|null
     */
    public function findWonOrLostByPlayer(Player $player, $won = true)
    {
        return $this
            ->createQueryBuilder('g')
            ->where('g.white = :player AND g.result = :whiteResult')
            ->orWhere('g.black = :player AND g.result = :blackResult')
            ->setParameter('whiteResult', ($won ? Game::RESULT_WHITE_WINS : Game::RESULT_BLACK_WINS))
            ->setParameter('blackResult', (!$won ? Game::RESULT_WHITE_WINS : Game::RESULT_BLACK_WINS))
            ->setParameter('player', $player)
            ->getQuery()
            ->execute();
    }

    public function findWonOrLostByUser(User $user, $won = true)
    {
        $queryBuilder = $this->createQueryBuilder('g');

        return $queryBuilder
            ->add('where', $queryBuilder->expr()->orX(
                $queryBuilder->expr()->andX(
                    $queryBuilder->expr()->in('g.white', ':players'),
                    $queryBuilder->expr()->eq('g.result', ':whiteResult')
                ),
                $queryBuilder->expr()->andX(
                    $queryBuilder->expr()->in('g.black', ':players'),
                    $queryBuilder->expr()->eq('g.result', ':blackResult')
                )
            ))
            ->setParameter('whiteResult', ($won ? Game::RESULT_WHITE_WINS : Game::RESULT_BLACK_WINS))
            ->setParameter('blackResult', (!$won ? Game::RESULT_WHITE_WINS : Game::RESULT_BLACK_WINS))
            ->setParameter('players', $user->getPlayers())
            ->getQuery()
            ->execute();
    }

    /**
     * @param User $user
     * @param string $result
     * @return Game[]|null
     */
    public function findByUserAndResult(User $user, $result)
    {
        $queryBuilder = $this->createQueryBuilder('g');

        return $queryBuilder
            ->add('where', $queryBuilder->expr()->andX(
                $queryBuilder->expr()->orX(
                    $queryBuilder->expr()->in('g.white', ':players'),
                    $queryBuilder->expr()->in('g.black', ':players')
                ),
                $queryBuilder->expr()->eq('g.result', ':result')
            ))
            ->setParameter('result', $result)
            ->setParameter('players', $user->getPlayers())
            ->getQuery()
            ->execute();
    }

    /**
     * @param User $user1
     * @param User $user2
     * @param bool $won
     * @return Game[]|null
     */
    public function findWonOrLostByUserVsUser(User $user1, User $user2, $won = true)
    {
        $queryBuilder = $this->createQueryBuilder('g');

        return $queryBuilder
            ->add('where', $queryBuilder->expr()->orX(
                $queryBuilder->expr()->andX(
                    $queryBuilder->expr()->in('g.white', ':players1'),
                    $queryBuilder->expr()->in('g.black', ':players2'),
                    $queryBuilder->expr()->eq('g.result', ':whiteWins')
                ),
                $queryBuilder->expr()->andX(
                    $queryBuilder->expr()->in('g.white', ':players1'),
                    $queryBuilder->expr()->in('g.black', ':players2'),
                    $queryBuilder->expr()->eq('g.result', ':blackWins')
                )
            ))
            ->setParameter('players1', $user1->getPlayers())
            ->setParameter('players2', $user2->getPlayers())
            ->setParameter('whiteWins', ($won ? Game::RESULT_WHITE_WINS : Game::RESULT_BLACK_WINS))
            ->setParameter('blackWins', (!$won ? Game::RESULT_WHITE_WINS : Game::RESULT_BLACK_WINS))
            ->getQuery()
            ->execute();
    }

    /**
     * @param User $user1
     * @param User $user2
     * @param string $result
     * @return Game[]|null
     */
    public function findUserVsUserByResult(User $user1, User $user2, $result)
    {
        $queryBuilder = $this->createQueryBuilder('g');

        return $queryBuilder
            ->add('where', $queryBuilder->expr()->orX(
                $queryBuilder->expr()->andX(
                    $queryBuilder->expr()->in('g.white', ':players1'),
                    $queryBuilder->expr()->in('g.black', ':players2'),
                    $queryBuilder->expr()->eq('g.result', ':result')
                ),
                $queryBuilder->expr()->andX(
                    $queryBuilder->expr()->in('g.white', ':players1'),
                    $queryBuilder->expr()->in('g.black', ':players2'),
                    $queryBuilder->expr()->eq('g.result', ':result')
                )
            ))
            ->setParameter('players1', $user1->getPlayers())
            ->setParameter('players2', $user2->getPlayers())
            ->setParameter('result', $result)
            ->getQuery()
            ->execute();
    }
}