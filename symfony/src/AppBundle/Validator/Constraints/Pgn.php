<?php


namespace AppBundle\Validator\Constraints;

use Symfony\Component\Validator\Constraint;

/**
 * @Annotation
 */
class Pgn extends Constraint
{
    public $message = 'validator.pgn.message';
}
