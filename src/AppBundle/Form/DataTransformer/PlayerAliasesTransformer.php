<?php declare(strict_types = 1);

namespace AppBundle\Form\DataTransformer;

use Symfony\Component\Form\DataTransformerInterface;

class PlayerAliasesTransformer implements DataTransformerInterface
{
    public function transform($value): string
    {
        if (empty($value)) {
            return '';
        }

        return implode(',', $value);
    }

    public function reverseTransform($value): array
    {
        return array_map(
            function ($item) {
                return trim($item);
            },
            explode(',', $value)
        );
    }
}
