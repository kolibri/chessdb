<?php


namespace AppBundle\Entity;


use Ramsey\Uuid\Uuid;

class LeaderboardItem
{
    /** @var Uuid */
    private $uuid;

    /** @var string */
    private $player;

    /** @var int */
    private $games;

    /** @var int */
    private $won;

    /** @var int */
    private $lost;

    /** @var int */
    private $draw;

    /** @var Leaderboard */
    private $leaderboard;

    /** @var \DateTime */
    private $createdAt;

    /**
     * Leaderboard constructor.
     * @param string $player
     * @param int $games
     * @param int $won
     * @param int $lost
     * @param int $draw
     */
    public function __construct($player, $games, $won, $lost, $draw)
    {
        $this->player = $player;
        $this->games = $games;
        $this->won = $won;
        $this->lost = $lost;
        $this->draw = $draw;
        $this->createdAt = new \DateTime();
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
    public function getPlayer()
    {
        return $this->player;
    }

    /**
     * @return int
     */
    public function getGames()
    {
        return $this->games;
    }

    /**
     * @return int
     */
    public function getWon()
    {
        return $this->won;
    }

    /**
     * @return int
     */
    public function getLost()
    {
        return $this->lost;
    }

    /**
     * @return int
     */
    public function getDraw()
    {
        return $this->draw;
    }

    /**
     * @return mixed
     */
    public function getLeaderboard()
    {
        return $this->leaderboard;
    }

    /**
     * @param mixed $leaderboard
     */
    public function setLeaderboard($leaderboard)
    {
        $this->leaderboard = $leaderboard;
    }

    /**
     * @return \DateTime
     */
    public function getCreatedAt()
    {
        return $this->createdAt;
    }
}