<?php


namespace AppBundle\Entity;


use Doctrine\ORM\EntityRepository;

class GameRepository extends EntityRepository
{
    /**
     * @param Player $player
     * @return Game[]|null
     */
    public function findWonGamesByPlayer(Player $player)
    {
        return $this->findWonOrLostGamesByPlayer($player, true);
    }

    /**
     * @param Player $player
     * @return Game[]|null
     */
    public function findLostGamesByPlayer(Player $player)
    {
        return $this->findWonOrLostGamesByPlayer($player, false);
    }

    /**
     * @param Player $player
     * @return Game[]|null
     */
    public function findDrawGamesByPlayer(Player $player)
    {
        return $this->findGamesByPlayerIdAndResult($player, Game::RESULT_DRAW);
    }

    /**
     * @param Player $player
     * @return Game[]|null
     */
    public function findUnfinishedGamesByPlayer(Player $player)
    {
        return $this->findGamesByPlayerIdAndResult($player, Game::RESULT_UNFINISHED);
    }

    /**
     * @param Player $player1
     * @param Player $player2
     * @return Game[]|null
     */
    public function findWonGamesPlayerVsPlayer(Player $player1, Player $player2)
    {
        return $this->findWonOrLostGamesPlayerVsPlayer($player1, $player2, true);
    }

    /**
     * @param Player $player1
     * @param Player $player2
     * @return Game[]|null
     */
    public function findLostGamesPlayerVsPlayer(Player $player1, Player $player2)
    {
        return $this->findWonOrLostGamesPlayerVsPlayer($player1, $player2, false);
    }

    /**
     * @param Player $player1
     * @param Player $player2
     * @return Game[]|null
     */
    public function findDrawGamesPlayerVsPlayer(Player $player1, Player $player2)
    {
        return $this->findGamesPlayerVsPlayerByResult($player1, $player2, Game::RESULT_DRAW);
    }

    /**
     * @param Player $player1
     * @param Player $player2
     * @return Game[]|null
     */
    public function findUnfinishedGamesPlayerVsPlayer(Player $player1, Player $player2)
    {
        return $this->findGamesPlayerVsPlayerByResult($player1, $player2, Game::RESULT_UNFINISHED);
    }

    /**
     * @param Player $player1
     * @param Player $player2
     * @param $result
     * @return Game[]|null
     */
    public function findGamesPlayerVsPlayerByResult(Player $player1, Player $player2, $result)
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
    public function findWonOrLostGamesPlayerVsPlayer(Player $player1, Player $player2, $won = true)
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
    public function findGamesByPlayerIdAndResult(Player $player, $result)
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
    public function findWonOrLostGamesByPlayer(Player $player, $won = true)
    {
        return $this
            ->createQueryBuilder('g')
            ->where('g.white = :player AND g.result = :whiteResult')
            ->orWhere('g.black = :player AND g.result = :blackResult')
            ->setParameter('whiteResult', ($won ? Game::RESULT_WHITE_WINS : Game::RESULT_BLACK_WINS))
            ->setParameter('blackResult',(!$won ? Game::RESULT_WHITE_WINS : Game::RESULT_BLACK_WINS))
            ->setParameter('player', $player)
            ->getQuery()
            ->execute();
    }

}