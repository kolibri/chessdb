<?php declare(strict_types = 1);

namespace AppBundle\Factory;

use AppBundle\PgnParser\PgnParser;
use AppBundle\Domain\PgnDate;
use AppBundle\Entity\Game;
use AppBundle\Entity\ImportPgn;

class GameFactory
{
    private $chess;

    public function __construct(PgnParser $chess)
    {
        $this->chess = $chess;
    }

    public function createFromImportPgn(ImportPgn $importPgn): Game
    {
        $pgn = $importPgn->getPgnString();

        if (!$this->chess->validatePgn($pgn)) {
            throw new \InvalidArgumentException('Given PGN is not valid!');
        }

        $info = $this->chess->parsePgn($pgn);

        return Game::create(
            $info['header']['Event'],
            $info['header']['Site'],
            PgnDate::fromString($info['header']['Date']),
            $info['header']['Round'],
            $info['header']['White'],
            $info['header']['Black'],
            $info['header']['Result'],
            $info['moves'],
            $importPgn
        );
    }
}
