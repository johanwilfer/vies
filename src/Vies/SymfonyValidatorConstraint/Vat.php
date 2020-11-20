<?php

declare (strict_types=1);

namespace DragonBe\Vies\SymfonyValidatorConstraint;

use Symfony\Component\Validator\Constraint;

/**
 * @Annotation
 */
class Vat extends Constraint
{
    public $message = 'Not valid';
}
