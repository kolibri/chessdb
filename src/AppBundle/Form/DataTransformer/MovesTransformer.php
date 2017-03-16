<?php declare(strict_types = 1);

namespace AppBundle\Form\DataTransformer;

use AppBundle\Helper\MovesTransformHelper;
use Symfony\Component\Form\DataTransformerInterface;

class MovesTransformer implements DataTransformerInterface
{
    public function transform($value)
    {
        if (empty($value)) {
            return '';
        }

        return MovesTransformHelper::moveArrayToString($value);
    }

    public function reverseTransform($value)
    {
        return MovesTransformHelper::moveStringToArray($value);
    }
}
