<?php


namespace AppBundle\Entity;


use Doctrine\ORM\EntityRepository;

class GameRepository extends EntityRepository
{
    /**
     * @param string $playerId
     * @return Game[]|null
     */
    public function findWonGamesByPlayerId($playerId)
    {
        return $this->findWonOrLostGamesByPlayerId($playerId, true);
    }

    /**
     * @param string $playerId
     * @return Game[]|null
     */
    public function findLostGamesByPlayerId($playerId)
    {
        return $this->findWonOrLostGamesByPlayerId($playerId, false);
    }

    /**
     * @param string $playerId
     * @return Game[]|null
     */
    public function findDrawGamesByPlayerId($playerId)
    {
        return $this->findGamesByPlayerIdAndResult($playerId, Game::RESULT_DRAW);
    }

    /**
     * @param string $playerId
     * @return Game[]|null
     */
    public function findUnfinishedGamesByPlayerId($playerId)
    {
        return $this->findGamesByPlayerIdAndResult($playerId, Game::RESULT_UNFINISHED);
    }

    /**
     * @param string $player1Id
     * @param string $player2Id
     * @return Game[]|null
     */
    public function findWonGamesPlayerVsPlayer($player1Id, $player2Id)
    {
        return $this->findWonOrLostGamesPlayerVsPlayer($player1Id, $player2Id, true);
    }

    /**
     * @param string $player1Id
     * @param string $player2Id
     * @return Game[]|null
     */
    public function findLostGamesPlayerVsPlayer($player1Id, $player2Id)
    {
        return $this->findWonOrLostGamesPlayerVsPlayer($player1Id, $player2Id, false);
    }

    /**
     * @param string $player1
     * @param string $player2
     * @return Game[]|null
     */
    public function findDrawGamesPlayerVsPlayer($player1, $player2)
    {
        return $this->findGamesPlayerVsPlayerByResult($player1, $player2, Game::RESULT_DRAW);
    }

    /**
     * @param string $player1
     * @param string $player2
     * @return Game[]|null
     */
    public function findUnfinishedGamesPlayerVsPlayer($player1, $player2)
    {
        return $this->findGamesPlayerVsPlayerByResult($player1, $player2, Game::RESULT_UNFINISHED);
    }

    /**
     * @param string $player1Id
     * @param string $player2Id
     * @param string $result
     * @return Game[]|null
     */
    public function findGamesPlayerVsPlayerByResult($player1Id, $player2Id, $result)
    {
        return $this
            ->createQueryBuilder('g')
            ->where('g.white = :player1 AND g.black = :player2 AND g.result = :result')
            ->orWhere('g.white = :player2 AND g.black = :player1 AND g.result = :result')
            ->setParameter('player1', $player1Id)
            ->setParameter('player2', $player2Id)
            ->setParameter('result', $result)
            ->getQuery()
            ->execute();
    }

    /**
     * @param string $player1Id
     * @param string $player2Id
     * @param bool $won
     * @return Game[]|null
     */
    public function findWonOrLostGamesPlayerVsPlayer($player1Id, $player2Id, $won = true)
    {
        return $this
            ->createQueryBuilder('g')
            ->where('g.white = :player1 AND g.black = :player2 AND g.result = :whiteWins')
            ->orWhere('g.white = :player2 AND g.black = :player1 AND g.result = :blackWins')
            ->setParameter('player1', $player1Id)
            ->setParameter('player2', $player2Id)
            ->setParameter('whiteWins', ($won ? Game::RESULT_WHITE_WINS : Game::RESULT_BLACK_WINS))
            ->setParameter('blackWins', (!$won ? Game::RESULT_WHITE_WINS : Game::RESULT_BLACK_WINS))
            ->getQuery()
            ->execute();
    }

    /**
     * @param string $playerId
     * @param string $result
     * @return Game[]|null
     */
    public function findGamesByPlayerIdAndResult($playerId, $result)
    {
        return $this
            ->createQueryBuilder('g')
            ->where('g.white = :player OR g.black = :player')
            ->andWhere('g.result = :result')
            ->setParameter('result', $result)
            ->setParameter('player', $playerId)
            ->getQuery()
            ->execute();
    }

    /**
     * @param string $playerId
     * @param bool $won
     * @return Game[]|null
     */
    public function findWonOrLostGamesByPlayerId($playerId, $won = true)
    {
        return $this
            ->createQueryBuilder('g')
            ->where('g.white = :player AND g.result = :whiteResult')
            ->orWhere('g.black = :player AND g.result = :blackResult')
            ->setParameter('whiteResult', ($won ? Game::RESULT_WHITE_WINS : Game::RESULT_BLACK_WINS))
            ->setParameter('blackResult',(!$won ? Game::RESULT_WHITE_WINS : Game::RESULT_BLACK_WINS))
            ->setParameter('player', $playerId)
            ->getQuery()
            ->execute();
    }

}