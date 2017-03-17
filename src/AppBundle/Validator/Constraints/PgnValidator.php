<?php declare(strict_types = 1);

namespace AppBundle\Validator\Constraints;

use AppBundle\PgnParser\RyanhsChessPgnParser;
use Symfony\Component\Validator\Constraint;
use Symfony\Component\Validator\ConstraintValidator;

class PgnValidator extends ConstraintValidator
{
    private $chess;

    public function __construct(RyanhsChessPgnParser $chess)
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
