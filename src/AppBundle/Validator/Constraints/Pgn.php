<?php declare(strict_types = 1);

namespace AppBundle\Validator\Constraints;

use Symfony\Component\Validator\Constraint;

/**
 * @Annotation
 */
class Pgn extends Constraint
{
    public $message = 'validator.pgn.message';
}
