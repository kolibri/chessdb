<?php

namespace AppBundle\Entity;

use Ramsey\Uuid\Uuid;

class Leaderboard
{
    /** @var  Uuid */
    private $uuid;

    /** @var  LeaderboardItem[] */
    private $items;

    /** @var \DateTime */
    private $createdAt;

    public function __construct($items = [])
    {
        $this->setItems($items);
        $this->createdAt = new \DateTime();
    }

    /**
     * @return LeaderboardItem[]
     */
    public function getItems()
    {
        return $this->items;
    }

    /**
     * @param LeaderboardItem[] $items
     */
    public function setItems($items)
    {
        foreach ($items as $item) {
            $this->addItem($item);
        }
    }

    /**
     * @param LeaderboardItem $item
     */
    public function addItem(LeaderboardItem $item)
    {
        $item->setLeaderboard($this);
        $this->items[$item->getPlayer()] = $item;
    }

    /**
     * @return Uuid
     */
    public function getUuid()
    {
        return $this->uuid;
    }

    /**
     * @return \DateTime
     */
    public function getCreatedAt()
    {
        return $this->createdAt;
    }
}
