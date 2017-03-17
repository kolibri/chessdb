<?php declare(strict_types = 1);

namespace AppBundle\Entity\Repository;

use AppBundle\Entity\Game;
use AppBundle\Entity\ImportPgn;
use AppBundle\Entity\User;
use Doctrine\ORM\EntityRepository;

class GameRepository extends EntityRepository
{
    public function save(Game $game, bool $flush = true)
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
     * @param string $player
     * @return Game[]|null
     */
    public function findByPlayer(User $player): array
    {
        return $this
            ->createQueryBuilder('g')
            ->where('g.white = :player')
            ->orWhere('g.black = :player')
            ->setParameter('player', $player->getUsername())
            ->getQuery()
            ->execute();
    }

    public function findByPlayerGroupByResult(User $player)
    {
        return [
            'won' => $this->findWonByPlayer($player),
            'lost' => $this->findLostByPlayer($player),
            'draw' => $this->findDrawByPlayer($player),
            'unfinished' => $this->findUnfinishedByPlayer($player),
        ];
    }

    public function findByPlayerVsPlayerGroupByResult(User $player1, User $player2)
    {
        return [
            'won' => $this->findWonPlayerVsPlayer($player1, $player2),
            'lost' => $this->findLostPlayerVsPlayer($player1, $player2),
            'draw' => $this->findDrawByPlayerVsPlayer($player1, $player2),
            'unfinished' => $this->findUnfinishedByPlayerVsPlayer($player1, $player2),
        ];
    }

    public function findWonByPlayer(User $player)
    {
        return $this->findWonOrLostByPlayer($player, true);
    }

    public function findLostByPlayer(User $player)
    {
        return $this->findWonOrLostByPlayer($player, false);
    }

    public function findDrawByPlayer(User $player)
    {
        return $this->findByPlayerAndResult($player, Game::RESULT_DRAW);
    }

    public function findUnfinishedByPlayer(User $player)
    {
        return $this->findByPlayerAndResult($player, Game::RESULT_UNFINISHED);
    }

    public function findWonPlayerVsPlayer(User $player1, User $player2)
    {
        return $this->findWonOrLostByPlayerVsPlayer($player1, $player2, true);
    }

    public function findLostPlayerVsPlayer(User $player1, User $player2)
    {
        return $this->findWonOrLostByPlayerVsPlayer($player1, $player2, false);
    }

    public function findDrawByPlayerVsPlayer(User $player1, User $player2)
    {
        return $this->findPlayerVsPlayerByResult($player1, $player2, Game::RESULT_DRAW);
    }

    public function findUnfinishedByPlayerVsPlayer(User $player1, User $player2)
    {
        return $this->findPlayerVsPlayerByResult($player1, $player2, Game::RESULT_UNFINISHED);
    }

    /**
     * @param $player1
     * @param $player2
     * @param $result
     * @return Game[]|null
     */
    public function findPlayerVsPlayerByResult(User $player1, User $player2, string $result)
    {
        return $this
            ->createQueryBuilder('g')
            ->where('g.white = :player1 AND g.black = :player2 AND g.result = :result')
            ->orWhere('g.white = :player2 AND g.black = :player1 AND g.result = :result')
            ->setParameter('player1', $player1->getUsername())
            ->setParameter('player2', $player2->getUsername())
            ->setParameter('result', $result)
            ->getQuery()
            ->execute();
    }

    /**
     * @param $player1
     * @param $player2
     * @param bool $whiteWon
     *
*@return Game[]|null
     */
    public function findWonOrLostByPlayerVsPlayer(User $player1, User $player2, bool $whiteWon = true)
    {
        return $this
            ->createQueryBuilder('g')
            ->where('g.white = :player1 AND g.black = :player2 AND g.result = :whiteWins')
            ->orWhere('g.white = :player2 AND g.black = :player1 AND g.result = :blackWins')
            ->setParameter('player1', $player1->getUsername())
            ->setParameter('player2', $player2->getUsername())
            ->setParameter('whiteWins', ($whiteWon ? Game::RESULT_WHITE_WINS : Game::RESULT_BLACK_WINS))
            ->setParameter('blackWins', (!$whiteWon ? Game::RESULT_WHITE_WINS : Game::RESULT_BLACK_WINS))
            ->getQuery()
            ->execute();
    }

    /**
     * @param $player
     * @param string $result
     * @return Game[]|null
     */
    public function findByPlayerAndResult(User $player, string $result)
    {
        return $this
            ->createQueryBuilder('g')
            ->where('g.white = :player OR g.black = :player')
            ->andWhere('g.result = :result')
            ->setParameter('result', $result)
            ->setParameter('player', $player->getUsername())
            ->getQuery()
            ->execute();
    }

    /**
     * @param $player
     * @param bool $won
     * @return Game[]|null
     */
    public function findWonOrLostByPlayer(User $player, $won = true)
    {
        return $this
            ->createQueryBuilder('g')
            ->where('g.white = :player AND g.result = :whiteResult')
            ->orWhere('g.black = :player AND g.result = :blackResult')
            ->setParameter('whiteResult', ($won ? Game::RESULT_WHITE_WINS : Game::RESULT_BLACK_WINS))
            ->setParameter('blackResult', (!$won ? Game::RESULT_WHITE_WINS : Game::RESULT_BLACK_WINS))
            ->setParameter('player', $player->getUsername())
            ->getQuery()
            ->execute();
    }
}
