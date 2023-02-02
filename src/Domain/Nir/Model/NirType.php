<?php

declare(strict_types=1);

namespace App\Domain\Nir\Model;

enum NirType: string
{
    case BornInFrance = 'born_in_france';

    case BornOverseas = 'born_overseas';

    case BornInForeignCountry = 'born_in_foreign_country';
}
