<?php

declare (strict_types=1);

namespace DragonBe\Vies\SymfonyValidatorConstraint;

use DragonBe\Vies\Vies;
use DragonBe\Vies\ViesServiceException;
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


        // first we look at the country the user has selected
        // we need country to validate VAT
        $vies = new Vies();

        $vatCountry = substr($value, 0, 2);
        $vatId = substr($value, 2);

        // Greece uses EL as a VAT prefix, but the country code is GR. Make adjustments for this.
        // https://github.com/DragonBe/vies/issues/57
        $euCountries = $vies->listEuropeanCountries();
        $euCountries['GR'] = $euCountries['EL'];
        unset($euCountries['EL']);

        // we check only countries from UE
        if (!array_key_exists($country, $euCountries)) {
            return;
        }

        if ($vatCountry !== $country) {
            // if vat number is not starting with country code from billing country starting from invalid country
            $this->context->buildViolation('sirvoy_affiliate.vat_inside_contry')
                ->setTranslationDomain('validators')
                ->atPath($propertyName)
                ->addViolation();

            // if we get this error we are not needed searching for other errors (not need to ask online service)
            return;
        }

        // now we validate the vatNo - try online validation first, fallback to checksum if we get Exception
        try {
            $result = $vies->validateVat($vatCountry, $vatId);
            $vatIdValid = $result->isValid();
        } catch (ViesServiceException $e) {
            $vatIdValid = $vies->validateVatSum($vatCountry, $vatId);
        }

        if (!$vatIdValid) {
            $this->context->buildViolation('sirvoy_affiliate.vat_invalid')
                ->setTranslationDomain('validators')
                ->atPath($propertyName)
                ->addViolation();
        }
    }
}
