<?php

declare(strict_types=1);

namespace App\Domain\Nir\Generator;

use App\Domain\Nir\Model\NirType;

interface NirGeneratorInterface
{
    public function generate(NirType $type = NirType::BornInFrance): string;
}
