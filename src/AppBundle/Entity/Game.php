<?php


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

    /** @var string */
    private $event;

    /** @var string */
    private $site;

    /** @var PgnDate */
    private $date;

    /** @var string */
    private $round;

    /** @var string */
    private $white;

    /** @var string */
    private $black;

    /** @var string */
    private $result;

    /** @var array */
    private $moves;

    /** @var ImportPgn */
    private $originalPgn;

    public function __construct(
        $event,
        $site,
        PgnDate $date,
        $round,
        $white,
        $black,
        $result,
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

    /**
     * @return Uuid
     */
    public function getUuid()
    {
        return $this->uuid;
    }

    /**
     * @return string
     */
    public function getEvent()
    {
        return $this->event;
    }

    /**
     * @param string $event
     */
    public function setEvent($event)
    {
        $this->event = $event;
    }

    /**
     * @return string
     */
    public function getSite()
    {
        return $this->site;
    }

    /**
     * @param string $site
     */
    public function setSite($site)
    {
        $this->site = $site;
    }

    /**
     * @return PgnDate
     */
    public function getDate()
    {
        return $this->date;
    }

    /**
     * @param PgnDate $date
     */
    public function setDate(PgnDate $date)
    {
        $this->date = $date;
    }

    /**
     * @return string
     */
    public function getRound()
    {
        return $this->round;
    }

    /**
     * @param string $round
     */
    public function setRound($round)
    {
        $this->round = $round;
    }

    /**
     * @return string
     */
    public function getWhite()
    {
        return $this->white;
    }

    /**
     * @param string $white
     */
    public function setWhite($white)
    {
        $this->white = $white;
    }

    /**
     * @return string
     */
    public function getBlack()
    {
        return $this->black;
    }

    /**
     * @param string $black
     */
    public function setBlack($black)
    {
        $this->black = $black;
    }

    /**
     * @return string
     */
    public function getResult()
    {
        return $this->result;
    }

    /**
     * @param string $result
     */
    public function setResult($result)
    {
        $this->result = $result;
    }

    /**
     * @return array
     */
    public function getMoves()
    {
        return $this->moves;
    }

    /**
     * @param array $moves
     */
    public function setMoves(array $moves)
    {
        $this->moves = $moves;
    }

    /**
     * @return ImportPgn
     */
    public function getOriginalPgn()
    {
        return $this->originalPgn;
    }

    /**
     * @param ImportPgn $originalPgn
     */
    public function setOriginalPgn($originalPgn)
    {
        $this->originalPgn = $originalPgn;
    }

    public function getPgn()
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
