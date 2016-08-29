<?php


namespace AppBundle\Twig;


use AppBundle\Entity\Game;

class ChessExtension extends \Twig_Extension
{
    /** @var string */
    private $pgnClass;

    /**
     * @param string $pgnClass
     */
    public function __construct($pgnClass)
    {
        $this->pgnClass = $pgnClass;
    }

    public function getFilters()
    {
        return [
            new \Twig_SimpleFilter('gameTitle', [$this, 'renderGameTitle']),
        ];
    }

    public function getFunctions()
    {
        return [
            new \Twig_SimpleFunction('pgn', [$this, 'renderGame'], ['is_safe' => ['html']]),
        ];
    }

    public function renderGameTitle(Game $game)
    {
        return sprintf(
            '%s - %s: %s',
            $game->getWhite()->getName(),
            $game->getBlack()->getName(),
            ('*' === $game->getResult() ? 'unfinished' : $game->getResult())
        );
    }

    public function renderGame(Game $game, array $options = [])
    {
        $dataString = '';

        foreach ($options as $option => $value) {
            $dataString .= sprintf(' data-%s="%s"', $option, $value);
        }

        return sprintf('<div class="%s"%s>%s</div>', $this->pgnClass, $dataString, $game->getPgn());
    }

    public function getName()
    {
        return 'app.chess';
    }
}
