<?php

declare(strict_types=1);

namespace App\Domain\Nir\Validation\Constraints;

use App\Domain\Nir\Calculator\NirCalculatorInterface;
use Symfony\Component\Validator\Constraint;
use Symfony\Component\Validator\ConstraintValidator;
use Symfony\Component\Validator\Exception\UnexpectedTypeException;

use function in_array;
use function is_string;
use function preg_match;
use function str_replace;
use function strlen;

final class NirValidator extends ConstraintValidator
{
    public function __construct(private readonly NirCalculatorInterface $nirCalculator)
    {
    }

    public function validate(mixed $value, Constraint $constraint): void
    {
        if (!$constraint instanceof Nir) {
            throw new UnexpectedTypeException($constraint, Nir::class);
        }

        if (null === $value) {
            return;
        }

        if (!is_string($value)) {
            throw new UnexpectedTypeException($value, 'string');
        }

        $value = str_replace(' ', '', $value);

        if (!in_array(strlen($value), [13, 15], true)) {
            $this->context
                ->buildViolation($constraint->message)
                ->setCode(Nir::LENGTH_ERROR)
                ->addViolation()
            ;

            return;
        }

        if (!$this->isValid($value)) {
            $this->context
                ->buildViolation($constraint->message)
                ->setCode(Nir::INVALID_ERROR)
                ->addViolation()
            ;

            return;
        }

        if (!$this->isControlKeyValid($value)) {
            $this->context
                ->buildViolation($constraint->message)
                ->setCode(Nir::INVALID_KEY_ERROR)
                ->addViolation()
            ;
        }
    }

    private function isValid(string $value): bool
    {
        return (bool) preg_match(
            '/^' .
            '(?<sex>[123478])' .
            '(?<year>[0-9]{2})' .
            '(?<month>0[1-9]|1[0-2]|[23][0-9]|4[0-2]|[5-9][0-9])' .
            '(?<department>0[1-9]|2[0-9AB]|[13-8][0-9]|9[0-9])' .
            '(?<municipality>(?!000)[0-9]{3})' .
            '(?<birthIndex>(?!000)[0-9]{3})' .
            '(?<key>[0-8][0-9]|9[0-6])?' .
            '$/',
            $value,
        );
    }

    private function isControlKeyValid(string $value): bool
    {
        if (13 === strlen($value)) {
            return true;
        }

        return ((int) mb_substr($value, -2)) === $this->nirCalculator->compute(mb_substr($value, 0, 13));
    }
}
