<?php

namespace App\Command;

use App\Repository\LocationRepository;
use Symfony\Component\Console\Attribute\AsCommand;
use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Input\InputArgument;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Input\InputOption;
use Symfony\Component\Console\Output\OutputInterface;
use Symfony\Component\Console\Style\SymfonyStyle;

#[AsCommand(
    name: 'location:country_and_city',
    description: 'Add a short description for your command',
)]
class LocationCountryAndCityCommand extends Command
{
    public function __construct(private readonly LocationRepository $locationRepository)
    {
        parent::__construct();
    }

    protected function configure(): void
    {
        $this
            ->addArgument('country', InputArgument::REQUIRED, 'country')
            ->addArgument('city', InputArgument::REQUIRED, 'city')
        ;
    }

    protected function execute(InputInterface $input, OutputInterface $output): int
    {
        $io = new SymfonyStyle($input, $output);
        $country = $input->getArgument('country');
        $city = $input->getArgument('city');
        $location = $this->locationRepository->findOneBy([
            'country' => $country,
            'city' => $city,
        ]);
        $io->writeln(sprintf('City: %s, Country: %s, Longitude: %.6f, Latitude: %.6f',
            $location->getCity(),
            $location->getCountry(),
            $location->getLongitude(),
            $location->getLatitude(),
        ));

        return Command::SUCCESS;
    }
}
