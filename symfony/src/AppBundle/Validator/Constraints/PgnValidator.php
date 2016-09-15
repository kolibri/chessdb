<?php


namespace AppBundle\Validator\Constraints;


use AppBundle\Adapter\ChessAdapter;
use Symfony\Component\Validator\Constraint;
use Symfony\Component\Validator\ConstraintValidator;

class PgnValidator extends ConstraintValidator
{
    /** @var ChessAdapter */
    private $chess;

    /**
     * PgnValidator constructor.
     * @param ChessAdapter $chess
     */
    public function __construct(ChessAdapter $chess)
    {
        $this->chess = $chess;
    }

    public function validate($value, Constraint $constraint)
    {
        if (!$this->chess->validatePgn($value)) {
            $this
                ->context
                ->buildViolation($constraint->message)
                ->addViolation();
        }
    }

}