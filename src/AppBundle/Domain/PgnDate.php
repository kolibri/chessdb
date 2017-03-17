<?php declare(strict_types = 1);

namespace AppBundle\Domain;

class PgnDate
{
    private $year;
    private $month;
    private $day;

    private function __construct(int $year, int $month, int $day)
    {
        $this->setYear($year);
        $this->setMonth($month);
        $this->setDay($day);
    }

    public static function fromString(string $dateString): self
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

    public function getYear(): int
    {
        return $this->year;
    }

    public function setYear($year)
    {
        if ('????' === $year) {
            $year = 0;
        }
        $this->year = (int) $year;
    }

    public function getMonth(): int
    {
        return $this->month;
    }

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

    public function getDay(): int
    {
        return $this->day;
    }

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

    public function toString(): string
    {
        return sprintf(
            '%s.%s.%s',
            (0 < $this->getYear()) ? sprintf("%'04d", $this->getYear()) : '????',
            (0 < $this->getMonth()) ? sprintf("%'02d", $this->getMonth()) : '??',
            (0 < $this->getDay()) ? sprintf("%'02d", $this->getDay()) : '??'
        );
    }
}
