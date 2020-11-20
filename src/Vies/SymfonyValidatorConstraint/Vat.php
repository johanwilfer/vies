<?php

declare (strict_types=1);

namespace DragonBe\Vies\SymfonyValidatorConstraint;

use Symfony\Component\Validator\Constraint;

/**
 * @Annotation
 * @Target({"PROPERTY", "METHOD", "ANNOTATION"})
 *
 * @author Johan Wilfer <johan@jttech.se>
 */
class Vat extends Constraint
{
    public $message = 'Not valid';

    //4. Not registered for VAT Ok
    //5. Valid / not valid (checksum)


    //1. Not inside country
    //2. Required for EU countries
    //3. Should not be given for non-EU contries
    public $countryField = null;

    public $mode; // "online", "checksum", "online_checksum"
    //
    //online only
    //checksum only
    //online + checksum
    //

    // not registered for VAT?
}
