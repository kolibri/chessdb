<?php


namespace AppBundle\Import;

use AppBundle\Entity\Game;
use AppBundle\Adapter\ChessAdapter;
use AppBundle\Entity\PlayerRepository;

class PgnStringToGameImporter
{
    /** @var ChessAdapter */
    private $chess;
    
    /** @var PlayerRepository */
    private $playerRepository;

    /**
     * PgnToChessGameImporter constructor.
     * @param ChessAdapter $chess
     * @param PlayerRepository $playerRepository
     */
    public function __construct(ChessAdapter $chess, PlayerRepository $playerRepository)
    {
        $this->chess = $chess;
        $this->playerRepository = $playerRepository;
    }

    /**
     * @param $pgn
     * @return Game
     */
    public function createChessGame($pgn)
    {
        $header = $this->chess->parsePgn($pgn)['header'];

        $date = \DateTime::createFromFormat('Y.m.d',$header['Date']);

        $whitePlayer = $this->playerRepository->findOrCreateNewPlayerByName($header['White']);
        $blackPlayer = $this->playerRepository->findOrCreateNewPlayerByName($header['Black']);

        return new Game(
            $header['Event'],
            $header['Site'],
            $date,
            $header['Round'],
            $whitePlayer,
            $blackPlayer,
            $header['Result'],
            $pgn
        );
    }
}
