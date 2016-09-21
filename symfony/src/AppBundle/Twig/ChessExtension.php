<?php

namespace AppBundle\Twig;

class ChessExtension extends \Twig_Extension
{
    const PGN_CLASS = 'pgn';

    public function getFunctions()
    {
        return [
            new \Twig_SimpleFunction('pgn', [$this, 'renderPgn'], ['is_safe' => ['html']]),
        ];
    }

    public function renderPgn($pgn, $options = [])
    {
        $attributes = '';
        foreach ($this->parseOptions($options) as $key => $value) {
            $attributes .= sprintf("%s='%s' ", $key, $value);
        }

        return sprintf('<div %s>%s</div>', $attributes, $pgn);
    }

    private function parseOptions($options = [])
    {
        $defaultOptions = [
            'data-show-buttons' => "true",
            'data-show-moves' => "false",
            'data-show-header' => "true",
            'data-label-next' => "&gt;&gt;",
            'data-label-back' => "&lt;&lt;",
            'data-label-reset' => "start",
            'data-label-turn' => "flip",
            'class' => 'pgn',
        ];

        return array_merge($defaultOptions, $options);
    }

    public function getName()
    {
        return 'app_chess';
    }
}