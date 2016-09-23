<?php


namespace AppBundle\Adapter;

use Ryanhs\Chess\Chess;

class ChessAdapter
{
    /** @var Chess */
    private $chess;

    /**
     * @param Chess $chess
     */
    public function __construct(Chess $chess)
    {
        $this->chess = $chess;
    }

    public function parsePgn($pgn)
    {
        return $this->chess->parsePgn($pgn);
    }

    public function validatePgn($pgn)
    {
        return $this->chess->validatePgn($pgn);
    }
}
