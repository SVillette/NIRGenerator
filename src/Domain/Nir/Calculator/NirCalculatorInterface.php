<?php

declare(strict_types=1);

namespace App\Domain\Nir\Calculator;

interface NirCalculatorInterface
{
    public function compute(string $value): int;
}
