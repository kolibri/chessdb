<?php declare(strict_types = 1);

namespace AppBundle\PgnParser;

interface PgnParser
{
    /**
     * @param string $pgn
     * @return array
     */
    public function parsePgn(string $pgn): array;

    /**
     * @param string $pgn
     * @return bool
     */
    public function validatePgn(string $pgn): bool;
}
