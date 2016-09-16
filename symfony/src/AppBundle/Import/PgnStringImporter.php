<?php


namespace AppBundle\Import;


use AppBundle\Adapter\ChessAdapter;
use AppBundle\Domain\PgnDate;
use AppBundle\Entity\Game;

class PgnStringImporter
{
    /** @var ChessAdapter */
    private $chess;

    /**
     * PgnStringImporter constructor.
     * @param ChessAdapter $chess
     */
    public function __construct(ChessAdapter $chess)
    {
        $this->chess = $chess;
    }

    public function importPgnString($pgn)
    {
        if (!$this->chess->validatePgn($pgn)) {
            throw new \InvalidArgumentException('Given PGN is not valid!');
        }

        $info = $this->chess->parsePgn($pgn);
        
        return new Game(
            $info['header']['Event'],
            $info['header']['Site'],
            PgnDate::fromString($info['header']['Date']),
            $info['header']['Round'],
            $info['header']['White'],
            $info['header']['Black'],
            $info['header']['Result'],
            $info['moves']
        );
   }
}