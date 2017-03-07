<?php


namespace AppBundle\Form\DataTransformer;

use Symfony\Component\Form\DataTransformerInterface;

class PlayerAliasesTransformer implements DataTransformerInterface
{
    public function transform($value)
    {
        if (empty($value)) {
            return '';
        }

        return implode(',', $value);
    }

    public function reverseTransform($value)
    {
        return array_map(
            function ($item) {
                return trim($item);
            },
            explode(',', $value)
        );
    }
}
