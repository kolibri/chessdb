<?php


namespace AppBundle\Form\DataTransformer;

use AppBundle\Helper\MovesTransformHelper;
use Symfony\Component\Form\DataTransformerInterface;
use Symfony\Component\Form\Exception\TransformationFailedException;

class MovesTransformer implements DataTransformerInterface
{
    /** @var  MovesTransformHelper */
    private $transformHelper;

    public function __construct(MovesTransformHelper $transformHelper)
    {
        $this->transformHelper = $transformHelper;
    }

    public function transform($value)
    {
        if (empty($value)) {
            return '';
        }

        return $this->transformHelper->moveArrayToString($value);
    }

    public function reverseTransform($value)
    {
        return $this->transformHelper->moveStringToArray($value);
    }
}
