<?php

namespace App\Command;

use App\Domain\Nir\Calculator\NirCalculatorInterface;
use App\Domain\Nir\Generator\NirGeneratorInterface;
use App\Domain\Nir\Validation\Constraints\Nir;
use Symfony\Component\Console\Attribute\AsCommand;
use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Input\InputOption;
use Symfony\Component\Console\Output\OutputInterface;
use Symfony\Component\Console\Style\SymfonyStyle;

use Symfony\Component\Validator\Validator\ValidatorInterface;

use function sprintf;
use function str_pad;
use function str_replace;

use const STR_PAD_LEFT;

#[AsCommand(
    name: 'nir:generate',
    description: 'Generate a NIR (French Registration Number)',
)]
class NirGenerateCommand extends Command
{
    public function __construct(
        private readonly NirCalculatorInterface $nirCalculator,
        private readonly NirGeneratorInterface $nirGenerator,
        private readonly ValidatorInterface $validator,
    ) {
        parent::__construct();
    }

    protected function configure(): void
    {
        $this
            ->addOption('count', 'c', InputOption::VALUE_REQUIRED, 'Number of NIR to be generated', 1)
            ->addOption('with-key', null, InputOption::VALUE_NONE, 'Display the computed control key')
            ->addOption('raw', '-r', InputOption::VALUE_NONE, 'Display the NIR without spaces')
        ;
    }

    protected function execute(InputInterface $input, OutputInterface $output): int
    {
        $io = new SymfonyStyle($input, $output);

        $generatedKeys = $input->getOption('count') ?? 1;
        $generateWithControlKey = $input->getOption('with-key') ?? false;
        $rawOutput = $input->getOption('raw') ?? false;

        for ($i = 0; $i < $generatedKeys; $i++) {
            $nir = $this->nirGenerator->generate();
            $key = str_pad($this->nirCalculator->compute($nir), 2, '0', STR_PAD_LEFT);

            $formattedNir = $generateWithControlKey ?
                sprintf('%s %s', $nir, $key) :
                $nir
            ;

            $rawNir = str_replace(' ', '', $formattedNir);
            if (0 !== $this->validator->validate($rawNir, [new Nir()])->count()) {
                $io->error("Generated key $formattedNir is not valid");
            }

            $io->writeln($rawOutput ? $rawNir : $formattedNir);
        }

        $io->success("$generatedKeys key(s) have been generated");

        return Command::SUCCESS;
    }
}
