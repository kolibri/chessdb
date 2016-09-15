<?php


namespace AppBundle\Domain;

use Symfony\Component\Validator\Constraints\NotBlank;
use AppBundle\Validator\Constraints\Pgn as ValidPgn;

class Pgn
{
    /**
     * @var string
     *
     * @NotBlank
     * @ValidPgn
     */
    private $pgnString;

    /**
     * @return string
     */
    public function getPgnString()
    {
        return $this->pgnString;
    }

    /**
     * @param string $pgnString
     */
    public function setPgnString($pgnString)
    {
        $this->pgnString = $pgnString;
    }
}
