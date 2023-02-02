<?php

declare(strict_types=1);

namespace App\Domain\Nir\Generator;

use App\Domain\Nir\Model\NirType;
use Random\Randomizer;
use RuntimeException;

use function count;
use function range;
use function str_pad;

use const STR_PAD_LEFT;

final class NirGenerator implements NirGeneratorInterface
{
    private Randomizer $randomizer;

    public function __construct()
    {
        $this->randomizer = new Randomizer();
    }

    public function generate(NirType $type = NirType::BornInFrance): string
    {
        return
            $this->generateSex() . ' ' .
            $this->generateYear() . ' ' .
            $this->generateMonth() . ' ' .
            $this->generateBirthLocalization($type) . ' ' .
            $this->generateBirthIndex()
        ;
    }

    private function generateSex(): int
    {
        return $this->randomizer->getInt(1, 2);
    }

    private function generateYear(): string
    {
        $year = $this->randomizer->getInt(0, 99);

        return str_pad((string) $year, 2, '0', STR_PAD_LEFT);
    }

    private function generateMonth(): string
    {
        $availableMonths = [...range(1, 12), ...range(20, 42), ...range(50, 99)];

        $key = $this->randomizer->getInt(0, count($availableMonths) - 1);

        $month = $availableMonths[$key] ?? throw new RuntimeException('Cannot generate month part');

        return str_pad((string) $month, 2, '0', STR_PAD_LEFT);
    }

    private function generateBirthLocalization(NirType $type): string
    {
        return match ($type) {
            NirType::BornInFrance => $this->generateTypeBornInFrance(),
            NirType::BornOverseas => $this->generateTypeBornOverseas(),
            NirType::BornInForeignCountry => $this->generateTypeBornInForeignCountry()
        };
    }

    private function generateTypeBornInFrance(): string
    {
        $availableDepartments = [...range(1, 96), '2A', '2B'];

        $key = $this->randomizer->getInt(0, count($availableDepartments) - 1);

        $department = $availableDepartments[$key] ?? throw new RuntimeException('Cannot generate department');
        $formattedDepartment = str_pad((string) $department, 2, '0', STR_PAD_LEFT);

        $municipality = $this->randomizer->getInt(1, 999);
        $formattedMunicipality = str_pad((string) $municipality, 3, '0', STR_PAD_LEFT);

        return "$formattedDepartment $formattedMunicipality";
    }

    private function generateTypeBornOverseas(): string
    {
        $regionOfBirth = $this->randomizer->getInt(970, 989);

        $municipality = $this->randomizer->getInt(1, 99);
        $formattedMunicipality = str_pad((string) $municipality, 2, '0', STR_PAD_LEFT);

        return "$regionOfBirth $formattedMunicipality";
    }

    private function generateTypeBornInForeignCountry(): string
    {
        $countryOfBirth = $this->randomizer->getInt(1, 999);
        $formattedCountry = str_pad((string) $countryOfBirth, 3, '0', STR_PAD_LEFT);

        return "99 $formattedCountry";
    }

    private function generateBirthIndex(): string
    {
        return str_pad((string) $this->randomizer->getInt(1, 999), 3, '0', STR_PAD_LEFT);
    }
}
