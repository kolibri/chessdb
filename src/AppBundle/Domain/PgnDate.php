<?php declare(strict_types = 1);

namespace AppBundle\Domain;

class PgnDate
{
    /** @var integer */
    private $year;

    /** @var integer */
    private $month;

    /** @var integer */
    private $day;

    /**
     * PgnDate constructor.
     *
     * @param int $year
     * @param int $month
     * @param int $day
     */
    private function __construct($year, $month, $day)
    {
        $this->setYear($year);
        $this->setMonth($month);
        $this->setDay($day);
    }

    public static function fromString($dateString)
    {
        $regex = '/([\d\?]{4})\.([\d\?]{2})\.([\d\?]{2})/';

        if (!preg_match($regex, $dateString, $matches)) {
            throw new \InvalidArgumentException(sprintf('the string "%s" is not a valid pgn date.', $dateString));
        }

        return new self(
            $matches[1],
            $matches[2],
            $matches[3]
        );
    }

    /**
     * @return int
     */
    public function getYear()
    {
        return $this->year;
    }

    /**
     * @param int $year
     */
    public function setYear($year)
    {
        if ('????' === $year) {
            $year = 0;
        }
        $this->year = (int) $year;
    }

    /**
     * @return int
     */
    public function getMonth()
    {
        return $this->month;
    }

    /**
     * @param int $month
     */
    public function setMonth($month)
    {
        if ('??' === $month) {
            $month = 0;
        }

        if ($month > 12 || $month < 0) {
            throw new \InvalidArgumentException(sprintf('given month "%s" is not valid', $month));
        }

        $this->month = (int) $month;
    }

    /**
     * @return int
     */
    public function getDay()
    {
        return $this->day;
    }

    /**
     * @param int $day
     */
    public function setDay($day)
    {
        if ('??' === $day) {
            $day = 0;
        }

        if ($day > 31) {
            throw new \InvalidArgumentException(sprintf('given day "%s" is not valid', $day));
        }
        $this->day = (int) $day;
    }

    public function toString()
    {
        return sprintf(
            '%s.%s.%s',
            (0 < $this->getYear()) ? sprintf("%'04d", $this->getYear()) : '????',
            (0 < $this->getMonth()) ? sprintf("%'02d", $this->getMonth()) : '??',
            (0 < $this->getDay()) ? sprintf("%'02d", $this->getDay()) : '??'
        );
    }
}
