<?php


namespace AppBundle\Entity;

use Ramsey\Uuid\Uuid;
use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Validator\Constraints as Assert;


/**
 * @ORM\Entity(repositoryClass="GameRepository")
 */
class Game
{
    const RESULT_WHITE_WINS = '1-0';
    const RESULT_BLACK_WINS = '0-1';
    const RESULT_DRAW = '1/2-1/2';
    const RESULT_UNFINISHED = '*';

    /**
     * @var Uuid
     *
     * @ORM\Id
     * @ORM\Column(type="uuid")
     * @ORM\GeneratedValue(strategy="CUSTOM")
     * @ORM\CustomIdGenerator(class="Ramsey\Uuid\Doctrine\UuidGenerator")
     */
    private $uuid;

    /**
     * @var string
     *
     * @ORM\Column
     * @Assert\NotBlank
     */
    private $event;

    /**
     * @var string
     *
     * @ORM\Column
     * @Assert\NotBlank
     */
    private $site;

    /**
     * @var \DateTime
     *
     * @ORM\Column(type="date")
     * @Assert\NotBlank
     * @Assert\Date
     */
    private $date;

    /**
     * @var string
     *
     * @ORM\Column
     * @Assert\NotBlank
     */
    private $round;

    /**
     * @var Player
     *
     * @ORM\ManyToOne(targetEntity="Player")
     * @ORM\JoinColumn(name="white_id", referencedColumnName="uuid")
     * @Assert\NotBlank
     */
    private $white;

    /**
     * @var Player
     *
     * @ORM\ManyToOne(targetEntity="Player")
     * @ORM\JoinColumn(name="black_id", referencedColumnName="uuid")
     * @Assert\NotBlank
     */
    private $black;

    /**
     * @var string
     *
     * @ORM\Column(length=7)
     * @Assert\NotBlank
     * @Assert\Choice({
     *     Game::RESULT_WHITE_WINS,
     *     Game::RESULT_BLACK_WINS,
     *     Game::RESULT_DRAW,
     *     Game::RESULT_UNFINISHED
     *  })
     */
    private $result;

    /**
     * @var string
     *
     * @ORM\Column(type="text")
     * @Assert\NotBlank
     */
    private $pgn;

    /**
     * ChessGame constructor.
     * @param string $event
     * @param string $site
     * @param \DateTime $date
     * @param string $round
     * @param Player $white
     * @param Player $black
     * @param string $result
     * @param string $pgn
     */
    public function __construct($event, $site, \DateTime $date, $round, Player $white, Player $black, $result, $pgn)
    {
        $this->event = $event;
        $this->site = $site;
        $this->date = $date;
        $this->round = $round;
        $this->white = $white;
        $this->black = $black;
        $this->result = $result;
        $this->pgn = $pgn;
    }

    /**
     * @return \Ramsey\Uuid\Uuid
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
     * @return string
     */
    public function getSite()
    {
        return $this->site;
    }

    /**
     * @return \DateTime
     */
    public function getDate()
    {
        return $this->date;
    }

    /**
     * @return string
     */
    public function getRound()
    {
        return $this->round;
    }

    /**
     * @return Player
     */
    public function getWhite()
    {
        return $this->white;
    }

    /**
     * @return Player
     */
    public function getBlack()
    {
        return $this->black;
    }

    /**
     * @return string
     */
    public function getResult()
    {
        return $this->result;
    }

    /**
     * @return string
     */
    public function getPgn()
    {
        return $this->pgn;
    }
}