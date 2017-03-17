<?php declare(strict_types = 1);

namespace AppBundle\Entity;

use AppBundle\Domain\PgnDate;
use AppBundle\Helper\MovesTransformHelper;
use Ramsey\Uuid\Uuid;

class Game
{
    const RESULT_WHITE_WINS = '1-0';
    const RESULT_BLACK_WINS = '0-1';
    const RESULT_DRAW = '1/2-1/2';
    const RESULT_UNFINISHED = '*';

    /**@var Uuid */
    private $uuid;

    private $event;
    private $site;
    private $date;
    private $round;
    private $white;
    private $black;
    private $result;
    private $moves;
    private $originalPgn;

    private function __construct(
        string $event,
        string $site,
        PgnDate $date,
        string $round,
        string $white,
        string $black,
        string $result,
        array $moves,
        ImportPgn $originalPgn = null
    ) {
        $this->event = $event;
        $this->site = $site;
        $this->date = $date;
        $this->round = $round;
        $this->white = $white;
        $this->black = $black;
        $this->result = $result;
        $this->moves = $moves;
        $this->originalPgn = $originalPgn;
    }

    public static function create(
        string $event,
        string $site,
        PgnDate $date,
        string $round,
        string $white,
        string $black,
        string $result,
        array $moves,
        ImportPgn $originalPgn = null
    ) {
        return new self($event, $site, $date, $round, $white, $black, $result, $moves, $originalPgn);
    }

    public function getUuid(): Uuid
    {
        return $this->uuid;
    }

    public function getEvent(): string
    {
        return $this->event;
    }

    public function setEvent(string $event)
    {
        $this->event = $event;
    }

    public function getSite(): string
    {
        return $this->site;
    }

    public function setSite(string $site)
    {
        $this->site = $site;
    }

    public function getDate(): PgnDate
    {
        return $this->date;
    }

    public function setDate(PgnDate $date)
    {
        $this->date = $date;
    }

    public function getRound(): string
    {
        return $this->round;
    }

    public function setRound(string $round)
    {
        $this->round = $round;
    }

    public function getWhite(): string
    {
        return $this->white;
    }

    public function setWhite(string $white)
    {
        $this->white = $white;
    }

    public function getBlack(): string
    {
        return $this->black;
    }

    public function setBlack(string $black)
    {
        $this->black = $black;
    }

    public function getResult(): string
    {
        return $this->result;
    }

    public function setResult(string $result)
    {
        $this->result = $result;
    }

    public function getMoves(): array
    {
        return $this->moves;
    }

    public function setMoves(array $moves)
    {
        $this->moves = $moves;
    }

    public function getOriginalPgn(): ImportPgn
    {
        return $this->originalPgn;
    }

    public function setOriginalPgn(ImportPgn $originalPgn)
    {
        $this->originalPgn = $originalPgn;
    }

    public function getPgn(): string
    {
        $format = <<<EOF
[Event "%s"]
[Site "%s"]
[Date "%s"]
[Round "%s"]
[Result "%s"]
[White "%s"]
[Black "%s"]

%s %s
EOF;

        return sprintf(
            $format,
            $this->getEvent(),
            $this->getSite(),
            $this->getDate()->toString(),
            $this->getRound(),
            $this->getResult(),
            $this->getWhite(),
            $this->getBlack(),
            MovesTransformHelper::moveArrayToString($this->getMoves()),
            $this->getResult()
        );
    }
}
