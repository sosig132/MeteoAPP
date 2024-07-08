<?php


namespace Andre\MeteoApp\Commands;

use Andre\MeteoApp\Classes\VisualCrossingClass;
use Andre\MeteoApp\Classes\WeatherApiClass;
use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Input\InputArgument;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;

class MeteoCommand extends Command
{
    protected static $defaultName = 'meteo';

    protected function configure()
    {
        $this
            ->setDescription('Get the weather')
            ->addArgument('city', InputArgument::REQUIRED, 'The city to get the weather for')
            ->addArgument('service', InputArgument::REQUIRED, 'The weather service to use');
    }

    protected function execute(InputInterface $input, OutputInterface $output)
    {
        $city = $input->getArgument('city');
        $service = $input->getArgument('service');
        $weatherApi = null;
        if ($service == 'weatherapi') {
            $weatherApi = new WeatherApiClass();
        }
        elseif($service == 'visualcrossing'){
            $weatherApi = new VisualCrossingClass();
        }
        $temperature = $weatherApi->getMeteo($city);

        $output->writeln('City: ' . $city);
        $output->writeln('Service: ' . $service);
        $output->writeln('Temperature: ' . $temperature . '°C');

        return Command::SUCCESS;
    }
}
