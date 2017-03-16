<?php declare(strict_types = 1);

namespace AppBundle\Form\DataTransformer;

use AppBundle\Domain\PgnDate;
use Symfony\Component\Form\DataTransformerInterface;

class PgnDateTransformer implements DataTransformerInterface
{
    public function transform($value):string
    {
        if (empty($value)) {
            return '????.??.??';
        }

        return $value->toString();
    }

    public function reverseTransform($value): PgnDate
    {
        return PgnDate::fromString($value);
    }
}
