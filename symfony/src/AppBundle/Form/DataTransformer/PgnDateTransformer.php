<?php

namespace AppBundle\Form\DataTransformer;

use AppBundle\Domain\PgnDate;
use Symfony\Component\Form\DataTransformerInterface;

class PgnDateTransformer implements DataTransformerInterface
{
    public function transform($value)
    {
        if (empty($value)) {
            return '????.??.??';
        }

        return $value->toString();
    }

    public function reverseTransform($value)
    {
        return PgnDate::fromString($value);
    }
}
