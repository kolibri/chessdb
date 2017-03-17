<?php declare(strict_types = 1);

namespace Tests\AppBundle\Factory;

use AppBundle\PgnParser\PgnParserInterface;
use AppBundle\Entity\Game;
use AppBundle\Entity\ImportPgn;
use AppBundle\Factory\GameFactory;
use PHPUnit\Framework\TestCase;

class GameFactoryTest extends TestCase
{
    public function testCreateFromImportedPgn()
    {
        /** @var PgnParserInterface|\PHPUnit_Framework_MockObject_MockObject $chessMock */
        $chessMock = $this->createMock(PgnParserInterface::class);
        $chessMock->expects($this->once())
            ->method('validatePgn')
            ->willReturn(true);
        $chessMock->expects($this->once())
            ->method('parsePgn')
            ->willReturn([
                'header' => [
                    'Event' => 'event',
                    'Site' => 'site',
                    'Date' => '1995.10.05',
                    'Round' => '1',
                    'White' => 'white',
                    'Black' => 'black',
                    'Result' => '1-0',
                ],
                'moves' => ['e4', 'd5']
            ]);

        $gameFactory = new GameFactory($chessMock);

        $pgnMock = $this->createMock(ImportPgn::class);

        $game = $gameFactory->createFromImportPgn($pgnMock);

        $this->assertInstanceOf(Game::class, $game);
        $this->assertEquals('event', $game->getEvent());
        $this->assertEquals('site', $game->getSite());
        $this->assertEquals('1995.10.05', $game->getDate()->toString());
        $this->assertEquals('1', $game->getRound());
        $this->assertEquals('white', $game->getWhite());
        $this->assertEquals('black', $game->getBlack());
        $this->assertEquals(['e4', 'd5'], $game->getMoves());
    }

    private function getSamplePgn()
    {
        return <<<EOF
[Event "Simultaneous"]
[Site "Budapest HUN"]
[Date "1934.??.??"]
[EventDate "?"]
[Round "?"]
[Result "1-0"]
[White "Esteban Canal"]
[Black "NN"]
[ECO "B01"]
[WhiteElo "?"]
[BlackElo "?"]
[PlyCount "27"]

1.e4 d5 2.exd5 Qxd5 3.Nc3 Qa5 4.d4 c6 5.Nf3 Bg4 6.Bf4 e6 7.h3
Bxf3 8.Qxf3 Bb4 9.Be2 Nd7 10.a3 O-O-O 11.axb4 Qxa1+ 12.Kd2
Qxh1 13.Qxc6+ bxc6 14.Ba6# 1-0

EOF;
    }
}
