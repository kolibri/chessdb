<?php declare(strict_types = 1);

namespace AppBundle\PgnParser;

use Ryanhs\Chess\Chess;

class RyanhsChessPgnParserInterface implements PgnParserInterface
{
    /**
     * @param string $pgn
     * @return array
     */
    public function parsePgn(string $pgn): array
    {
        return Chess::parsePgn($pgn);
    }

    /**
     * @param string $pgn
     * @return bool
     */
    public function validatePgn(string $pgn): bool
    {
        return Chess::validatePgn($pgn);
    }
}
