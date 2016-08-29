<?php

namespace Tests\AppBundle\Twig;

use AppBundle\Entity\Game;
use AppBundle\Twig\ChessExtension;

class ChessExtensionTest extends \PHPUnit_Framework_TestCase
{
    public function testRenderGame()
    {
        $extension = new ChessExtension('pgn');

        /** @var \PHPUnit_Framework_MockObject_MockObject|Game $gameMock */
        $gameMock = $this->createMock(Game::class);
        $gameMock
            ->expects($this->any())
            ->method('getPgn')
            ->willReturn('pgnString');

        $pgnDiv = $extension->renderGame($gameMock);

        $this->assertEquals('<div class="pgn">pgnString</div>', $pgnDiv);
    }


    public function testRenderGameWithOptions()
    {
        $extension = new ChessExtension('pgn');

        /** @var \PHPUnit_Framework_MockObject_MockObject|Game $gameMock */
        $gameMock = $this->createMock(Game::class);
        $gameMock
            ->expects($this->any())
            ->method('getPgn')
            ->willReturn('pgnString');

        $pgnDiv = $extension->renderGame($gameMock, ['show-moves' => 'true']);

        $this->assertEquals('<div class="pgn" data-show-moves="true">pgnString</div>', $pgnDiv);
    }
}
