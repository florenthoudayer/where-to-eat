<?php

namespace App\Command;

use App\Entity\Address;
use App\Entity\Restaurant;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Input\InputOption;
use Symfony\Component\Console\Output\OutputInterface;
use Symfony\Component\Console\Style\SymfonyStyle;
use Symfony\Contracts\HttpClient\HttpClientInterface;

class RestaurantImporterCommand extends Command
{
    protected static $defaultName = 'restaurant:importer';
    protected static $defaultDescription = 'Add a short description for your command';

    private $httpClient;

    private $entityManager;

    public function __construct(HttpClientInterface $httpClient, EntityManagerInterface $entityManager)
    {
        parent::__construct();
        $this->httpClient = $httpClient;
        $this->entityManager = $entityManager;
    }

    protected function configure(): void
    {
        $this
            ->addOption('address', 'a', InputOption::VALUE_REQUIRED, 'Address from restaurants will be imported')
        ;
    }

    protected function execute(InputInterface $input, OutputInterface $output): int
    {
        $io = new SymfonyStyle($input, $output);
        $address = $input->getOption('address');

        if (!$address)
        {
            $address = $io->ask('Could you provide an address to locate restaurants nearby ?','5 avenue Anatole France, 75007 Paris');

        }

        $url = 'https://maps.googleapis.com/maps/api/place/textsearch/json?query='.$address.'&key='.$_ENV['GMAPS_KEY'].'&type=restaurant';
        $response = $this->httpClient->request('GET',$url);

        $output->writeln('Address used: '. $address);

        $body = $response->toArray();

        foreach ($body['results'] as $restaurantinfo)
        {
            $matches = [];
            preg_match_all('/([0-9].*),\s(\d+)(.*),(.*)/xsi', $restaurantinfo['formatted_address'], $matches);

            //Si aucune adresse trouvée.
            if (!$matches[1])
            {
                continue;
            }
            $address = new Address();
            $address->setStreet($matches[1][0]);
            $address->setZip($matches[2][0]);
            $address->setCity($matches[3][0]);
            $address->setCountry($matches[4][0]);

            $restaurant = new Restaurant();
            $restaurant->setName($restaurantinfo['name']);
            $restaurant->setAddress($address);

            $this->entityManager->persist($restaurant);
            $this->entityManager->flush();
        }

        return Command::SUCCESS;
    }
}
