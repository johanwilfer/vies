<?php

declare (strict_types=1);

namespace DragonBe\Vies\SymfonyValidatorConstraint;

use Symfony\Component\Validator\Constraint;
use Symfony\Component\Validator\ConstraintValidator;
use Symfony\Component\Validator\Exception\UnexpectedTypeException;
//use Symfony\Component\Validator\Exception\UnexpectedValueException;

/**
 * @Annotation
 */
class VatValidator extends ConstraintValidator
{
    public function validate($value, Constraint $constraint)
    {
        if (!$constraint instanceof Vat) {
            throw new UnexpectedTypeException($constraint, Vat::class);
        }

        // custom constraints should ignore null and empty values to allow
        // other constraints (NotBlank, NotNull, etc.) take care of that
        if (null === $value || '' === $value) {
            return;
        }

        // Once we drop support for symfony 3.4 we can add this (not supported in 3.4)
        //if (!is_string($value)) {
        //    // throw this exception if your validator cannot handle the passed type so that it can be marked as invalid
        //    throw new UnexpectedValueException($value, 'string');
        //
        //    // separate multiple types using pipes
        //    // throw new UnexpectedValueException($value, 'string|int');
        //}

        // FIXME! Do validation here

        // to build a violation do
        if (true) {
            // the argument must be a string or an object implementing __toString()
            $this->context->buildViolation($constraint->message)
                ->setParameter('{{ string }}', $value)
                ->addViolation();
        }
    }
}
