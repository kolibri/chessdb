<?php


namespace AppBundle\Entity\Repository;

use AppBundle\Entity\Game;
use AppBundle\Entity\ImportPgn;
use AppBundle\Entity\User;
use Doctrine\ORM\EntityRepository;

class GameRepository extends EntityRepository
{
    public function save(Game $game, $flush = true)
    {
        $entityManager = $this->getEntityManager();
        $entityManager->persist($game);

        if ($flush) {
            $entityManager->flush();
        }
    }

    /**
     * @param ImportPgn $importPgn
     * @return Game|null
     * @throws \Doctrine\ORM\NonUniqueResultException
     */
    public function findOneByImportPgn(ImportPgn $importPgn)
    {
        return $this
            ->createQueryBuilder('g')
            ->where('g.originalPgn = :pgn')
            ->setParameter('pgn', $importPgn)
            ->getQuery()
            ->getOneOrNullResult();
    }

    /**
     * @param User $player
     * @return Game[]|null
     */
    public function findByPlayer($player)
    {
        return $this
            ->createQueryBuilder('g')
            ->where('g.white = :player')
            ->orWhere('g.black = :player')
            ->setParameter('player', $player)
            ->getQuery()
            ->execute();
    }

    public function findByPlayerGroupByResult($player)
    {
        return [
            'won' => $this->findWonByPlayer($player),
            'lost' => $this->findLostByPlayer($player),
            'draw' => $this->findDrawByPlayer($player),
            'unfinished' => $this->findUnfinishedByPlayer($player),
        ];
    }

    public function findWonByPlayer($player)
    {
        return $this->findWonOrLostByPlayer($player, true);
    }

    public function findLostByPlayer($player)
    {
        return $this->findWonOrLostByPlayer($player, false);
    }

    public function findDrawByPlayer($player)
    {
        return $this->findByPlayerAndResult($player, Game::RESULT_DRAW);
    }

    public function findUnfinishedByPlayer($player)
    {
        return $this->findByPlayerAndResult($player, Game::RESULT_UNFINISHED);
    }

    public function findWonPlayerVsPlayer($player1, $player2)
    {
        return $this->findWonOrLostByPlayerVsPlayer($player1, $player2, true);
    }

    public function findLostPlayerVsPlayer($player1, $player2)
    {
        return $this->findWonOrLostByPlayerVsPlayer($player1, $player2, false);
    }

    public function findDrawByPlayerVsPlayer($player1, $player2)
    {
        return $this->findPlayerVsPlayerByResult($player1, $player2, Game::RESULT_DRAW);
    }

    public function findUnfinishedByPlayerVsPlayer($player1, $player2)
    {
        return $this->findPlayerVsPlayerByResult($player1, $player2, Game::RESULT_UNFINISHED);
    }

    /**
     * @param $player1
     * @param $player2
     * @param $result
     * @return Game[]|null
     */
    public function findPlayerVsPlayerByResult($player1, $player2, $result)
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
     * @param $player1
     * @param $player2
     * @param bool $won
     * @return Game[]|null
     */
    public function findWonOrLostByPlayerVsPlayer($player1, $player2, $won = true)
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
     * @param $player
     * @param string $result
     * @return Game[]|null
     */
    public function findByPlayerAndResult($player, $result)
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
     * @param $player
     * @param bool $won
     * @return Game[]|null
     */
    public function findWonOrLostByPlayer($player, $won = true)
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
}
