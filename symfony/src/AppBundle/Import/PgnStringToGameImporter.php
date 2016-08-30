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
    public function createGame($pgn)
    {
        $header = $this->getGameData($pgn);

        return new Game(
            $header['Event'],
            $header['Site'],
            $header['Date'],
            $header['Round'],
            $header['White'],
            $header['Black'],
            $header['Result'],
            $pgn
        );
    }

    public function updateGame(Game $game, $pgn)
    {
        $header = $this->getGameData($pgn);
        
        $game->setEvent($header['Event']);
        $game->setSite($header['Site']);
        $game->setDate($header['Date']);
        $game->setRound($header['Round']);
        $game->setWhite($header['White']);
        $game->setBlack($header['Black']);
        $game->setResult($header['Result']);
        $game->setPgn($pgn);

        return $game;
    }

    private function getGameData($pgn)
    {
        $header = $this->chess->parsePgn($pgn)['header'];
        $header['Date'] = \DateTime::createFromFormat('Y.m.d',$header['Date']);
        $header['White'] = $this->playerRepository->findOrCreateNewPlayerByName($header['White']);
        $header['Black'] = $this->playerRepository->findOrCreateNewPlayerByName($header['Black']);

        return $header;
    }
}
