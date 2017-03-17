<?php declare(strict_types = 1);

namespace AppBundle\Adapter;

use Ryanhs\Chess\Chess;

class ChessAdapter
{
    private $chess;

    public function __construct(Chess $chess)
    {
        $this->chess = $chess;
    }

    public function parsePgn(string $pgn): array
    {
        return $this->chess->parsePgn($pgn);
    }

    public function validatePgn(string $pgn): bool
    {
        return $this->chess->validatePgn($pgn);
    }
}
