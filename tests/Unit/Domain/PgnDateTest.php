<?php declare(strict_types = 1);

namespace Tests\AppBundle\Domain\PgnDate;

use AppBundle\Domain\PgnDate;
use PHPUnit\Framework\TestCase;

class PgnDateTest extends TestCase
{

    /**
     * @dataProvider pgnDateProvider
     */
    public function testCreateNewPgnDate($dateString, int $year, int $month, int $day)
    {
        $pgnDate = PgnDate::fromString($dateString);

        $this->assertSame($year, $pgnDate->getYear());
        $this->assertSame($month, $pgnDate->getMonth());
        $this->assertSame($day, $pgnDate->getDay());
        $this->assertSame($dateString, $pgnDate->toString());
    }

    /**
     * @dataProvider invalidPgnDateProvider
     */
    public function testInvalidDateFormats(string $invalidDateString)
    {
        $this->expectException(\InvalidArgumentException::class);

        $pgnDate = PgnDate::fromString($invalidDateString);
    }

    public function pgnDateProvider()
    {
        return [
            ['1992.02.31', 1992, 2, 31],
            ['1992.02.01', 1992, 2, 1],
            ['1992.12.13', 1992, 12, 13],
            ['1992.12.??', 1992, 12, 0],
            ['1992.??.13', 1992, 0, 13],
            ['????.12.13', 0, 12, 13],
            ['????.??.13', 0, 0, 13],
            ['????.??.??', 0, 0, 0],
        ];
    }

    public function invalidPgnDateProvider()
    {
        return [
            ['blafoo'],
            ['bla.foo.da'],
            ['bla.foo.31'],
            ['bla.23.31'],
            ['1234.23.31'],
            ['1234-23-31'],
            ['1234.23.31'],
            ['1992.13.32'],
            ['1992.12.32'],
            ['1992.13.32'],
            ['1992.13.-32'],
            ['1992.-13.32'],
            ['1992.13.32'],
        ];
    }
}
