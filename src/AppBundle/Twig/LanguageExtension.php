<?php declare(strict_types = 1);

namespace AppBundle\Twig;

use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Routing\Generator\UrlGeneratorInterface;

class LanguageExtension extends \Twig_Extension
{
    private $locales;
    private $router;

    public function __construct(UrlGeneratorInterface $router, array $locales = [])
    {
        $this->locales = $locales;
        $this->router = $router;
    }

    public function getFunctions()
    {
        return [
            new \Twig_SimpleFunction('language_selector', [$this, 'languageSelector'], ['is_safe' => ['html']]),
        ];
    }

    public function languageSelector(Request $request)
    {
        $route = $request->attributes->get('_route', 'app_homepage');
        $params = array_merge(
            $request->attributes->get('_route_params', []),
            $request->query->all()
        );

        $buffer = [];
        foreach ($this->locales as $locale) {
            $path = $this->router->generate(
                $route,
                array_merge($params, ['_locale' => $locale])
            );

            $buffer[] = sprintf(
                '<a href="%s">%s</a>',
                $path,
                $locale
            );
        }

        return implode("\n", $buffer);
    }

    public function getName()
    {
        return 'app_language';
    }
}
