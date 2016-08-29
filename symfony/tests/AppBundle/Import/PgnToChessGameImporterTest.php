<?php


namespace Tests\AppBundle\Import;


use AppBundle\Adapter\ChessAdapter;
use AppBundle\Entity\Player;
use AppBundle\Entity\PlayerRepository;
use AppBundle\Import\PgnStringToGameImporter;

class PgnToChessGameImporterTest extends \PHPUnit_Framework_TestCase
{
    /**
     * @dataProvider validGameDataProvider
     */
    public function testCreateGame($event, $site, $date, $round, $white, $black, $result, $samplePgn)
    {
        /** @var ChessAdapter|\PHPUnit_Framework_MockObject_MockObject $adapterMock */
        $adapterMock = $this->createMock(ChessAdapter::class);
        $adapterMock
            ->expects($this->once())
            ->method('parsePgn')
            ->with($this->equalTo($samplePgn))
            ->willReturn([
                'header' => [
                    'Event' => $event,
                    'Site' => $site,
                    'Date' => $date,
                    'Round' => $round,
                    'White' => $white,
                    'Black' => $black,
                    'Result' => $result
                ]
            ]);

        $whitePlayer = new Player($white);
        $blackPlayer = new Player($black);

        /** @var PlayerRepository|\PHPUnit_Framework_MockObject_MockObject $playerRepoMock */
        $playerRepoMock = $this->createMock(PlayerRepository::class);
        $playerRepoMock
            ->expects($this->exactly(2))
            ->method('findOrCreateNewPlayerByName')
            ->with($this->logicalOr(
                $this->equalTo($white),
                $this->equalTo($black)
            ))
            ->willReturnCallback(function($arg){
                return new Player($arg);
            });

        $importer = new PgnStringToGameImporter($adapterMock, $playerRepoMock);

        $importedGame = $importer->createChessGame($samplePgn);

        $this->assertEquals($event, $importedGame->getEvent());
        $this->assertEquals($site, $importedGame->getSite());
        $this->assertEquals(\DateTime::createFromFormat('Y.m.d', $date), $importedGame->getDate());
        $this->assertEquals($round, $importedGame->getRound());
        $this->assertEquals($result, $importedGame->getResult());
        $this->assertEquals($whitePlayer, $importedGame->getWhite());
        $this->assertEquals($blackPlayer, $importedGame->getBlack());
        $this->assertEquals($samplePgn, $importedGame->getPgn());
    }

    public function validGameDataProvider()
    {
        return [
            ['event', 'site', '1985.09.26', 'round', 'white', 'black', '1-0', 'dummy'],
            ['event', 'site', '1985.09.26', 'round', 'white', 'black', '0-1', 'dummy'],
            ['event', 'site', '1985.09.26', 'round', 'white', 'black', '1/2-1/2', 'dummy'],
            ['event', 'site', '1985.09.26', 'round', 'white', 'black', '*', 'dummy'],
        ];
    }
}
