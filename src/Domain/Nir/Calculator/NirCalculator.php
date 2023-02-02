<?php

declare(strict_types=1);

namespace App\Domain\Nir\Calculator;

use InvalidArgumentException;

use function mb_strtoupper;
use function sprintf;
use function str_replace;
use function strlen;
use function trim;

final class NirCalculator implements NirCalculatorInterface
{
    private const NIR_MODULO_KEY = 97;

    public function compute(string $value): int
    {
        $nir = mb_strtoupper(trim(str_replace(' ', '', $value)));

        if (13 !== strlen($nir)) {
            throw new InvalidArgumentException(
                sprintf('Cannot compute control key with a nir of length %s', strlen($nir))
            );
        }

        $sanitizedNir = (int) str_replace(['2A', '2B'], ['19', '18'], $nir);

        $remain = $sanitizedNir % self::NIR_MODULO_KEY;

        return self::NIR_MODULO_KEY - $remain;
    }
}
