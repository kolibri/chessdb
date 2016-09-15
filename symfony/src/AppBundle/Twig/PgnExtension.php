<?php


namespace AppBundle\Twig;


use AppBundle\Entity\Game;
use AppBundle\Helper\MovesTransformHelper;

class PgnExtension extends \Twig_Extension
{
    /** @var MovesTransformHelper */
    private $movesTransformHelper;

    public function __construct(MovesTransformHelper $movesTransformHelper)
    {
        $this->movesTransformHelper = $movesTransformHelper;
    }

    public function getFunctions()
    {
        return [
            new \Twig_SimpleFunction('pgn', [$this, 'renderGame'], ['is_safe' => ['html']]),
        ];
    }

    public function renderGame(Game $game)
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
            $game->getEvent(),
            $game->getSite(),
            $game->getDate(),
            $game->getRound(),
            $game->getResult(),
            $game->getWhite(),
            $game->getBlack(),
            $this->movesTransformHelper->moveArrayToString($game->getMoves()),
            $game->getResult()
        );
    }

    public function getName()
    {
        return 'app.pgn';
    }
}