<?php

require __DIR__.'/../vendor/autoload.php';

use Andre\MeteoApp\Commands\MeteoCommand;
use Symfony\Component\Console\Application;

$application = new Application();

$application->add(new MeteoCommand());
$application->run();