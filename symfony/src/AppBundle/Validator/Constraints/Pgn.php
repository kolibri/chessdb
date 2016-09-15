<?php


namespace AppBundle\Validator\Constraints;


use Symfony\Component\Validator\Constraint;

/**
 * @Annotation
 */
class Pgn extends Constraint
{
    public $message = 'The given string is not a valid PGN string.';

}